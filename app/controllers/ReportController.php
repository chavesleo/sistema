
<?php

class ReportController extends BaseController {

	public function index(){

		$menuAtivo = 6;
		$cssPagina = 'css/report/default.css';
		$jsPagina = 'js/report/default.js';
		$tituloPagina = 'Relatórios';
		
		$totalProcessosAprovados = 0;
		$totalProcessosEmAnalise = 0;
		$totalProcessosReprovados = 0;
		$totalCandidatosCadastrados = 0;

		$questionarios = Evaluation::where('company_id', Auth::user()->company_id)
										->with('proccesses.candidate')
										->get();

		$arrayProccessList = $this->getProccessList();

		$arrayStatus = array('a' => 'Aprovado', 'e' => 'Em Análise', 'r' => 'Reprovado');
	
		//echo "<pre>";print_r($questionarios);echo "</pre>";exit;

		#CONTAGEM DOS PROCESSOS INICIADOS
		if ($questionarios) {
			foreach ($questionarios as $questionario) {
				foreach ($questionario->proccesses as $processo) {
					switch ($processo->status) {
						case 'a':
							$totalProcessosAprovados++;
							break;
						case 'e':
							$totalProcessosEmAnalise++;
							break;
						case 'r':
							$totalProcessosReprovados++;
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
		return View::make('report.template', compact('arrayProccessList',
													'arrayStatus',
													'totalProcessosAprovados',
													'totalProcessosEmAnalise',
													'totalProcessosReprovados',
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

	public function getProccessList(){

		$queryResult = DB::table('proccess as p')
				        ->join('candidate as c', 'c.id', '=', 'p.candidate_id')
				        ->join('evaluation as e', 'e.id', '=', 'p.evaluation_id')
				        ->select('c.id as candidate_id', 
				        		'p.id as proccess_id',
			        			'e.id as evaluation_id',
				        		'c.fullname as candidate_name',
				        		'c.created_at as candidate_date_reg',
				        		'p.created_at as proccess_init_date',
			        			'p.progress as proccess_progress',
			        			'p.final_note as proccess_note',
			        			'p.status as proccess_status',
			        			'e.title as evaluation_title')
				        ->where('p.company_id', Auth::user()->company_id)
				        ->get();

		if ($queryResult) {

			foreach ($queryResult as $dadosProcesso) {

					$calculaProcesso = new ProccessController;
					$calculaProcesso->calculateProgressById($dadosProcesso->proccess_id);
					
					$arrayRetorno[$dadosProcesso->candidate_id]['candidate_id'] = $dadosProcesso->candidate_id;
					$arrayRetorno[$dadosProcesso->candidate_id]['candidate_name'] = $dadosProcesso->candidate_name;
					$arrayRetorno[$dadosProcesso->candidate_id]['candidate_date_reg'] = $dadosProcesso->candidate_date_reg;
					$arrayRetorno[$dadosProcesso->candidate_id]['proccesses'][$dadosProcesso->proccess_id]['proccess_init_date'] = $dadosProcesso->proccess_init_date; 
					$arrayRetorno[$dadosProcesso->candidate_id]['proccesses'][$dadosProcesso->proccess_id]['proccess_progress'] = $dadosProcesso->proccess_progress; 
					$arrayRetorno[$dadosProcesso->candidate_id]['proccesses'][$dadosProcesso->proccess_id]['evaluation_title'] = $dadosProcesso->evaluation_title; 
					$arrayRetorno[$dadosProcesso->candidate_id]['proccesses'][$dadosProcesso->proccess_id]['proccess_status'] = $dadosProcesso->proccess_status; 
					$arrayRetorno[$dadosProcesso->candidate_id]['proccesses'][$dadosProcesso->proccess_id]['proccess_note'] = $dadosProcesso->proccess_note; 
				
			}

			//echo "<pre>";print_r($arrayRetorno);echo "</pre>";exit;

			return (isset($arrayRetorno)) ? $arrayRetorno : false;

		}

		return false;

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