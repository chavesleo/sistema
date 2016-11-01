<?php

class ExpansionPlanController extends BaseController {

	public function listar(){
		$menuAtivo = 4;
		$cssPagina = false;
		$jsPagina = 'js/expansionplan/list.js';
		$tituloPagina = 'Planos de Expansão';

		$expansionPlans = Company::find(Auth::user()->company_id)->expansionPlans;

		return View::make('expansionplan.template', compact('expansionPlans','menuAtivo', 'cssPagina', 'jsPagina', 'tituloPagina'));
	}

	function action(){
		
		$data = Input::all();

		//echo "<pre>";print_r($data);exit;

		#compara datas
		$ini_date = new DateTime($data['start_date']);
		$end_date = new DateTime($data['end_date']);
		$interval = $ini_date->diff($end_date);

		if ($interval->format('%R%a') < 0) {
			$mensagem['tipo'] = "danger";
			$mensagem['texto'] = '<span class="glyphicon glyphicon-remove-circle" aria-hidden="true"></span>&nbsp;&nbsp;Data inicial não pode ser menor que a data final!';

			Session::put('alert', $mensagem);
			return Redirect::to('expansionplan/list');
		}

		if (is_numeric($data['id']) && $data['id'] != 0) {
			$expansionPlan = ExpansionPlan::find($data['id']);

			#compara empresas
			if ($expansionPlan->company_id != Auth::user()->company_id) {
				$mensagem['tipo'] = "danger";
				$mensagem['texto'] = '<span class="glyphicon glyphicon-remove-circle" aria-hidden="true"></span>&nbsp;&nbsp;Acesso Negado!';

				Session::put('alert', $mensagem);
				return Redirect::to('expansionplan/list');
			}
			$mensagem['texto'] = '<span class="glyphicon glyphicon-ok-circle" aria-hidden="true"></span>&nbsp;&nbsp;Plano de Expansão <strong>alterado</strong> com sucesso!';
		}else{
			$expansionPlan = new ExpansionPlan();
			$expansionPlan->company_id = Auth::user()->company_id;
			$mensagem['texto'] = '<span class="glyphicon glyphicon-ok-circle" aria-hidden="true"></span>&nbsp;&nbsp;Plano de Expansão <strong>criado</strong> com sucesso!';
		}

		if (is_array($data['format'])) {
			$separador = '';
			$listaFormato = '';
			foreach ($data['format'] as $formato => $valor) {
				$listaFormato .= $separador.$valor;
				$separador = ',';
			}
			$expansionPlan->format = $listaFormato;			
		}

		$expansionPlan->title = $data['title'];
		$expansionPlan->start_date = $data['start_date'];
		$expansionPlan->end_date = $data['end_date'];
		$expansionPlan->general_goal_units = $data['general_goal_units'];

		$expansionPlan->save();
		$mensagem['tipo'] = "success";

		Session::put('alert', $mensagem);
		return Redirect::to('expansionplan/list');
	}

	function delete(){

		$id = Input::get('id');

		#carrega usuario do id recebido
		$expansionPlan = ExpansionPlan::find($id);

		#compara empresas
		if ($expansionPlan->company_id != Auth::user()->company_id) {
			$mensagem['tipo'] = "danger";
			$mensagem['texto'] = '<span class="glyphicon glyphicon-remove-circle" aria-hidden="true"></span>&nbsp;&nbsp;Acesso Negado!';

			Session::put('alert', $mensagem);
			return Redirect::to('expansionplan/list');
		}

		#remove plano de expansão
		//$expansionPlan->delete();

		#retorna para listagem com mensagem
		$mensagem['tipo'] = "success";
		$mensagem['texto'] = '<span class="glyphicon glyphicon-ok-circle" aria-hidden="true"></span>&nbsp;&nbsp;'.$expansionPlan->title.' foi removido! Para remover de verdade descomente a linha';

		Session::put('alert', $mensagem);
		return Redirect::to('expansionplan/list');

	}

}
