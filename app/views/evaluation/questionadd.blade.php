@extends('layouts.default')

@section('conteudo')

	<div class="col-sm-9 col-md-10 affix-content">

		<div class="container">

			<div class="row"> &nbsp; </div>

			{{-- AVISOS DE ALERTA DA SESSAO--}}
			@if (Session::has('alert'))
				<div class="row">
					<div class="alert alert-{{Session::get('alert.tipo')}} alert-dismissible" role="alert">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
						<p>{{Session::get('alert.texto')}}</p>
					</div>
					{{Session::forget('alert')}}
				</div>
			@endif
			@if( count($errors) > 0)
				@foreach ($errors->all() as $msg)
				<div class="row">
					<div class="alert alert-warning alert-dismissible" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
						<p>
							<span class="glyphicon glyphicon-warning-sign" aria-hidden="true"></span>
							&nbsp;&nbsp;&nbsp;{{$msg}}
						</p>
					</div>
				</div>
				@endforeach
			@endif

			<div class="row">

				<div class="panel panel-default">

					<div class="panel-heading">
						<div class="row">
							<div class="col-md-12">
								<h4>{{$evaluation->title}} <small>{{$evaluation->description}}</small></h4>
							</div>
							<div class="col-md-10">
								<div class="form-group">
									<div class="input-group">
										<div class="input-group-addon"><span class="glyphicon glyphicon-link" aria-hidden="true"></span>&nbsp;Link</div>
										<input type="text" readonly="" class="form-control" id="exampleInputAmount" value="{{URL::to('proccess/')}}/{{$evaluation->token}}">
									</div>
								</div>
							</div>
							<div class="col-md-2">
								<button type="button" class="btn btn-block btn-primary pull-right" title="{{Lang::get('textos.tit_adicionar')}}" data-toggle="modal" data-target="#modalPerguntas">
									<span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
									&nbsp;&nbsp;Pergunta
								</button>
							</div>
						</div>
					</div>
					<div class="panel-body">
						<table class="table table-condensed table-striped table-bordered table-hover">
							<thead>
								<tr>
									<th class="text-center" >Ordem</th>
									<th>Texto</th>
									<th class="text-center" style="width: 17%;">Tipo</th>
									<th class="text-center" style="width: 8%;">Obrig.</th>
									<th class="text-center" style="width: 20%;">Opções</th>
									<th class="text-center" style="width: 7%;">Peso</th>
									<th class="text-center" style="width: 5%;">Ação</th>
								</tr>
							</thead>
							<tbody>
								@forelse($insertedQuestions as $questionEvaluation)
							    	<tr>
							    		<td class="text-center" ><p>{{$questionEvaluation->order}}</p></td>
										<td><p>{{$questionEvaluation->question->text}}</p></td>
										<td><p>{{$arrayEnumTipo[$questionEvaluation->question->type]}}</p></td>
										<td class="text-center"><p>{{$arrayEnumObrig[$questionEvaluation->question->mandatory]}}</p></td>
										<td>
											@forelse($questionEvaluation->question->options as $option)
										    	{{$option->text}}<br/>
											@empty
												---
											@endforelse
										</td>
										<td class="text-center" ><p>{{$questionEvaluation->rating}}</p></td>
										<td>
											<button type="button" class="btn btn-sm btn-danger pull-right action-usr" pk="1" title="{{Lang::get('textos.tit_adicionar')}}" data-toggle="modal" data-target="#modalNewEva">
												<span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
											</button>
										</td>
							    	</tr>
								@empty
								<tr>
									<td colspan="7">Nenhuma pergunta vinculada.</td>
								</tr>
								@endforelse
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade in" id="modalPerguntas" tabindex="-1" role="dialog" aria-labelledby="modalPerguntas">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
					<h4 class="modal-title text-center">Adicionar Pergunta</h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<table class="table table-condensed table-striped table-bordered table-hover">
							<thead>
								<tr>
									<th>Texto</th>
									<th class="text-center" style="width: 9%;">Peso</th>
									<th class="text-center" style="width: 17%;">Tipo</th>
									<th class="text-center" style="width: 8%;">Obrig.</th>
									<th class="text-center" style="width: 20%;">Opções</th>
									<th class="text-center" style="width: 5%;">Ação</th>
								</tr>
							</thead>
							<tbody>
								@forelse($questions as $question)
							    	<tr>
										<td><p>{{$question->text}}</p></td>
										<td>
											<div class="form-group">
												<input type="text" @if( in_array($question->type, array('a','b','c','d','f','g','h','i','j','k','m','o'))) disabled @endif @if( in_array($question->type, array('c','d'))) title="Os pesos já foram definidos nas opções" @endif class="form-control input-sm text-center rating-input" maxlength="5" id="rating-{{$question->id}}">
											</div>
										</td>
										<td><p>{{$arrayEnumTipo[$question->type]}}</p></td>
										<td class="text-center"><p>{{$arrayEnumObrig[$question->mandatory]}}</p></td>
										<td>
											@forelse($question->options as $option)
										    	{{$option->text}}<br/>
											@empty
												---
											@endforelse
										</td>
										<td>
											<button type="button" class="btn btn-sm btn-warning pull-right btnaddquestion" pk="{{$question->id}}" title="{{Lang::get('textos.tit_adicionar')}}">
												<span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
											</button>
										</td>
							    	</tr>
								@empty
								<tr>
									<td colspan="5">Nenhuma pergunta cadastrada.</td>
								</tr>
								@endforelse
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>

	{{--FORMULÁRIO DE INSERÇÃO--}}
	{{Form::open(array('url' => URL::current(), 'id' => 'addqform', 'method' => 'post', 'role'=>'form'))}}
		<input id="pkquestion" type="hidden" name="id" value="">
		<input id="rating" type="hidden" name="rating" value="">
	{{Form::close()}}

@endsection