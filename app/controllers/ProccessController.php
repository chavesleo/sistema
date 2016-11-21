<?php

//salva as perguntas via ajax e calcula prograsso

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

		//echo "<pre>";print_r($arrayPercentCount);exit;

		#calcula a nota
		$this->calculateNoteById($proccessId);

		#calcula status
		$this->calculateStatus($proccessId);

		if($ajax){
			echo json_encode($arrayPercentCount);
			return Response::make('', 201);
		}

		return $arrayPercentCount;

	}

	/*
	* CALCULA O STATUS DO CANDIDATO
	*/
	public function calculateStatus($proccessId){

		$proccess = Proccess::where('id', $proccessId)->first();
		$evaluation = Evaluation::where('id', $proccess->evaluation_id)->first();
		$questionCityInterest = Question::where('type','l')->first();
		$questionEvaluation = QuestionEvaluation::where('id', $questionCityInterest->id)->first();
		$questionAnswer = ProccessAnswer::where('question_id',$questionCityInterest->id)->where('proccess_id',$proccessId)->first();
		$idCidadeInteresse = ($questionAnswer) ? $questionAnswer->text : false ;

		if($proccess->final_note >= $evaluation->min_note){
			$proccess->status = 'a';

		}else{

			#se ja foi criada resposta de cidade de interesse
			if ($idCidadeInteresse) {

				$objCidadeInteresseSelecionada = City::where('id',$idCidadeInteresse)->first();

				#lista cidades do plano de expansão
				$planoExpansao = ExpansionPlan::where('id', $evaluation->expansion_plan_id)
								->with('expansionPlanCities')
								->first();

				//echo "<pre>";print_r($planoExpansao);exit;

				if ($planoExpansao) {

					$auxDistancia = 100;

					foreach($planoExpansao->expansionPlanCities as $cidadeDoPlano){

						#cidade de interesse marcada está no plano
						#então já foi calculada média, nao tem o que fazer
						if ($cidadeDoPlano->city_id == $idCidadeInteresse) {
							//echo ($idCidadeInteresse);
							break;
						}else{
							#calcula distância, se ta no raio salva em array
							#se array !vazio pega o menor raio

							$objCidadeDoPlano = City::where('id',$cidadeDoPlano->city_id)->first();

							$distancia = CityController::getDistance($objCidadeDoPlano->lat, 
																	$objCidadeDoPlano->lng, 
																	$objCidadeInteresseSelecionada->lat, 
																	$objCidadeInteresseSelecionada->lng);

							$auxDistancia = ($distancia <= $auxDistancia) ? $distancia : $auxDistancia;

							//salvar cidades e distancias em uma tabela ?
							
						}
						
					}
				}

				/*

				//$proccess->status = 'a';
				*/

			}

			#2 - Investimento x Formato Franquia

			$proccess->status = 'r';
		}

		$proccess->save();


	}

}
