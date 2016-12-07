
<?php

class ReportController extends BaseController {

	private $arrayStatus = array('a' => 'Aprovado', 'e' => 'Em Análise', 'r' => 'Reprovado');

	###########################
	#   INDEX RELATORIOS
	###########################
	public function index(){

		$menuAtivo = 6;
		$cssPagina = 'css/report/default.css';
		$jsPagina = false;
		$tituloPagina = 'Relatórios';
		
		$totalProcessosAprovados = 0;
		$totalProcessosEmAnalise = 0;
		$totalProcessosReprovados = 0;

		$questionarios = Evaluation::where('company_id', Auth::user()->company_id)
										->with('proccesses.candidate')
										->get();

		$arrayProccessList = $this->getProccessList();

		$arrayStatus = $this->arrayStatus;

		$graficoMensal = ProccessController::getProccessByMonth();
	
		//echo "<pre>";print_r($graficoMensal);echo "</pre>";exit;

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
				}
			}

		}

		return View::make('report.template', compact('arrayProccessList',
													'arrayStatus',
													'graficoMensal',
													'totalProcessosAprovados',
													'totalProcessosEmAnalise',
													'totalProcessosReprovados',
													'menuAtivo',
													'cssPagina',
													'jsPagina',
													'tituloPagina'));
	}

	###########################
	#   LISTA PROCESSOS INDEX
	###########################
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

					#calula o progresso e as demais análises
					$objProcessoCalula = new ProccessController;
					$objProcessoCalula->calculateProgressById($dadosProcesso->proccess_id, false);

					#ajusta data
					$candidate_date_reg = new DateTime($dadosProcesso->candidate_date_reg);
					$proccess_init_date = new DateTime($dadosProcesso->proccess_init_date);
					
					$arrayRetorno[$dadosProcesso->candidate_id]['candidate_id'] = $dadosProcesso->candidate_id;
					$arrayRetorno[$dadosProcesso->candidate_id]['candidate_name'] = $dadosProcesso->candidate_name;
					$arrayRetorno[$dadosProcesso->candidate_id]['candidate_date_reg'] = $candidate_date_reg->format('d/m/Y H:i');
					$arrayRetorno[$dadosProcesso->candidate_id]['proccesses'][$dadosProcesso->proccess_id]['proccess_init_date'] = $proccess_init_date->format('d/m/Y H:i'); 
					$arrayRetorno[$dadosProcesso->candidate_id]['proccesses'][$dadosProcesso->proccess_id]['proccess_progress'] = $dadosProcesso->proccess_progress; 
					$arrayRetorno[$dadosProcesso->candidate_id]['proccesses'][$dadosProcesso->proccess_id]['evaluation_title'] = $dadosProcesso->evaluation_title; 
					$arrayRetorno[$dadosProcesso->candidate_id]['proccesses'][$dadosProcesso->proccess_id]['proccess_status'] = $dadosProcesso->proccess_status; 
					$arrayRetorno[$dadosProcesso->candidate_id]['proccesses'][$dadosProcesso->proccess_id]['proccess_note'] = $dadosProcesso->proccess_note; 
			}

			return (isset($arrayRetorno)) ? $arrayRetorno : array();

		}

		return array();

	}


	###########################
	#   DETALHES DO PROCESSO
	###########################
	public function showProccessDetailById($idProcess){

		$menuAtivo = 6;
		$cssPagina = 'css/report/default.css';
		$jsPagina = 'js/report/detail.js';
		$tituloPagina = 'Detalhes';
		$arrayStatus = $this->arrayStatus;

		$objProccessController = new ProccessController;
		$formularioCompleto = $objProccessController->montaQuestionario($idProcess, 'array');
		$objAnalisePrimaria = (object) $objProccessController->calculateStatus($idProcess, true);
		$arrayAnaliseSecundaria = $objProccessController->comparaRespostasOutrosQuestionarios($idProcess);
		
		$objProcessoCorrente = Proccess::where('id', $idProcess)->first();

		//$arrayAnaliseDetalhada = $objProccessController->detalharAnalise($idProcess, $objAnalisePrimaria);

		//echo "<pre>";print_r($arrayAnaliseSecundaria);echo "</pre>";exit;
		
		# dispara análise para outros questionários

		return View::make('report.proccessdetail', compact('formularioCompleto',
															'objAnalisePrimaria',
															'objProcessoCorrente',
															'arrayAnaliseSecundaria',
															'arrayStatus',
															'menuAtivo',
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