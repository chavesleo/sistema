<?php

class EvaluationController extends BaseController {

	private $arrayEnumTipo = array(	'a' => 'Texto Curto', 
									'b' => 'Texto Longo',
									'c' => 'Seleção Única',
									'd' => 'Seleção Múltipla',
									'e' => 'Somente Números',
									'f' => 'Data (11/11/1111)',
									'g' => 'CPF (999.999.999-99)',
									'h' => 'CNPJ (99.999.999/0001-99)',
									'i' => 'CEP (99.999-999)',
									'j' => 'Telefone (99) 99999-9999',
									'k' => 'E-mail (email@dominio.com)',
									'l' => 'Cidade de Interesse',
									'm' => 'Monetário',
									'n' => 'Investimento',
									'o' => 'Cidade de Residência');

	private $arrayEnumObrig = array('n' => 'Não', 
									's' => 'Sim');

	function listar(){
		$menuAtivo = 2;
		$cssPagina = '';
		$jsPagina = 'js/evaluation/list.js';
		$tituloPagina = 'Formulários';
		$evaluations = Company::find(Auth::user()->company_id)->evaluations;
		$expansionPlans = ExpansionPlan::where('company_id', Auth::user()->company_id)->get();

		return View::make('evaluation.template', compact('expansionPlans','menuAtivo','evaluations', 'cssPagina', 'jsPagina','tituloPagina'));
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

	function questionadd($idEvaluation){
		$menuAtivo = 2;
		$cssPagina = '';
		$jsPagina = 'js/evaluation/list.js';
		$tituloPagina = 'Formulários';
		$arrayEnumTipo = $this->arrayEnumTipo;
		$arrayEnumObrig = $this->arrayEnumObrig;
		$evaluation = Evaluation::find($idEvaluation);
		$insertedQuestions = QuestionEvaluation::where('evaluation_id', $idEvaluation)
												->with('question.options')
												->with('evaluation')
												->get();

		$questions = Question::where('company_id', Auth::user()->company_id)
								->with('options')
								->get();

		#retira da lista de adição as questões que já foram adicionadas
		if ($questions) {
			foreach ($questions as $ordem =>$questao) {
				if ($insertedQuestions) {
					foreach ($insertedQuestions as $questaoInserida) {
						if ($questaoInserida->question_id == $questao->id) {
							unset($questions[$ordem]);
						}
					}
				}
			}
		}

		//echo "<pre>";print_r($questions);exit;

		return View::make('evaluation.questionadd', compact('insertedQuestions','arrayEnumTipo', 'arrayEnumObrig','menuAtivo','questions', 'evaluation', 'cssPagina', 'jsPagina','tituloPagina'));
	}

	function insertquestion($idEvaluation){

		$dados = Input::all();

		$nextOrder = QuestionEvaluation::where('evaluation_id', $idEvaluation)->get();

		$questionEvaluation = new QuestionEvaluation;
		$questionEvaluation->question_id = $dados['id'];
		$questionEvaluation->evaluation_id = $idEvaluation;
		$questionEvaluation->order = count($nextOrder) + 1;
		$questionEvaluation->rating = $dados['rating'];

		$questionEvaluation->save();

		#retorna para listagem com mensagem
		$mensagem['tipo'] = "success";
		$mensagem['texto'] = '<span class="glyphicon glyphicon-ok-circle" aria-hidden="true"></span>&nbsp;&nbsp;Pergunta adicionada com sucesso!';

		Session::put('alert', $mensagem);
		return Redirect::to('evaluation/questionadd/'.$idEvaluation);

	}

	function cadastrar(){

	    $rules = array( 'title' => 'required',
	    				'expansion_plan_id' => 'required');

	    $validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {
			return Redirect::to('evaluation/list')->withErrors($validator);
		}

		$dados = Input::all();
		//echo "<pre>";print_r($dados);exit;

		$evaluationAdd = new Evaluation;
		$evaluationAdd->company_id = Auth::user()->company_id;
		$evaluationAdd->expansion_plan_id = $dados['expansion_plan_id'];
		$evaluationAdd->token = md5(uniqid().Auth::user()->company_id.uniqid(rand(), true));
		$evaluationAdd->title = $dados['title'];
		$evaluationAdd->description = $dados['description'];
		$evaluationAdd->min_note = $dados['ranking'];
		$evaluationAdd->save();

		return Redirect::to('evaluation/questionadd/'.$evaluationAdd->id);

	}

}