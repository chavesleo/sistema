<?php

class ProccessController extends BaseController {

	public function index($token){

		//Session::forget('proccess_init');

		#valida token, se nao existe token entao redireciona aviso
		$evaluation = Evaluation::where('token', $token)->first();
		if (!$evaluation) {
			return Response::view('errors.missing', array(), 404);
		}

		#valida se está ativo
		if ($evaluation->deleted_at != '') {
			return Response::view('errors.missing', array(), 404);
		}

		#verifica se sessao já não foi iniciada para este relatorio e redireciona
		if (Session::has('proccess_init') && in_array($token, Session::get('proccess_init.tk_proccess'))) {
			echo "<pre>";print_r(Session::all());exit;
			exit('tem a mesma sessão, redireciona para o questionário montado com as perguntas');
			//view
		}

		return View::make('login.proccessinit', compact('evaluation'));

	}

	public function startproccess($token){

		$dados = Input::all();

		//echo "<pre>";print_r($dados);exit;

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

		//cria um registro na proccess, com id do candidato e da avaliação, zera o progresso e a nota final
		$newProccess = new Proccess;
		$newProccess->candidate_id = $candidate->id;
		$newProccess->evaluation_id = $evaluation->id;
		$newProccess->progress = 0;
		$newProccess->status = 'i';
		$newProccess->final_note = 0;
		$newProccess->save();

		//cria a sessão para este usuario e esta avaliação
		Session::put(
					array('proccess_init' => array(
												'tk_proccess' => array($token),
												'candidate' => $candidate)
						));

		return Redirect::to(URL::current());

		//lista todas perguntas da avaliação e joga na tela com um botão finalizar no final, validando as obrigatórias

		//salva as perguntas via ajax e calcula prograsso
		
	}

}
