<?php

/*
* 1 - Cadastro de Empresas - OK
* 2 - Cadastro de Usuários - OK
* 3 - Cadastro de Formulários
* 4 - Cadastro de Perguntas - OK
* 5 - Cadastro de Plano de Expansão - OK
* 6 - Relatórios
* 7 - Responder Fomulários
* 8 - Web Services Exportar
* 9 - Análisar Candidatos / Sugerir
*/

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
Route::group(array('prefix' => 'evaluation'), function(){

	#rota padrão envia para listagem
	Route::get('/', function(){ return Redirect::to('evaluation/list'); });

	#listagem
	Route::get('list', 'EvaluationController@listar');

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
Route::group(array('prefix' => 'question'), function(){

	#rota padrão envia para listagem
	Route::get('/', function(){ return Redirect::to('question/list'); });

	#listagem
	Route::get('list', 'QuestionController@listar');

	#Edição
	Route::post('add', 'QuestionController@adicionar');
	Route::get('add', function(){return Redirect::to('question/list');});

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