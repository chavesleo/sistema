<?php

use SoapBox\Formatter\Formatter;

class ProccessController extends BaseController {

	public function index($token){

		//Session::forget('proccess_init');
		$cssPagina = 'css/proccess/layout.css';
		$jsPagina = 'js/proccess/default.js';

		#valida token, se nao existe token entao redireciona aviso
		$evaluation = Evaluation::where('token', $token)
								->with('QuestionEvaluations.question.options')
								->with('Company')
								->first();
		if (!$evaluation) {
			return Response::view('errors.missing', array(), 404);
		}

		#valida se está ativo
		if ($evaluation->deleted_at != '') {
			return Response::view('errors.missing', array(), 404);
		}

		#verifica se sessao já não foi iniciada para este relatorio e redireciona
		if (Session::has('proccess_init') && in_array($token, Session::get('proccess_init.tk_proccess'))) {

			#calcula o percentual preenchido
			$arrayPercentCount = $this->calculateProgressById(Session::get('proccess_init.proccess_id'));
			
			$listaUf = State::orderBy('name')->get();
			
			foreach ($evaluation->QuestionEvaluations as $ddQuestao) {

				$resposta = ProccessAnswer::where('proccess_id', '=', Session::get('proccess_init.proccess_id'))
											->where('question_id', '=', $ddQuestao->question->id)
											->first();
				if ($resposta) {

					#resposta do tipo cidade
					if ($ddQuestao->question->type == 'l' || $ddQuestao->question->type == 'o') {
						$selectedCity = City::where('id', $resposta->text)->first();
						$comboCitiesOfUf = City::where('state_id', $selectedCity->state_id)->orderBy('name', 'asc')->get();
						$arrayRespostas[$ddQuestao->question->id] = array('text' => $resposta->text, 
																		  'option_id' => $resposta->option_id, 
																		  'selected_state' => $selectedCity->state_id,
																		  'comboCityOfState' => $comboCitiesOfUf);
					}else if($ddQuestao->question->type == 'd'){
						$arrayMultiplasMarcadas = explode(",",$resposta->text);
						//echo "<pre>";print_r($arrayMultiplasMarcadas);exit;

						$arrayRespostas[$ddQuestao->question->id] = array('text' => $resposta->text, 'option_id' => $resposta->option_id, 'arrayMultiplasMarcadas' => $arrayMultiplasMarcadas);
					}else{
						$arrayRespostas[$ddQuestao->question->id] = array('text' => $resposta->text, 'option_id' => $resposta->option_id);
					}
				}else{
					$arrayRespostas[$ddQuestao->question->id] = array('text' => NULL, 'option_id' => NULL, 'comboCityOfState' => array(), 'arrayMultiplasMarcadas' => array());
				}
			}

			return View::make('evaluation.proccess', compact('listaUf','arrayPercentCount','arrayRespostas','jsPagina','cssPagina','evaluation'));
		}

		return View::make('login.proccessinit', compact('evaluation'));

	}

	public function changeForcedStatus($id){
		$dados = Input::all();

		$processo = Proccess::where('id', $id)->where('company_id', Auth::user()->company_id)->first();

		if (!$processo || !$dados['new_status']) {
			#retorna para listagem com mensagem
			$mensagem['tipo'] = "danger";
			$mensagem['texto'] = '<span class="glyphicon glyphicon glyphicon-remove-circle" aria-hidden="true"></span>&nbsp;&nbsp; Acesso Negado!';

			Session::put('alert', $mensagem);

			return Redirect::to('report/proccessdetails/'.$id);
		}

		$processo->forced_status = 1;
		$processo->forced_comment = $dados['forced_obs'];
		$processo->status = $dados['new_status'];
		$processo->save();

		#retorna para listagem com mensagem
		$mensagem['tipo'] = "success";
		$mensagem['texto'] = '<span class="glyphicon glyphicon-ok-circle" aria-hidden="true"></span>&nbsp;&nbsp; Status Alterado!';

		Session::put('alert', $mensagem);

		return Redirect::to('report/proccessdetails/'.$id);
	}

	public function startproccess($token){

		$dados = Input::all();

		#valida token, se nao existe token entao redireciona aviso
		$evaluation = Evaluation::where('token', $token)->first();
		if (!$evaluation) {
			return Response::view('errors.missing', array(), 404);
		}

		//busca candidato por email, senão cria
		$candidate = Candidate::where('email',$dados['email'])->first();

		if (!$candidate) {
			$candidate = new Candidate;
			$candidate->email = $dados['email'];
			$candidate->fullname = '';
			$candidate->passcode = '';
			$candidate->save();
		}

		//resgata o processo iniciado, senão cria um novo
		$newProccess = Proccess::where('candidate_id', $candidate->id)
								->where('evaluation_id',$evaluation->id)
								->first();

		//echo "<pre>";print_r($newProccess);exit;

		//cria um registro na proccess, com id do candidato e da avaliação, zera o progresso e a nota final
		if (!$newProccess) {
			$newProccess = new Proccess;
			$newProccess->candidate_id = $candidate->id;
			$newProccess->evaluation_id = $evaluation->id;
			$newProccess->company_id = $evaluation->company_id;
			$newProccess->progress = 0;
			$newProccess->status = 'r';
			$newProccess->final_note = 0;
			$newProccess->save();
		}

		//cria a sessão para este usuario e esta avaliação
		Session::put(
					array('proccess_init' => array(
												'tk_proccess' 	=> array($token),
												'candidate' 	=> $candidate,
												'proccess_id' 	=> $newProccess->id )
						));

		return Redirect::to(URL::current());
	
	}

	/*
	* BOTAO FINALIZAR DO QUESTIONARIO
	*/
	public function finish(){

		$dados = Input::all();

		if (is_array($dados['resposta']) && count($dados['resposta']) > 0) {

			foreach ($dados['resposta'] as $idPergunta => $resposta) {

				if (is_array($resposta) || trim($resposta) != '') {

					$question = Question::where('id',$idPergunta)->first();

					#verifica se já não há resposta
					$objResposta = ProccessAnswer::where('proccess_id', '=', $dados['proccess_id'])
												->where('question_id', '=', $idPergunta)
												->first();


					if ($objResposta) {
						$objResposta->text = $resposta;
					}else{
						$objResposta = new ProccessAnswer;
						$objResposta->proccess_id = $dados['proccess_id'];
						$objResposta->question_id = $idPergunta;
						$objResposta->text = $resposta;
					}

					if ($question->type == 'c') {
						$objResposta->option_id = $resposta;

					}else if($question->type == 'd' && is_array($resposta)){
						$lista = $separador = '';
						foreach ($resposta as $idResposta) {
							$lista .= $separador.$idResposta;
							$separador = ',';
						}
						$objResposta->text = $lista;
					}//if tipo

					#salva o nome do candidato
					$processo = Proccess::where('id', $dados['proccess_id'])->first();

					if (trim(strtolower($question->text)) == 'nome' || 
						trim(strtolower($question->text)) == 'nome completo') {
						$candidato = Candidate::where('id', $processo->candidate_id)->first();
						$candidato->fullname = $resposta;
						$candidato->save();
					}

					$objResposta->save();

				}//trim vazio

			}//foreach

			#retorna para listagem com mensagem
			$mensagem['tipo'] = "success";
			$mensagem['texto'] = '<span class="glyphicon glyphicon-ok-circle" aria-hidden="true"></span>&nbsp;&nbsp;Formulário enviado!';

			Session::put('alert', $mensagem);

			return Redirect::to($dados['currenturl']);

		}

	}

	/*
	* SALVA A QUESTAO VIA AJAX
	*/
	public function ajaxSaveQuestion(){
		$dados = Input::all();

		//echo "<pre>";print_r($dados);exit;

		$question = Question::where('id',$dados['question_id'])->first();

		#verifica se já não há resposta
		$resposta = ProccessAnswer::where('proccess_id', '=', $dados['proccess_id'])
									->where('question_id', '=', $dados['question_id'])
									->first();

		if ($resposta) {
			$resposta->text = $dados['text'];
		}else{
			$resposta = new ProccessAnswer;
			$resposta->proccess_id = $dados['proccess_id'];
			$resposta->question_id = $dados['question_id'];
			$resposta->text = $dados['text'];
		}
		if ($question->type == 'c') {
			$resposta->option_id = $dados['text'];

		}else if($question->type == 'd' && is_array($dados['text'])){
			$lista = $separador = '';
			foreach ($dados['text'] as $idResposta) {
				$lista .= $separador.$idResposta;
				$separador = ',';
			}
			$resposta->text = $lista;
		}

		#salva o nome do candidato
		$processo = Proccess::where('id', $dados['proccess_id'])->first();

		if (trim(strtolower($question->text)) == 'nome' || 
			trim(strtolower($question->text)) == 'nome completo') {
			$candidato = Candidate::where('id', $processo->candidate_id)->first();
			$candidato->fullname = $dados['text'];
			$candidato->save();
		}

		$resposta->save();
		
		//OK
		return Response::make('', 201);

		//erro
		//return Response::make('', 500);
	}

	######################################################################################
	#					INICIO DAS FUNÇÕES PARA O RELATÓRIO
	######################################################################################

	/*
	* CALCULA A NOTA DO PROCESSO
	*/
	public function calculateNoteById($proccessId){

		$nota = 0;

		$processo = Proccess::where('id',$proccessId)->with('answers')->first();
		$formulario = Evaluation::where('id',$processo->evaluation_id)->first();

		if ($processo) {
			foreach ($processo->answers as $resposta) {

				$perguntaDestaResposta = Question::where('id',$resposta->question_id)->first();

				#Opção Única
				if ($perguntaDestaResposta->type == 'c') {
					$opcao = Option::where('id', $resposta->option_id)->first();
					$nota += $opcao->rating;
				}

				#Multi Opção soma notas
				if($perguntaDestaResposta->type == 'd'){
					$arrayOptionSelected = explode(',', $resposta->text);
					foreach ($arrayOptionSelected as $option_id_selected) {
						$opcao = Option::where('id', $option_id_selected)->first();
						$nota += $opcao->rating;
					}
				}

				#Cidade de Interesse
				if($perguntaDestaResposta->type == 'l'){
					
					$pesoDaPergunta = QuestionEvaluation::where('question_id', $perguntaDestaResposta->id)
														->where('evaluation_id', $processo->evaluation_id)
														->first();

					$planoExpansao = ExpansionPlan::where('id', $formulario->expansion_plan_id)
													->with('expansionPlanCities')
													->first();

					foreach($planoExpansao->expansionPlanCities as $cidadesDoPlano){
						if ($cidadesDoPlano->city_id == $resposta->text) {
							$nota += $pesoDaPergunta->rating;
						}
					}
				}

				#Investimento
				if($perguntaDestaResposta->type == 'l'){
					
				}
			}
		}

		$processo->final_note = $nota;
		$processo->save();

		//echo "<pre>";print_r($processo);exit;
		return $nota;

	}

	/*
	* CALCULA O PROGRESSO DO PROCESSO
	*/
	public function calculateProgressById($proccessId, $ajax = false){

		$processo = Proccess::where('id', $proccessId)->first();
		$evaluation = Evaluation::where('id', $processo->evaluation_id)
								->with('QuestionEvaluations.question')
								->first();

		$arrayPercentCount = array('total' => count($evaluation->QuestionEvaluations), 
									'answered' => 0,
									'percent_formated' => 0,
									'percent' => 0);

		foreach ($evaluation->QuestionEvaluations as $ddQuestao) {

			$resposta = ProccessAnswer::where('proccess_id', '=', $proccessId)
										->where('question_id', '=', $ddQuestao->question->id)
										->first();
			if ($resposta) {
				$arrayPercentCount['answered']++;
			}
		}

		#calculo do percentual
		$arrayPercentCount['percent'] = round((($arrayPercentCount['answered'] * 100) / $arrayPercentCount['total']), 1);
		$arrayPercentCount['percent_formated'] = number_format($arrayPercentCount['percent'], 1, ',', '');

		#salva o percentual na tabela		
		$processo->progress = $arrayPercentCount['percent'];
		$processo->save();

		#calcula a nota
		$this->calculateNoteById($proccessId);

		#faz análise secundaria
		$this->comparaRespostasOutrosQuestionarios($proccessId);

		#calcula status
		$this->calculateStatus($proccessId);

		if($ajax){
			echo json_encode($arrayPercentCount);
			return Response::make('', 201);
		}

		return $arrayPercentCount;

	}

	######################################################
	# CALCULA O STATUS DO CANDIDATO
	######################################################
	public function calculateStatus($proccessId, $returnArrayAux = false){

		$auxPercentual = $auxCidadeInteresse = $auxInvestimento = $auxCidadeRaio = false;

		$proccess = Proccess::where('id', $proccessId)->first();
		$evaluation = Evaluation::where('id', $proccess->evaluation_id)->first();	
		
		#resposta cidade de interesse
		$questionCityInterest = $this->getQuestion($proccess->evaluation_id, 'l');
		$respostaCidadeInteresse = ProccessAnswer::where('question_id',$questionCityInterest->id)->where('proccess_id',$proccessId)->first();
		$idRespostaCidadeInteresse = ($respostaCidadeInteresse) ? $respostaCidadeInteresse->text : false ;

		#resposta investimento
		$questionInvestmet = $this->getQuestion($proccess->evaluation_id, 'm');
		$respostaInvestimento = ProccessAnswer::where('question_id',$questionInvestmet->id)->where('proccess_id',$proccessId)->first();
		$valorRespostaInvestimento = ($respostaInvestimento) ? $respostaInvestimento->text : false ;

		//echo "<pre>";print_r($valorRespostaInvestimento);exit;

		#Percentual Acima
		if( $proccess->final_note >= $evaluation->min_note ){
			$auxPercentual = true;
		}

		#Cidade de Interesse
		if ($idRespostaCidadeInteresse) {
			$auxCidadeInteresse = $this->checkIfCityInterestIsInActionPlan($idRespostaCidadeInteresse,
																			$evaluation->expansion_plan_id);
		}

		#Cidade no RAIO
		if (!$auxCidadeInteresse) {
			$auxCidadeRaio = $this->checkIfCityAroundActionPlan($idRespostaCidadeInteresse,
																$evaluation->expansion_plan_id);
		}

		#Investimento
		if ($valorRespostaInvestimento) {
			$valorRespostaInvestimento = str_replace('.', '', $valorRespostaInvestimento).'.00';
			$auxInvestimento = $this->checkValidInvestment(floatval($valorRespostaInvestimento), $evaluation->expansion_plan_id);
		}

		###################################
		# CALCULA O STATUS
		###################################

		#APROVADO - Nota Acima (E) Cidade Interesse (E) Investimento 
		if ($auxPercentual && $auxCidadeInteresse && $auxInvestimento) {
			$proccess->status = 'a';

		#REPROVADO
		}elseif (!$auxPercentual && !$auxCidadeInteresse && 
				(!$auxCidadeRaio || ( $auxCidadeRaio && !$auxInvestimento) ) ||
				(!$auxPercentual && $auxCidadeInteresse && !$auxInvestimento) ||
				($auxPercentual && !$auxCidadeInteresse && !$auxCidadeRaio && $auxInvestimento) 
				) {

					$proccess->status = 'r';

		#EM ANALISE
		}elseif( ($auxPercentual && !$auxCidadeInteresse && $auxCidadeRaio && $auxInvestimento) ||
				 (!$auxPercentual && $auxCidadeInteresse && $auxInvestimento) ||
				 (!$auxPercentual && !$auxCidadeInteresse && $auxCidadeRaio &&  $auxInvestimento)
				){
					
					$proccess->status = 'e';

		#SEM DEFINIÇÃO AINDA
		}else{
			if ($auxPercentual) { echo "<br>tem percentual - "; }else{ echo "<br> nao tem percentual - "; }
			if ($auxCidadeInteresse) { echo "<br>tem cidade interesse - "; }else{echo "<br>nao cidade interesse - ";}
			if ($auxCidadeRaio) { echo "<br>tem cidade no raio - "; }else{echo "<br>nao cidade no raio - ";}
			if ($auxInvestimento) { echo "<br>tem investimento - "; }else{echo "<br>nao tem investimento";}
			$proccess->status = 'o';
			exit;
		}
		
		if (!$proccess->forced_status && !$proccess->secundary_status) {
			$proccess->save();
		}

		if ($returnArrayAux) {
			return array('auxPercentual' => $auxPercentual, 
						 'auxCidadeInteresse' => $auxCidadeInteresse,
						 'auxCidadeRaio' => $auxCidadeRaio,
						 'auxInvestimento' => $auxInvestimento);
		}
	}

	######################################################
	# Verifica se está na cidade de intersse
	######################################################
	public function checkIfCityInterestIsInActionPlan($idRespostaCidadeInteresse, $idPlanoExpansao){
		$objCidadeInteresseSelecionada = City::where('id',$idRespostaCidadeInteresse)->first();

		#lista cidades do plano de expansão
		$planoExpansao = ExpansionPlan::where('id', $idPlanoExpansao)
						->with('expansionPlanCities')
						->first();

		if ($planoExpansao) {
			foreach($planoExpansao->expansionPlanCities as $cidadeDoPlano){
				#cidade de interesse marcada está no plano
				if ($cidadeDoPlano->city_id == $idRespostaCidadeInteresse) {
					return true;
				}
			}//foreach
		}//tem plano expansao

		return false;
	}

	######################################################
	# Verifica se a cidade está no raio e pega a mais próxima
	######################################################
	public function checkIfCityAroundActionPlan($idRespostaCidadeInteresse, $idPlanoExpansao){

		if (!$idRespostaCidadeInteresse || !$idPlanoExpansao) {
			return false;
		}

		$objCidadeInteresseSelecionada = City::where('id',$idRespostaCidadeInteresse)->first();

		#lista cidades do plano de expansão
		$planoExpansao = ExpansionPlan::where('id', $idPlanoExpansao)
						->with('expansionPlanCities')
						->first();

		if ($planoExpansao) {
			$auxDistancia = false;
			foreach($planoExpansao->expansionPlanCities as $cidadeDoPlano){

				$objCidadeDoPlano = City::where('id',$cidadeDoPlano->city_id)->first();

				if ($objCidadeDoPlano) {
					$distancia = CityController::getDistance($objCidadeDoPlano->lat, 
															 $objCidadeDoPlano->lng, 
															 $objCidadeInteresseSelecionada->lat, 
															 $objCidadeInteresseSelecionada->lng);
					
					//echo '<br>'.$cidadeDoPlano->distance .'-'. $distancia ;
					if (intval($distancia) < floatval($cidadeDoPlano->distance)) {
						if (!$auxDistancia) {
							$auxDistancia = $distancia;
						}else if($auxDistancia > $distancia){
							$auxDistancia = $distancia;
						}else if($auxDistancia == $distancia){
	"Mesma distancia de cidades: Valores de Investimento Distintos";
						}
					}

				}//cidade do plano
			}//foreach
		}//tem plano expansao

		return $auxDistancia;
	}

	######################################################
	# Verifica se o investimento é valido
	######################################################
	public function checkValidInvestment($investment, $idPlanoExpansao){
		#lista cidades do plano de expansão
		$planoExpansao = ExpansionPlan::where('id', $idPlanoExpansao)
						->with('expansionPlanCities')
						->first();

		if ($planoExpansao) {
			foreach($planoExpansao->expansionPlanCities as $cidadeDoPlano){
				if ($investment >= $cidadeDoPlano->investment) {
					return true;
				}
			}
		}//tem plano expansao
		return false;
	}

	######################################################
	# Pega a questão de determinado tipo de um questionário
	######################################################
	public function getQuestion($evaluationId, $questionType){
		return DB::table('evaluation')
        		->join('question_evaluation', 'question_evaluation.evaluation_id', '=', 'evaluation.id')
	        	->join('question', 'question_evaluation.question_id', '=', 'question.id')
        		->select('question.*')
        		->where('evaluation.id', $evaluationId)
        		->where('question.type', $questionType)
	        	->first();
	}

	######################################################
	#   MONTA EXIBIÇÃO DO FORMULÁRIO RESPONDIDO
	######################################################
	public function montaQuestionario($idProcess, $returnFormat){

		# Recalcula Progresso
		$this->calculateProgressById($idProcess);
		
		$arrRetorno = array();
		
		if ($returnFormat == 'json') {
			$processo = Proccess::where('id',$idProcess)
								->with('answers')
								->first();
		}else{
			$processo = Proccess::where('id',$idProcess)
								->where('company_id', Auth::user()->company_id)
								->with('answers')
								->first();
		}

		
		$formulario = Evaluation::where('id',$processo->evaluation_id)->with('QuestionEvaluations')->first();
		
		$arrRetorno['titulo'] = $formulario->title;
		$arrRetorno['descricao'] = $formulario->description;
		$arrRetorno['nota_minima'] = $formulario->min_note;
		$arrRetorno['nota_final'] = $processo->final_note;
		$arrRetorno['progresso'] = $processo->progress;
		$arrRetorno['status'] = $processo->status;
		$arrRetorno['data_ini'] = $processo->created_at->format('d/m/Y H:i');

		if ($formulario) {
			foreach ($formulario->QuestionEvaluations as $dadosQuetaoFormulario) {

				$questao = Question::where('id', $dadosQuetaoFormulario->question_id)->first();
				$arrRetorno['pergunta'][$dadosQuetaoFormulario->order]['pergunta'] = $questao->text;
				$respostaQuestao = ProccessAnswer::where('proccess_id',$idProcess)->where('question_id',$questao->id)->first();
				
				if(!$respostaQuestao){
					$arrRetorno['pergunta'][$dadosQuetaoFormulario->order]['resposta'] = '';
				}else{
					#Opção Única
					if ($questao->type == 'c') {
						$opcao = Option::where('id', $respostaQuestao->option_id)->first();
						if ($opcao) {
							$arrRetorno['pergunta'][$dadosQuetaoFormulario->order]['resposta'] = $opcao->text;
						}else{
							$arrRetorno['pergunta'][$dadosQuetaoFormulario->order]['resposta'] = '';
						}
					
					#Multi Opção
					}elseif($questao->type == 'd'){
						$strResposta = '';
						$separador = '';
						
						$arrayOptionSelected = explode(',', $respostaQuestao->text);
						foreach ($arrayOptionSelected as $option_id_selected) {
							$opcao = Option::where('id', $option_id_selected)->first();
							$strResposta .= $separador.$opcao->text;
							$separador = ' - ';
						}
						$arrRetorno['pergunta'][$dadosQuetaoFormulario->order]['resposta'] = $strResposta;

					#Cidade de Interesse
					}elseif($questao->type == 'l' || $questao->type == 'o'){
						$cidade = City::where('id', $respostaQuestao->text)->first();
						if ($cidade) {
							$uf = State::where('id', $cidade->state_id)->first();
							$arrRetorno['pergunta'][$dadosQuetaoFormulario->order]['resposta'] = array('city_name'=>$cidade->name, 'uf_name'=>$uf->name, 'uf_short'=> $uf->short_name);
						}else{
							$arrRetorno['pergunta'][$dadosQuetaoFormulario->order]['resposta'] = array('city_name'=>$cidade->name, 'uf_name'=>$uf->name, 'uf_short'=> $uf->short_name);
						}
					}elseif($questao->type == 'm'){
						$arrRetorno['pergunta'][$dadosQuetaoFormulario->order]['resposta'] = 'R$ '.$respostaQuestao->text.',00';
					}else{
						$arrRetorno['pergunta'][$dadosQuetaoFormulario->order]['resposta'] = $respostaQuestao->text;
					}
				}//tem resposta
			}
		}

		if ($returnFormat == 'json') {
			return json_encode($arrRetorno);
		}

		if ($returnFormat == 'array') {
			return $arrRetorno;
		}

	}

	###################################################################
	#   Compara as respostas de um processo com outros questionários
	###################################################################
	public function comparaRespostasOutrosQuestionarios($proccessId){

		$arrayAuxiliar = array();
		$nota = 0;

		#lista o processo com suas respostas originais
		$processo = Proccess::where('id', $proccessId)->with('answers')->first();

		#se processo é aprovado entao nem verifica outros
		if ($processo->status == 'a') {
			return $arrayAuxiliar;
		}

		#verifica se tem alguma resposta já preenchida
		if ($processo->answers && count($processo->answers) > 0) {

			#lista todos formularios criados da empresa
			$evaluations = Evaluation::where('id', '<>', $processo->evaluation_id)
									   ->where('company_id', $processo->company_id)
									   ->with('QuestionEvaluations')
									   ->get();

   			if (count($evaluations) < 1) {
   				return $arrayAuxiliar;
   			}
		   	
			#percorre os formulários encontrados
	   		foreach ($evaluations as $dadosQuestionarios) {

	   			#vefifica se existe processo respondido para este formulario
	   			$processoRespondido = Proccess::where('company_id', $processo->company_id)
	   											->where('evaluation_id', $dadosQuestionarios->id)
	   											->where('candidate_id', $processo->candidate_id)
	   											->where('id', '<>', $processo->evaluation_id)
	   											->get();

	   			if (count($processoRespondido) > 0) {
	   				return $arrayAuxiliar;
	   			}

   				#percorre as perguntas dos formulários encontrados
	   			if($dadosQuestionarios->QuestionEvaluations && count($dadosQuestionarios->QuestionEvaluations) > 0){
	   				
	   				foreach($dadosQuestionarios->QuestionEvaluations as $dadosQuestaoFormulario){

	   					#compara o id da pergunta com o da resposta do processo
	   					foreach ($processo->answers as $dadosResposta) {
	   						if ($dadosResposta->question_id == $dadosQuestaoFormulario->question_id) {
	   							
			   					$questao = Question::where('id', $dadosQuestaoFormulario->question_id)->first();

	   							$arrayAuxiliar[$dadosQuestionarios->id]['title'] = $dadosQuestionarios->title;
	   							$arrayAuxiliar[$dadosQuestionarios->id]['min_note'] = $dadosQuestionarios->min_note;
	   							$arrayAuxiliar[$dadosQuestionarios->id]['expansion_plan_id'] = $dadosQuestionarios->expansion_plan_id;
	   							$arrayAuxiliar[$dadosQuestionarios->id]['questoes'][$dadosResposta->question_id]['pergunta'] = $questao->text;
	   							$arrayAuxiliar[$dadosQuestionarios->id]['questoes'][$dadosResposta->question_id]['text'] = $dadosResposta->text;
	   							$arrayAuxiliar[$dadosQuestionarios->id]['questoes'][$dadosResposta->question_id]['rating'] = $dadosQuestaoFormulario->rating;

	   							#OPCAO
	   							if ($dadosResposta->option_id && $questao->type == 'c') {
	   								$opcaoMarcada = Option::where('id', $dadosResposta->option_id)->where('question_id', $dadosResposta->question_id)->first();
	   								$arrayAuxiliar[$dadosQuestionarios->id]['questoes'][$dadosResposta->question_id]['option_id'] =  $dadosResposta->option_id;
	   								$arrayAuxiliar[$dadosQuestionarios->id]['questoes'][$dadosResposta->question_id]['option_text'] = $opcaoMarcada->text;
	   								$arrayAuxiliar[$dadosQuestionarios->id]['questoes'][$dadosResposta->question_id]['option_rating'] = $opcaoMarcada->rating;
									$nota += $opcaoMarcada->rating;
	   							}

								#Multi Opção
								if($questao->type == 'd'){
									$arrayAuxiliar[$dadosQuestionarios->id]['questoes'][$dadosResposta->question_id]['option_rating'] = 0;
									$arrayOptionSelected = explode(',', $dadosResposta->text);
									foreach ($arrayOptionSelected as $option_id_selected) {
										$opcaoMarcada = Option::where('id', $option_id_selected)->first();
										$nota += $opcaoMarcada->rating;
										$arrayAuxiliar[$dadosQuestionarios->id]['questoes'][$dadosResposta->question_id]['option_rating'] += $opcaoMarcada->rating;
										$arrayAuxiliar[$dadosQuestionarios->id]['questoes'][$dadosResposta->question_id]['option_text'] = $opcaoMarcada->text;
									}
								}

								if ($questao->type == 'l') {
									$planoExpansao = ExpansionPlan::where('id', $dadosQuestionarios->expansion_plan_id)
																	->with('expansionPlanCities')
																	->first();

									$objCidadeMarcada = City::where('id', $dadosResposta->text)->with('state')->first();
									if ($objCidadeMarcada) {
										$arrayAuxiliar[$dadosQuestionarios->id]['questoes'][$dadosResposta->question_id]['option_text'] = $objCidadeMarcada->name." / ".$objCidadeMarcada->state->short_name;
									}
								}

	   							#cidade de interesse
	   							if ($questao->type == 'l') {
									$temCidade = false;
									foreach($planoExpansao->expansionPlanCities as $cidadesDoPlano){
										if ($cidadesDoPlano->city_id == $dadosResposta->text) {
											$temCidade = true;
										}
									}
									if ($temCidade) {
										$nota += $dadosQuestaoFormulario->rating;
										$arrayAuxiliar[$dadosQuestionarios->id]['analise']['cidade_interesse'] = 1;
									}else{
										$arrayAuxiliar[$dadosQuestionarios->id]['analise']['cidade_interesse'] = false;

										#Cidade no RAIO
										$auxCidadeRaio = $this->checkIfCityAroundActionPlan($dadosResposta->text,
																							$dadosQuestionarios->expansion_plan_id);

										$arrayAuxiliar[$dadosQuestionarios->id]['analise']['cidade_no_raio'] = $auxCidadeRaio;

									}
	   							}

	   							#investimento
	   							if ($questao->type == 'm') {
	   								$temInvestimento = false;
									foreach($planoExpansao->expansionPlanCities as $cidadesDoPlano){

										$investimentoMarcado = str_replace(".", "", $dadosResposta->text).".00";

										if ($investimentoMarcado >= $cidadesDoPlano->investment) {
											$temInvestimento = true;
										}
									}
									if ($temInvestimento) {
										$nota += $dadosQuestaoFormulario->rating;
										$arrayAuxiliar[$dadosQuestionarios->id]['analise']['investimento'] = 1;
									}else{
										$arrayAuxiliar[$dadosQuestionarios->id]['analise']['investimento'] = false;
									}
	   							}

	   							$arrayAuxiliar[$dadosQuestionarios->id]['final_note'] = $nota;

									#Percentual Acima
								if( $nota >= $dadosQuestionarios->min_note ){
									$arrayAuxiliar[$dadosQuestionarios->id]['analise']['nota_minima'] = 1;
								}else{
									$arrayAuxiliar[$dadosQuestionarios->id]['analise']['nota_minima'] = false;
								}

	   						}//se questoes sao iguais

	   					}//foreach perguntas para comparar

	   				}//foreach formulários encontrados
	   			
	   			}//percorre as perguntas dos formulários encontrados

	   		}//percorre os formulários encontrados

			###################################
			# CALCULA O STATUS
			###################################
		   	if (count($arrayAuxiliar) > 0) {
		   		foreach ($arrayAuxiliar as $idForm => $ddForm) {
		   			
					#APROVADO - Nota Acima (E) Cidade Interesse (E) Investimento 
					if ($ddForm['analise']['nota_minima'] && $ddForm['analise']['cidade_interesse'] && $ddForm['analise']['investimento']) {
						$arrayAuxiliar[$idForm]['status'] = "a";
						$processo->status = 'a';
						$processo->secundary_status = 1;
						$processo->save();

					#REPROVADO
					}elseif (!$ddForm['analise']['nota_minima'] && !$ddForm['analise']['cidade_interesse'] && 
							(!$ddForm['analise']['cidade_no_raio'] || ( $ddForm['analise']['cidade_no_raio'] && !$ddForm['analise']['investimento']) ) ||
							(!$ddForm['analise']['nota_minima'] && $ddForm['analise']['cidade_interesse'] && !$ddForm['analise']['investimento']) ||
							($ddForm['analise']['nota_minima'] && !$ddForm['analise']['cidade_interesse'] && !$ddForm['analise']['cidade_no_raio'] && $ddForm['analise']['investimento']) 
							) {

								$arrayAuxiliar[$idForm]['status'] = 'r';
								$processo->secundary_status = 0;
								$processo->save();

					#EM ANALISE
					}elseif( ($ddForm['analise']['nota_minima'] && !$ddForm['analise']['cidade_interesse'] && $ddForm['analise']['cidade_no_raio'] && $ddForm['analise']['investimento']) ||
							 (!$ddForm['analise']['nota_minima'] && $ddForm['analise']['cidade_interesse'] && $ddForm['analise']['investimento']) ||
							 (!$ddForm['analise']['nota_minima'] && !$ddForm['analise']['cidade_interesse'] && $ddForm['analise']['cidade_no_raio'] &&  $ddForm['analise']['investimento'])
							){
								
								$arrayAuxiliar[$idForm]['status'] = 'e';
								$processo->status = 'e';
								$processo->secundary_status = 1;
								$processo->save();

					}//if status

		   		}//foreach forms

		   	}//nao é vazio

		   	//echo "<pre>";print_r($arrayAuxiliar);echo "</pre>";exit;
		   	return $arrayAuxiliar;

		}//tem respostas preenchidas
		
	}

	public static function getProccessByMonth(){
		$arrayCountMes = array(1 => 0,
							  2 => 0,
							  3 => 0,
							  4 => 0,
							  5 => 0,
							  6 => 0,
							  7 => 0,
							  8 => 0,
							  9 => 0,
							  10 => 0,
							  11 => 0,
							  12 => 0);

		$listaMes = '';
		$listaTotal = '';

		$processos = Proccess::where('company_id', Auth::user()->company_id)
								->select('created_at')
								->get();

		if (count($processos) > 0) {
			foreach ($processos as $ddProcessos) {
				$dataProcesso = new DateTime($ddProcessos['created_at']);
				$mes = $dataProcesso->format('n');
				$arrayCountMes[$mes]++;
			}
		}

		foreach($arrayCountMes as $numMes => $totalMes){
			if ($totalMes) {
				if ($numMes == 1) { $listaMes .= '"Janeiro",'; $listaTotal .= $totalMes.','; }
				if ($numMes == 2) { $listaMes .= '"Fevereiro",'; $listaTotal .= $totalMes.','; }
				if ($numMes == 3) { $listaMes .= '"Março",'; $listaTotal .= $totalMes.','; }
				if ($numMes == 4) { $listaMes .= '"Abril",'; $listaTotal .= $totalMes.','; }
				if ($numMes == 5) { $listaMes .= '"Maio",'; $listaTotal .= $totalMes.','; }
				if ($numMes == 6) { $listaMes .= '"Junho",'; $listaTotal .= $totalMes.','; }
				if ($numMes == 7) { $listaMes .= '"Julho",'; $listaTotal .= $totalMes.','; }
				if ($numMes == 8) { $listaMes .= '"Agosto",'; $listaTotal .= $totalMes.','; }
				if ($numMes == 9) { $listaMes .= '"Setembro",'; $listaTotal .= $totalMes.','; }
				if ($numMes == 10) { $listaMes .= '"Outrubro",'; $listaTotal .= $totalMes.','; }
				if ($numMes == 11) { $listaMes .= '"Novembro",'; $listaTotal .= $totalMes.','; }
				if ($numMes == 12) { $listaMes .= '"Dezembro",'; $listaTotal .= $totalMes.','; }
			}
			
		}

		return array('mes'=>substr($listaMes, 0,-1), 'total'=>substr($listaTotal, 0, -1));
	}

	public function exportJson($idProcess){
		echo $this->montaQuestionario($idProcess, 'json');
	}

	public function exportPdf($idProcess){
		echo $this->montaQuestionario($idProcess, 'json');
	}

	public function exportCsv($idProcess){

		$arrRetorno = json_decode($this->montaQuestionario($idProcess, 'json'), 1);

		$arrRetornoTratado = $this->trataVirgulas($arrRetorno);

		$formatter = Formatter::make($arrRetornoTratado, Formatter::ARR);

		$csv = $formatter->toCsv();

		header('Content-Disposition: attachment; filename="export.csv"');
		header("Cache-control: private");
		header("Content-type: application/force-download");
		header("Content-transfer-encoding: binary\n");

		echo $csv;exit;

	}

	public function trataVirgulas($array){

		$arrRetorno = array();
		
		foreach ($array as $chave => $valor) {
			if (is_array($valor) && count($valor) > 0) {
				
				foreach ($valor as $ordem => $perguntas) {
					$auxPergunta = str_replace(",", "&comma;", $perguntas['pergunta']);
					if (is_array($perguntas['resposta'])) {
						$auxResposta = '"'.$perguntas['resposta']['city_name'].' - '.$perguntas['resposta']['uf_short'].'"';
					}else{
						$auxResposta = str_replace(",", "&comma;", $perguntas['resposta']);
					}
					$arrRetorno['perguntas'][$ordem][$auxPergunta] = $auxResposta;
				}

			}else{
				$auxChave = str_replace(",", "&comma;", $chave);
				$auxValor = str_replace(",", "&comma;", $valor);
				$arrRetorno[$auxChave] = $auxValor;
			}
		}
		
		return $arrRetorno;

	}


}
