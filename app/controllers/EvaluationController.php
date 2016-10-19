<?php

class EvaluationController extends BaseController {

	function listar(){
		$menuAtivo = 2;
		$cssPagina = '';
		$jsPagina = 'js/evaluation/list.js';
		$tituloPagina = 'Formulários';

		$evaluations = Company::find(Auth::user()->company_id)->evaluations;

		return View::make('evaluation.template', compact('menuAtivo','evaluations', 'cssPagina', 'jsPagina','tituloPagina'));
	}

	function delete(){

		$id = Input::get('id');

		#carrega usuario do id recebido
		$evaluation = Evaluation::find($id);

		#compara empresas
		if ($evaluation->company_id != Auth::user()->company_id) {
			$mensagem['tipo'] = "danger";
			$mensagem['texto'] = '<span class="glyphicon glyphicon-remove-circle" aria-hidden="true"></span>&nbsp;&nbsp;Acesso Negado!';

			Session::put('alert', $mensagem);
			return Redirect::to('evaluation/list');
		}

		#remove usuario
		//$evaluation->delete();

		#retorna para listagem com mensagem
		$mensagem['tipo'] = "success";
		$mensagem['texto'] = '<span class="glyphicon glyphicon-ok-circle" aria-hidden="true"></span>&nbsp;&nbsp;'."$evaluation->title foi removido! Para remover de verdade descomente a linha";

		Session::put('alert', $mensagem);
		return Redirect::to('evaluation/list');

	}

	function adicionar(){
		
	}

}