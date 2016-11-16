
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
		$totalCandidatosCadastrados = 0;

		$questionarios = Evaluation::where('company_id', Auth::user()->company_id)
										->with('proccesses.candidate')
										->get();
	

		//echo "<pre>";print_r($questionarios);echo "</pre>";exit;

		#CONTAGEM DOS PROCESSOS INICIADOS
		if ($questionarios) {
			foreach ($questionarios as $questionario) {
				foreach ($questionario->proccesses as $processo) {
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

					$totalCandidatosCadastrados += count($processo->candidate);
				}
			}

		}

/*
		$teste = new ProccessController;
		$teste->calculateNoteById(1);
*/
		return View::make('report.template', compact('totalProcessosIniciados',
													'totalProcessosAguardando',
													'totalProcessosFinalizados',
													'totalCandidatosCadastrados',
													'menuAtivo',
													'cssPagina',
													'jsPagina',
													'tituloPagina'));
	}

	public function candidatesList(){

		$menuAtivo = 6;
		$cssPagina = '';
		$jsPagina = '';
		$tituloPagina = 'Relatórios - Candidatos';

		$processos = new Proccess;
		$listaProcesso = $processos->listAllProccessByCompanyId();

		echo "<pre>";print_r($listaProcesso);echo "</pre>";exit;
		
		return View::make('report.candidatelist', compact('menuAtivo',
														'listaProcesso',
														'cssPagina',
														'jsPagina',
														'tituloPagina'));
	}

}

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