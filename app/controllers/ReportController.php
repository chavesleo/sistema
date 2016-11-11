
<?php

class ReportController extends BaseController {

	public function index(){
		$menuAtivo = 6;
		$cssPagina = 'css/report/default.css';
		$jsPagina = 'js/report/default.js';
		$tituloPagina = 'Relatórios';

		$processosIniciados = Evaluation::where('company_id', Auth::user()->company_id)
										->with('proccesses.iniciados')
										->get();

		echo "<pre>";print_r($processosIniciados);exit;

		return View::make('report.template', compact('menuAtivo','cssPagina','jsPagina','tituloPagina'));
	}

}
