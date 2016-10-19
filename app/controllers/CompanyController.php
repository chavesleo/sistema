<?php

class CompanyController extends BaseController {

	public function cadastrar(){

		$dados = Input::all(); //echo "<pre>";print_r($dados);exit;

	    $rules = array( 'company_name' => 'required|min:5',
				        'segment_id' => 'required',
				        'user_name' => 'required|min:5',
				        'user_email' => 'required|email|unique:user,email',
				        'user_pass' => 'required|min:4|confirmed',
				        'logo' => 'image|mimes:jpg,jpeg,bmp,png|size:1'
		    			);

	    $validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {
			return Redirect::to('login')->withErrors($validator);
		}

		//criar nome unico para imagem
		//mover imagem para pasta de imagens
		//salvar nome da imagem no banco

		$company = new Company;
		$company->name = $dados['company_name'];
		$company->token = Hash::make($dados['company_name'].uniqid());
		$company->logo = '';
		$company->segment_id = $dados['segment_id'];
		$company->active = 's';
		$company->save();

		$user = new User;
		$user->company_id = $company->id;
		$user->fullname = $dados['user_name'];
		$user->password = Hash::make($dados['user_pass']);
		$user->email = $dados['user_email'];
		$user->save();

		if(Auth::loginUsingId($user->id)){
			#retorna para listagem com mensagem
			$mensagem['tipo'] = "success";
			$mensagem['texto'] = '<span class="glyphicon glyphicon-ok-circle" aria-hidden="true"></span>&nbsp;&nbsp; Cadastro realizado com sucesso!';

			Session::put('alert', $mensagem);

			return Redirect::to('evaluation/list');
		}else{
			exit('ocorreu um erro');
		}


	}

}
