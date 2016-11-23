<?php

######################
# MAIN PAGE
######################
Route::get('/', array('before' => 'auth', function(){
	return Redirect::to('evaluation/list');
}));

######################
# User
######################
Route::group(array('prefix' => 'user','before' => 'auth'), function(){

	#rota padrão envia para listagem
	Route::get('/', function(){ return Redirect::to('user/list'); });

	#listagem
	Route::get('list', 'UserController@listar');

	#Remoção
	Route::post('delete', 'UserController@delete');
	Route::get('delete', function(){return Redirect::to('user/list');});

	#Edição
	Route::post('action', 'UserController@action');
	Route::get('action', function(){return Redirect::to('user/list');});

	#Troca Senha
	Route::post('changepass', 'UserController@changepass');
	Route::get('changepass', function(){return Redirect::to('/');});	

});

######################
# Expansion Plan
######################
Route::group(array('prefix' => 'expansionplan', 'before' => 'auth'), function(){

	#rota padrão envia para listagem
	Route::get('/', function(){ return Redirect::to('expansionplan/list'); });

	#listagem
	Route::get('list', 'ExpansionPlanController@listar');

	#Edição
	Route::post('action', 'ExpansionPlanController@action');
	Route::get('action', function(){return Redirect::to('expansionplan/list');});

	#Remoção
	Route::post('delete', 'ExpansionPlanController@delete');
	Route::get('delete', function(){return Redirect::to('expansionplan/list');});

});

######################
# Evaluation
######################
Route::group(array('prefix' => 'evaluation', 'before' => 'auth'), function(){

	#rota padrão envia para listagem
	Route::get('/', function(){ return Redirect::to('evaluation/list'); });

	#listagem
	Route::get('list', 'EvaluationController@listar');

	#Criação
	Route::post('add', 'EvaluationController@cadastrar');

	#Edição
	Route::get('questionadd/{id}', 'EvaluationController@questionadd');

	#Adição Perguntas
	Route::post('questionadd/{id}', 'EvaluationController@insertquestion');

	#Remoção
	Route::post('delete', 'EvaluationController@delete');
	Route::get('delete', function(){return Redirect::to('evaluation/list');});

});

######################
# Question
######################
Route::group(array('prefix' => 'question', 'before' => 'auth'), function(){

	#rota padrão envia para listagem
	Route::get('/', function(){ return Redirect::to('question/list'); });

	#listagem
	Route::get('list', 'QuestionController@listar');

	#Edição
	Route::post('add', 'QuestionController@adicionar');
	Route::get('add', function(){return Redirect::to('question/list');});

});

######################
# PROCCESS / PREENCHIMENTO QUESTIONARIO
######################
Route::group(array('prefix' => 'proccess'), function(){

	Route::get('invalid', function(){exit('Questionário não encontrado!');});

	#Adição Perguntas
	Route::post('finish', 'ProccessController@finish');
	
	Route::get('{token}', 'ProccessController@index');

	Route::post('{token}', 'ProccessController@startproccess');

});

######################
# Company
######################
Route::group(array('prefix' => 'empresa'), function(){
	Route::post('cadastrar', 'CompanyController@cadastrar');
});

######################
# AJAX
######################
Route::group(array('prefix' => 'ajax'), function(){

	#lista as cidades para o combo
	Route::get('listCitiesByStateId/{stateId}', 'CityController@ajaxListByStateId');

	#salva resposta
	Route::post('svquestion', 'ProccessController@ajaxSaveQuestion');

	#atualiza a barra de prograsso
	Route::get('atualizaprogresso/{proccessId}/{ajax}', 'ProccessController@calculateProgressById');

});

######################
# Relatórios
######################
Route::group(array('prefix' => 'report', 'before' => 'auth'), function(){

	Route::get('/', 'ReportController@index');

	Route::group(array('prefix' => 'proccessdetails'), function(){

		Route::get('{id}', 'ReportController@showProccessDetailById');

	});
});

#################
# Login e Logout
#################
Route::post('login', 'UserController@login');	
Route::get('login', function(){
	$arraySegmentos = Segment::all();
	return (Auth::check()) ? Redirect::intended('/') : View::make('login.template', compact('arraySegmentos'));
});
Route::get('logout', function(){
	Auth::logout();
	return Redirect::to('login');
});


/* PERFUMARIA

- por botoes e percentual no menu ao responder questionario
- Criação de pergunta Intervalo de números com opção e peso
- Quando for Combo de investimento com investimento do plano de expansão


QUESTIONS TIPO: 
- FAIXA DE NUMERO

* 1 - Cadastro de Empresas - 			OK
* 2 - Cadastro de Usuários - 			OK
* 3 - Cadastro de Formulários - 		OK
* 4 - Cadastro de Perguntas - 			OK ---- faixa de investimento
* 5 - Cadastro de Plano de Expansão - 	OK
* 6 - Relatórios
* 7 - Responder Formulários 			OK
* 8 - Exportar
	8.1 - Candidato
		8.1.1 - CSV
		8.1.2 - PDF
		8.1.3 - JSON
	8.2 - Formulários
		8.2.3 - Formulários com respostas de todos os candidatos em CSV ou JSON

* 9 - Analisar processos e sugerir:
	- Perfil do Candidato
	- Cidades dentro do Raio
	- Formato de franquia em cidade do Raio com investimento <=
	- Análise de respostas de processos com perguntas em comum

*/

/*  ZERAR TABELAS
SET FOREIGN_KEY_CHECKS = 0; 
TRUNCATE `candidate`;
TRUNCATE `city_interest`;
TRUNCATE `company`;
TRUNCATE `evaluation`;
TRUNCATE `expansion_plan`;
TRUNCATE `expansion_plan_city`;
TRUNCATE `option`;
TRUNCATE `proccess`;
TRUNCATE `proccess_answer`;
TRUNCATE `question`;
TRUNCATE `question_evaluation`;
TRUNCATE `user`;
SET FOREIGN_KEY_CHECKS = 1;


- Novos Candidatos por Mês
- Resultados de Processos por status
- Quantidade de Formulários Respondidos
- Metas Plano de Expansão x Atingidas

==========================================

- Não Concluídos (Inacabados)
- Aprovados
- Reprovados
- Em Análise (características próximas)

==========================================
Problemas

1 - Candidatos aprovados fora da cidade de interesse.
2 - Muitas Cidades dentro do raio.
3 - Independente da nota, se está no raio da cidade marca como "Em análise"


==========================================
Cenário:

Planos de Expansão
	1 -  Metropolitana
		- Porto Alegre (10) - Canoas (5) - Gravataí (5)
		- Loja: R$ 380.000,00 (2)(1)(1)
		- Quioque: R$ 118.000,00 (2)(1)(1)

	2 - Interior
		- Sta Cruz (50)(1) - Sta Maria (50)(2) - Caxias (40)(2) - Pelotas (40)(1) - Passo Fundo (50)(1)
		- Loja: R$ 195.000,00 (1)(1)(1)(1)
		- Móvel: R$ 95.000,00 (1)(1)(1)(1)

2 Formulários (Alimentação)
	- 27 Questões
	- 29 Questões

20 Candidatos
	- 11 Em cidades de interesse 
		- 5 Com valores abaixo
		- 6 Abaixo do percentual
	- 9 Em Outras Cidades
		- 6 Dentro do raio 
		- 3 Com Investimento



*/