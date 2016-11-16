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
									<h4>Formulários</h4>
								</div>
								<div class="col-md-2">
									<button type="button" class="btn btn-primary pull-right action-usr" pk="1" title="{{Lang::get('textos.tit_adicionar')}}" data-toggle="modal" data-target="#modalNewEva">
										<span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
										&nbsp;&nbsp;{{Lang::get('textos.tit_adicionar')}}
										</a>
									</button>
								</div>
							</div>
						</div>
						<div class="table-responsive">
							<table id="evaluation-list" class="table table-bordered table-hover table-condensed table-hovered table-striped">
								<thead>
									<tr>
										<td class="col-md-1 text-center"><strong>{{Lang::get('textos.tit_cod')}}</strong></td>
										<td class="col-md-4"><strong>{{Lang::get('textos.tit_nome')}}</strong></td>
										<td class="col-md-4"><strong>Descrição</strong></td>
										<td class="col-md-2 text-center"><strong>{{Lang::get('textos.tit_acao')}}</strong></td>
									</tr>
								</thead>
								<tbody>
									@forelse($evaluations as $evaluation)
								    	<tr>
											<td class="text-center"><p>{{$evaluation->id}}</p></td>
											<td><p id="title-eva-{{$evaluation->id}}">{{$evaluation->title}}</p></td>
											<td><p>{{$evaluation->description}}</p></td>
											<td class="text-center">
												<div class="btn-group" role="group">
													<a href="questionadd/{{$evaluation->id}}" class="btn btn-sm btn-warning" role="button" title="{{Lang::get('textos.tit_editar')}} {{ $evaluation->title }}">
														<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>&nbsp;&nbsp;{{Lang::get('textos.tit_editar')}}
													</a>
													<button type="button" class="btn btn-sm btn-info" data-container="body" data-toggle="popover" data-placement="left" data-content="{{URL::to('proccess/')}}/{{$evaluation->token}}">
												  		<span class="glyphicon glyphicon-link" aria-hidden="true"></span>&nbsp;&nbsp;Link
													</button>
												</div>
											</td>
								    	</tr>
									@empty
										<tr>
											<td colspan="4">Nenhum formulário cadastrado.</td>
										</tr>
									@endforelse
								</tbody>
							</table>
						</div><!-- table-responsive -->
					</div><!-- panel -->
				</div><!-- END ROW-->
			</div> <!-- CONTAINER -->
		</div> <!-- CONTEUDO -->
	</div><!-- affix-row [pre menu]-->

	<div class="modal fade" id="modalNewEva" tabindex="-1" role="dialog" aria-labelledby="modalNewEva">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title text-center">Cadastrar Novo Formulário</h4>
				</div>
				<div class="modal-body">
					{{Form::open(array('url' => 'evaluation/add', 'id' => 'new-evaluation-form', 'method' => 'post', 'role'=>'form', 'autocomplete' => 'off'))}}
						<div class="row">
							<div class="col-lg-12">
								<div class="form-group">
									<label class="control-label">Nome:</label>
									<input type="text" name="title" class="form-control" required="" maxlength="45">
								</div>
							</div>
							<div class="col-lg-12">
								<div class="form-group">
									<label class="control-label">Descrição:</label>
									<input type="text" name="description" class="form-control" maxlength="250">
								</div>
							</div>
							<div class="col-lg-8">
								<div class="form-group">
									<label class="control-label">Plano de expansão:</label>
									<select name="expansion_plan_id" class="form-control">
										<option value="">Selecione</option>
									@forelse($expansionPlans as $expansionPlan)
								    	<option value="{{$expansionPlan->id}}">{{$expansionPlan->title}}</option>
									@empty
										<option value="">É preciso cadastrar um plano de expansão!</option>
									@endforelse
									</select>
								</div>
							</div>
							<div class="col-lg-4">
								<div class="form-group">
									<label class="control-label">Nota Mínima:</label>
									<input type="number" name="ranking" class="form-control text-center" required="" maxlength="3" min="0" max="100" step="0.01">
								</div>
							</div>
						</div>
							
					{{Form::close()}}
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-success" form="new-evaluation-form">
						<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
						&nbsp;Salvar
					</button>
					<button type="button" class="btn btn-info" data-dismiss="modal">
						<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
						&nbsp;Fechar
					</button>
				</div>
			</div>
		</div>
	</div><!-- MODAL -->

	{{--FORMULÁRIO DE REMOÇÃO--}}
	{{Form::open(array('url' => 'evaluation/delete', 'id' => 'remove-form', 'method' => 'post', 'role'=>'form'))}}
		<input id="pkdelevaluation" type="hidden" name="id" value="">
	{{Form::close()}}

	<style>
		.popover{
			max-width: none !important;
		}
	</style>

@endsection