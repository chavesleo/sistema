<?php

class ProccessController extends BaseController {

	public function index($token){

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
			exit('tem a mesma sessão, redireciona para o questionário');
		}

		return View::make('login.proccessinit', compact('evaluation'));

		//Session::put(array('proccess_init' => array('tk_proccess' => array($token))));
	}

	public function startproccess(){
		$dados = Input::all();

		echo "<pre>";print_r($dados);exit;
		
	}

}
