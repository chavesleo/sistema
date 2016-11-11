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
								<div class="col-lg-6">
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
								<div class="col-lg-6">
									<button class="btn btn-block btn-warning" type="button" data-toggle="modal" data-target="#modalCidadeInteresse">
									  Cidades de Interesse Selecionadas <span class="badge badge-cidades-selecionadas">0</span>
									</button>
								</div>	
								<div class="col-lg-6">
									<button id="btn-adicionar" type="submit" class="btn btn-primary pull-right">
										<span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
										&nbsp;&nbsp;Cadastrar
									</button>
								</div>

								<div class="modal fade in" id="modalCidadeInteresse" tabindex="-1" role="dialog" aria-labelledby="modalCidadeInteresse">
									<div class="modal-dialog modal-lg" role="document">
										<div class="modal-content">
											<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
												<h4 class="modal-title text-center">Cidades de Interesse</h4>
											</div>
											<div class="modal-body">
												<div class="row">
													<div class="col-lg-6">
														<div class="form-group">
					    									<label>UF:</label>
															<select id="comboUf" class="form-control">
																<option value="">Selecione</option>
															@forelse($listaUf as $dadosUf)
														    	<option value="{{$dadosUf->id}}" sigla="{{$dadosUf->short_name}}">{{$dadosUf->name}}</option>
															@empty
																<option value="">Nenhuma UF encontrada.</option>
															@endforelse
															</select>
														</div>
													</div>
													<div class="col-lg-6">
														<div class="form-group">
					    									<label>Cidade:</label>
															<select id="comboCidades" class="form-control">
																<option value="">Selecione uma UF</option>
															</select>
														</div>
													</div>
													<div class="col-lg-3">
														<div class="form-group">
					    									<label>Formato:</label>
															<select id="comboFormato" class="form-control">
																<option value="">Selecione</option>
																<option value="loja">Loja</option>
																<option value="quiosque">Quiosque</option>
																<option value="sala">Sala Comercial</option>
																<option value="micro">Micro Franquia</option>
																<option value="movel">Móvel</option>
															</select>
														</div>
													</div>
													<div class="col-lg-3">
				    									<label>Meta:</label>
														<div class="form-group">
															<div class="input-group">
																<input id="inputmeta" type="number" maxlength="3" class="text-center form-control"  min="1" max="100">
																<div class="input-group-addon"><small>Unidades</small></div>
															</div>
														</div>
													</div>
													<div class="col-lg-3">
														<div class="form-group">
					    									<label>Investimento <small>( Unitário )</small>:</label>
															<div class="input-group">
																<div class="input-group-addon">R$</div>
																<input id="inputinvest" type="text" class="form-control text-right">
																<div class="input-group-addon">,00</div>
															</div>
														</div>
													</div>
													<div class="col-lg-2 col-lg-offset-1">
														<button id="btn-adicionar-cidade" style="margin-top: 24px; margin-bottom: 10px; " type="button" class="btn btn-block btn-warning" title="Adicionar">
															<span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
															&nbsp;&nbsp;Adicionar
														</button>
													</div>
												</div><!-- row 1 -->

												<table id="tbCidades" class="table table-condensed table-striped table-bordered">
													<thead>
														<tr>
															<th class="text-center">Cidade / UF</th>
															<th class="text-center">Formato</th>
															<th class="text-center" style="width: 20%;">Meta (unidades)</th>
															<th class="text-center" style="width: 20%;">Investimento</th>
															<th class="text-center" style="width: 5%;">Ação</th>
														</tr>
													</thead>
													<tbody>
														
													</tbody>
												</table>

											</div>
											<div class="modal-footer">
												<button type="button" class="btn btn-info" data-dismiss="modal">
													<span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span>
													&nbsp;Salvar
												</button>
											</div>
										</div>
									</div>
								</div>

							{{Form::close()}}
						</div>
						<hr>
						<!-- Table -->
						<table class="table table-bordered table-hover table-condensed table-hovered table-striped">
							<thead>
								<tr>
									<td class="col-md-6"><strong>Título</strong></td>
									<td class="col-md-2 text-center"><strong>Cidades de Interesse</strong></td>
									<td class="col-md-2 text-center"><strong>Data Inicial</strong></td>
									<td class="col-md-2 text-center"><strong>Data Final</strong></td>
								</tr>
							</thead>
							<tbody>
								@forelse($expansionPlans as $expansionPlan)
							    	<tr>
										<td><p class="title-{{$expansionPlan->id}}">{{$expansionPlan->title}}</p></td>
										<td class="text-center" data-toggle="tooltip" title="@foreach($expansionPlan->expansionPlanCities as $citiedata) {{$arrCities[$citiedata['city_id']]->name}} - @endforeach">{{count($expansionPlan->expansionPlanCities)}}</td>
										<td class="text-center"><p class="startdate-{{$expansionPlan->id}}" originalval="{{$expansionPlan->start_date}}">{{implode('/', array_reverse(explode('-', $expansionPlan->start_date)))}}</p></td>
										<td class="text-center"><p class="enddate-{{$expansionPlan->id}}" originalval="{{$expansionPlan->end_date}}">{{implode('/', array_reverse(explode('-', $expansionPlan->end_date)))}}</p></td>
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

	<input type="hidden" id="defaultRoute" value="{{URL::to('ajax/listCitiesByStateId')}}">
@endsection