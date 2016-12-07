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

	Route::get('export/json/{id}', 'ProccessController@exportJson');

	Route::get('export/pdf/{id}', 'ProccessController@exportPdf');

	Route::get('export/csv/{id}', 'ProccessController@exportCsv');

	Route::post('changestatus/{id}', 'ProccessController@changeForcedStatus');

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