
<?php

class ReportController extends BaseController {

	public function index(){
		$menuAtivo = 6;
		$cssPagina = 'css/report/default.css';
		$jsPagina = 'js/report/default.js';
		$tituloPagina = 'Relatórios';
		
		$totalProcessosIniciados = 0;
		$totalProcessosAguardando = 0;
		$totalProcessosFinalizados = 0;

		$Questionarios = Evaluation::where('company_id', Auth::user()->company_id)
										->with('proccesses')
										->get();
		/*
		$Questionarios = Evaluation::where('company_id', Auth::user()->company_id)
										->with(array('proccesses' => function($query)
										{
    										$query->where('status', 'like', 'i');
    										$query->orWhere('status', 'like', 'c');

										}))
										->get();
					echo "<pre>";print_r($processo);echo "</pre>";exit;
		*/

		#CONTAGEM DOS PROCESSOS INICIADOS
		if ($Questionarios) {
			foreach ($Questionarios as $Questionario) {
				foreach ($Questionario->proccesses as $processo) {
					switch ($processo->status) {
						case 'c':
							$totalProcessosAguardando++;
							break;
						case 'f':
							$totalProcessosFinalizados++;
							break;
						case 'i':
							$totalProcessosIniciados++;
							break;
						default:
							exit('erro, processo sem status');
							break;
					}
				}
			}

		}

		return View::make('report.template', compact('totalProcessosIniciados',
													'totalProcessosAguardando',
													'totalProcessosFinalizados',
													'menuAtivo',
													'cssPagina',
													'jsPagina',
													'tituloPagina'));
	}

}
