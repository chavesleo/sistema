<?php

class EvaluationController extends BaseController {

	private $arrayEnumTipo = array(	'a' => 'Texto Curto', 
									'b' => 'Texto Longo',
									'c' => 'Seleção Única',
									'd' => 'Seleção Múltipla',
									'e' => 'Telefone',
									'f' => 'Data',
									'g' => 'CPF',
									'h' => 'CNPJ');

	private $arrayEnumObrig = array('n' => 'Não', 
									's' => 'Sim');

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

		//echo "<pre>";print_r($data);exit;

	    $rules = array( 'title' => 'required');

	    $validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {
			return Redirect::to('evaluation/list')->withErrors($validator);
		}

		$dados = Input::all();

		$evaluation = new Evaluation;
		$evaluation->title = $dados['title'];
		$evaluation->company_id = Auth::user()->company_id;
		$evaluation->description = $dados['description'];
		$evaluation->save();

		return Redirect::to('evaluation/questionadd/'.$evaluation->id);

	}

	function questionadd($idEvaluation){
		$menuAtivo = 2;
		$cssPagina = '';
		$jsPagina = 'js/evaluation/list.js';
		$tituloPagina = 'Formulários';
		$arrayEnumTipo = $this->arrayEnumTipo;
		$arrayEnumObrig = $this->arrayEnumObrig;
		$evaluation = Evaluation::find($idEvaluation);
		$questions = Question::where('company_id', Auth::user()->company_id)
								->with('options')
								->get();

		$insertedQuestions = QuestionEvaluation::where('evaluation_id', $idEvaluation)
												->with('question.options')
												->with('evaluation')
												->get();

		//echo "<pre>";print_r($insertedQuestions);exit;

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

}