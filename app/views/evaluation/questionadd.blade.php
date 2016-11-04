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
							<div class="col-md-10">
								<h4>{{$evaluation->title}} <small>{{$evaluation->description}}</small></h4>
							</div>
							<div class="col-md-2">
								<button type="button" class="btn btn-primary pull-right" title="{{Lang::get('textos.tit_adicionar')}}" data-toggle="modal" data-target="#modalPerguntas">
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
								@forelse($questions as $question)
							    	<tr>
							    		<td class="text-center" ><p>{{$question->id}}</p></td>
										<td><p>{{$question->text}}</p></td>
										<td><p>{{$arrayEnumTipo[$question->type]}}</p></td>
										<td class="text-center"><p>{{$arrayEnumObrig[$question->mandatory]}}</p></td>
										<td>
											@forelse($question->options as $option)
										    	{{$option->text}}<br/>
											@empty
												---
											@endforelse
										</td>
										<td class="text-center" ><p>Peso</p></td>
										<td>
											<button type="button" class="btn btn-danger pull-right action-usr" pk="1" title="{{Lang::get('textos.tit_adicionar')}}" data-toggle="modal" data-target="#modalNewEva">
												<span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
											</button>
										</td>
							    	</tr>
								@empty
								<tr>
									<td colspan="5">Nenhuma pergunta vinculada.</td>
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
											<button type="button" class="btn btn-warning pull-right action-usr" pk="1" title="{{Lang::get('textos.tit_adicionar')}}" data-toggle="modal" data-target="#modalNewEva">
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

@endsection