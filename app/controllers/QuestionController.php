<?php

class QuestionController extends BaseController {

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
									'n' => 'Investimento');

	private $arrayEnumObrig = array('n' => 'Não', 
									's' => 'Sim');

	function listar(){

		$menuAtivo = 3;
		$cssPagina = '';
		$jsPagina = 'js/question/list.js';
		$tituloPagina = 'Questões';
		$questions = Company::find(Auth::user()->company_id)->questions;
		$arrayEnumTipo = $this->arrayEnumTipo;
		$arrayEnumObrig = $this->arrayEnumObrig;

		return View::make('question.template', compact('menuAtivo','questions', 'arrayEnumTipo', 'arrayEnumObrig', 'cssPagina', 'jsPagina','tituloPagina'));
		
	}

	function adicionar(){

	    $rules = array( 'text' => 'required',
				        'type' => 'required',
				        'mandatory' => 'required'
		    			);

	    $validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {
			return Redirect::to('question/list')->withErrors($validator);
		}

		$dados = Input::all();

		if ($dados['type'] != 'c' && $dados['type'] != 'd') {

			if ($this->insertQuestion($dados)) {
				#retorna para listagem com mensagem
				$mensagem['tipo'] = "success";
				$mensagem['texto'] = '<span class="glyphicon glyphicon-ok-circle" aria-hidden="true"></span>&nbsp;&nbsp;Pergunta cadastrada!';

				Session::put('alert', $mensagem);
				return Redirect::to('question/list');
			}

		}else{	
			$arrayOpcoes = $this->montaArrayOpcoes($dados['option']['text'], $dados['option']['rating']);
			if ($arrayOpcoes) {

				$questionId = $this->insertQuestion($dados);

				if ($questionId) {

					foreach ($arrayOpcoes as $valOpcao) {
						$option = new Option();
						$option->question_id = $questionId;
						$option->order = $valOpcao['order'];
						$option->text = $valOpcao['text'];
						$option->rating = $valOpcao['rating'];
						$option->save();
					}

					#retorna para listagem com mensagem
					$mensagem['tipo'] = "success";
					$mensagem['texto'] = '<span class="glyphicon glyphicon-ok-circle" aria-hidden="true"></span>&nbsp;&nbsp;Pergunta: foi cadastrada!"';

					Session::put('alert', $mensagem);
					return Redirect::to('question/list');
				}

			}
		}

	}

	private function montaArrayOpcoes($arrayText, $arrayRating){

		if (is_array($arrayText) && 
			is_array($arrayRating) && 
			count($arrayText) > 0 &&
			count($arrayRating) > 0 ) {
				$arrayMontado = array();
				$count = 1;
				foreach ($arrayText as $texto) {
					$arrayMontado[$count]['order'] = $count;
					$arrayMontado[$count]['text'] = $texto;
					$count++;
				}
				$count = 1;
				foreach ($arrayRating as $rating) {
					$arrayMontado[$count]['rating'] = $rating;
					$count++;
				}
		}else{
			return false;
		}

		return $arrayMontado;
	}

	private function insertQuestion($questionData){
		if (is_array($questionData)) {
			$question = New Question();
			$question->text = $questionData['text'];
			$question->type = $questionData['type'];
			$question->company_id = Auth::user()->company_id;
			$question->mandatory = $questionData['mandatory'];

			if ($question->save()) {
				return $question->id;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}

}
