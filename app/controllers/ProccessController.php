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

			$arrayPercentCount = array('total' => count($evaluation->QuestionEvaluations), 'answered' => 0);
			$listaUf = State::orderBy('name')->get();
			
			foreach ($evaluation->QuestionEvaluations as $ddQuestao) {

				$resposta = ProccessAnswer::where('proccess_id', '=', Session::get('proccess_init.proccess_id'))
											->where('question_id', '=', $ddQuestao->question->id)
											->first();
				if ($resposta) {

					#resposta do tipo cidade
					if ($ddQuestao->question->type == 'l') {
						$selectedCity = City::where('id', $resposta->text)->first();
						$comboCitiesOfUf = City::where('state_id', $selectedCity->state_id)->get();
						//echo "<pre>";print_r($citiesOfUf);exit;
						$arrayRespostas[$ddQuestao->question->id] = array('text' => $resposta->text, 
																		  'option_id' => $resposta->option_id, 
																		  'selected_state' => $selectedCity->state_id,
																		  'comboCityOfState' => $comboCitiesOfUf);
					}else{
						$arrayRespostas[$ddQuestao->question->id] = array('text' => $resposta->text, 'option_id' => $resposta->option_id);
					}
					$arrayPercentCount['answered']++;
				}else{
					$arrayRespostas[$ddQuestao->question->id] = array('text' => NULL, 'option_id' => NULL, 'comboCityOfState' => array());
				}
			}

			#calculo do percentual
			$arrayPercentCount['percent'] = round((($arrayPercentCount['answered'] * 100) / $arrayPercentCount['total']), 1);
			$arrayPercentCount['percent_formated'] = number_format($arrayPercentCount['percent'], 1, ',', '');
	
			#salva o percentual na tabela		
			$newProccess = Proccess::where('id', Session::get('proccess_init.proccess_id'))
									->first();
			$newProccess->progress = $arrayPercentCount['percent'];
			if ($arrayPercentCount['percent'] == 100) {
				$newProccess->status='f';
			}
			$newProccess->save();

			//listar perguntas já respondidas
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

		//resgata o processo iniciado, senaõ cria um novo
		$newProccess = Proccess::where('candidate_id', $candidate->id)
								->where('evaluation_id',$evaluation->id)
								->first();

		//echo "<pre>";print_r($newProccess);exit;

		//cria um registro na proccess, com id do candidato e da avaliação, zera o progresso e a nota final
		if (!$newProccess) {
			$newProccess = new Proccess;
			$newProccess->candidate_id = $candidate->id;
			$newProccess->evaluation_id = $evaluation->id;
			$newProccess->progress = 0;
			$newProccess->status = 'i';
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

	public function finish(){
		$dados = Input::all();
		echo "<pre>";print_r($dados);exit;
	}

	public function ajaxSaveQuestion(){
		$dados = Input::all();

		#verifica o tipo de pergunta
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
		if ($question->type == 'c' || $question->type == 'd') {
			$resposta->option_id = $dados['text'];
		}

		$resposta->save();
		
		//OK
		return Response::make('', 201);

		//erro
		//return Response::make('', 500);
	}

}
