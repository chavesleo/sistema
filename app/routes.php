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

	Route::get('listCitiesByStateId/{stateId}', 'CityController@ajaxListByStateId');

	Route::post('svquestion', 'ProccessController@ajaxSaveQuestion');

});

######################
# Relatórios
######################
Route::group(array('prefix' => 'report', 'before' => 'auth'), function(){

	Route::get('/', 'ReportController@index');

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

- AJAX Exibir percentual respondido e Salvar na tabela a porcentagem
- Exportar ao concluir
- Criação de pergunta Intervalo de números com opção e peso
- Quando for Combo de investimento com investimento do plano de expansão


QUESTIONS TIPO: 
- FAIXA DE NUMERO

* 1 - Cadastro de Empresas - 			OK
* 2 - Cadastro de Usuários - 			OK
* 3 - Cadastro de Perguntas - 			OK
* 4 - Cadastro de Plano de Expansão - 	OK
* 5 - Cadastro de Formulários - 		OK
* 6 - Responder Formulários 			OK
* 7 - Exportar
	7.1 - Candidato
		7.1.1 - CSV
		7.1.2 - PDF
		7.1.3 - JSON
	7.2 - Formulários
		7.2.3 - Formulários com respostas de todos os candidatos em CSV ou JSON

* 8 - Relatórios
* 9 - Analisar processos e sugerir:
	- Perfil do Candidato
	- Cidades dentro do Raio
	- Tipos de Franquias de Valor mais Acessível
	- Novos Processos com base em respostas de processos antigos

*/

/*
SET FOREIGN_KEY_CHECKS = 0; 
TRUNCATE `nome_da_tabela`; 
SET FOREIGN_KEY_CHECKS = 1;
*/