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
							<h3 class="panel-title text-center"><strong>Planos de Expansão</strong></h3>
						</div>

						<div class="panel-body">
							{{Form::open(array('url' => 'expansionplan/action', 'method' => 'post', 'role'=>'form', 'autocomplete' => 'off'))}}
								<input id="id_pe" type="hidden" name="id" value="0">
								<div class="col-lg-4">
									<div class="form-group">
										<label>Título</label>
										<input id="title" type="text" name="title" class="form-control" maxlength="45" required="">
									</div>
								</div>
								<div class="col-lg-3">
									<div class="form-group">
										<label>Data Inicial</label>
										<input id="start_date" type="date" name="start_date" class="form-control" required="">
									</div>
								</div>
								<div class="col-lg-3">
									<div class="form-group">
										<label>Data Final</label>
										<input id="end_date" type="date" name="end_date" class="form-control" required="">
									</div>
								</div>
								<div class="col-lg-2">
									<div class="form-group">
										<label>Meta <small>(unidades)</small></label>
										<input id="general_goal_units" type="number" name="general_goal_units" class="form-control">
									</div>
								</div>
								<div class="col-lg-5">
									<label>Formato</label>
									<br/>
									<label class="checkbox-inline">
										<input type="checkbox" name="format[loja]" value="1"> Loja
									</label>
									<label class="checkbox-inline">
										<input type="checkbox" name="format[micro]" value="2"> Micro
									</label>
									<label class="checkbox-inline">
										<input type="checkbox" name="format[sala]" value="3"> Sala
									</label>
									<label class="checkbox-inline">
										<input type="checkbox" name="format[quiosque]" value="4"> Quiosque
									</label>
									<label class="checkbox-inline">
										<input type="checkbox" name="format[movel]" value="5"> Móvel
									</label>
								</div>
								<div class="col-lg-12 col-sm-12">
									<button id="btn-adicionar" type="submit" class="btn btn-primary pull-right">
										<span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
										&nbsp;&nbsp;Cadastrar
									</button>

									<!--div id="grp-btn-edicao" class="btn-group pull-right" style="display:none;" role="group">
										<button type="submit" class="btn btn-warning">
											<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
											&nbsp;&nbsp;Salvar
										</button>
										<button id="btn-cancelar-edicao" type="reset" class="btn btn-info">
											<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
											&nbsp;&nbsp;Cancelar
										</button>
									</div-->

								</div>
							{{Form::close()}}
						</div>

						<!-- Table -->
						<table class="table table-bordered table-hover table-condensed table-hovered table-striped">
							<thead>
								<tr>
									<td class="col-md-4"><strong>Título</strong></td>
									<td class="col-md-2 text-center"><strong>Data Inicial</strong></td>
									<td class="col-md-2 text-center"><strong>Data Final</strong></td>
									<td class="col-md-1 text-center"><strong>Meta</strong></td>
									<!--td class="col-md-3 text-center"><strong>{{Lang::get('textos.tit_acao')}}</strong></td-->
								</tr>
							</thead>
							<tbody>
								@forelse($expansionPlans as $expansionPlan)
							    	<tr>
										<td><p class="title-{{$expansionPlan->id}}">{{$expansionPlan->title}}</p></td>
										<td class="text-center"><p class="startdate-{{$expansionPlan->id}}" originalval="{{$expansionPlan->start_date}}">{{implode('/', array_reverse(explode('-', $expansionPlan->start_date)))}}</p></td>
										<td class="text-center"><p class="enddate-{{$expansionPlan->id}}" originalval="{{$expansionPlan->end_date}}">{{implode('/', array_reverse(explode('-', $expansionPlan->end_date)))}}</p></td>
										<td class="text-center"><p class="goal-{{$expansionPlan->id}}">{{$expansionPlan->general_goal_units}}</p></td>
										<!--td class="text-center">
											<div class="btn-group" role="group" aria-label="...">
												<a href="#" class="btn btn-warning btn-editar" pk="{{$expansionPlan->id}}" role="button" title="{{Lang::get('textos.tit_editar')}} {{ $expansionPlan->title }}">
													<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
													&nbsp;&nbsp;{{Lang::get('textos.tit_editar')}}
												</a>
												<a href="#" class="btn btn-danger btn-apagar" pk="{{$expansionPlan->id}}" role="button" title="{{Lang::get('textos.tit_apagar')}} {{ $expansionPlan->title }}">
													<span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
													&nbsp;&nbsp;{{Lang::get('textos.tit_apagar')}}
													</a>
												</a>
											</div>
										</td-->
							    	</tr>
								@empty
								<tr>
									<td colspan="5">Nenhum Plano de Expansão Cadastrado.</td>
								</tr>
								@endforelse
							</tbody>
						</table>
					</div>

				</div><!--ROW-->
			</div> <!-- CONTAINER -->
		</div> <!-- CONTEUDO -->
	</div><!-- affix-row [pre menu]-->

	{{--FORMULÁRIO DE REMOÇÃO--}}
	{{Form::open(array('url' => 'expansionplan/delete', 'id' => 'remove-form', 'method' => 'post', 'role'=>'form'))}}
		<input id="pkdelplan" type="hidden" name="id" value="">
	{{Form::close()}}

@endsection