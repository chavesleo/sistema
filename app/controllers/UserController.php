<?php

class UserController extends BaseController {

	function login(){

	    $rules = array( 'email' => 'required|email|exists:user,email',
				        'password' => 'required|min:4'
		    			);

	    $validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {
			return Redirect::to('login')->withErrors($validator);
		}

		$dados = Input::all();

		if (!Auth::attempt(array('email' => $dados['email'], 'password' => $dados['password']))) {
		    
			Session::put('alerta', Lang::get('textos.login_invalido'));

		}

		return Redirect::intended('/');

	}

	function listar(){
		$menuAtivo = 5;
		$cssPagina = 'css/user/list.css';
		$jsPagina = 'js/user/list.js';
		$tituloPagina = 'Usuários';
		$users = Company::find(Auth::user()->company_id)->users;

		return View::make('user.template', compact('menuAtivo','users', 'cssPagina', 'jsPagina','tituloPagina'));
	}

	function delete(){

		$id = Input::get('id');

		#carrega usuario do id recebido
		$user = User::find($id);

		#compara empresas
		if ($user->company_id != Auth::user()->company_id) {
			$mensagem['tipo'] = "danger";
			$mensagem['texto'] = '<span class="glyphicon glyphicon-remove-circle" aria-hidden="true"></span>&nbsp;&nbsp;Acesso Negado!';

			Session::put('alert', $mensagem);
			return Redirect::to('user/list');
		}

		#remove usuario
		//$user->delete();

		#retorna para listagem com mensagem
		$mensagem['tipo'] = "success";
		$mensagem['texto'] = '<span class="glyphicon glyphicon-ok-circle" aria-hidden="true"></span>&nbsp;&nbsp;'."$user->fullname foi removido! Para remover de verdade descomente a linha";

		Session::put('alert', $mensagem);
		return Redirect::to('user/list');

	}

	function action(){
		
		$data = Input::all();

		if (is_numeric($data['id']) && $data['id'] != 0) {
			$user = User::find($data['id']);

			#compara empresas
			if ($user->company_id != Auth::user()->company_id) {
				$mensagem['tipo'] = "danger";
				$mensagem['texto'] = '<span class="glyphicon glyphicon-remove-circle" aria-hidden="true"></span>&nbsp;&nbsp;Acesso Negado!';

				Session::put('alert', $mensagem);
				return Redirect::to('user/list');
			}
			$mensagem['texto'] = '<span class="glyphicon glyphicon-ok-circle" aria-hidden="true"></span>&nbsp;&nbsp;Usuário alterado com sucesso!';
		}else{
			$user = new User();
			$user->company_id = Auth::user()->company_id;
			$mensagem['texto'] = '<span class="glyphicon glyphicon-ok-circle" aria-hidden="true"></span>&nbsp;&nbsp;Usuário criado com sucesso!';
		}

		$user->fullname = $data['name'];
		$user->email = $data['email'];

		if (trim($data['password'] != '')) {
			$user->password = Hash::make($data['password']);
		}elseif(trim($data['password']) == '' && !trim($data['id'])){
			$mensagem['tipo'] = "warning";
			$mensagem['texto'] = '<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>&nbsp;&nbsp;<b>Atenção:</b> Senha não pode ser vazia!';

			Session::put('alert', $mensagem);
			return Redirect::to('user/list');
		}

		$user->save();
		$mensagem['tipo'] = "success";

		Session::put('alert', $mensagem);
		return Redirect::to('user/list');
	}

	function changepass(){

		$data = Input::all();

	    $rules = array( 'old_password' => 'required|min:4',
				        'new_password' => 'required|min:4|confirmed'
		    			);

	    $validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {
			return Redirect::to('/')->withErrors($validator);
		}
		
		if (Hash::check($data['old_password'], Auth::user()->password)) {

			Auth::user()->password = Hash::make($data['new_password']);
			Auth::user()->save();

			$mensagem['tipo'] = "success";
			$mensagem['texto'] = '<span class="glyphicon glyphicon-ok-circle" aria-hidden="true"></span>&nbsp;&nbsp;Senha alterada com sucesso!';
			
		}else{
			$mensagem['tipo'] = "danger";
			$mensagem['texto'] = '<span class="glyphicon glyphicon-remove-circle" aria-hidden="true"></span>&nbsp;&nbsp;Senha <strong>NÃO ALTERADA</strong>, pois não confere!';
		}

		Session::put('alert', $mensagem);
		return Redirect::to('/');
	}

}