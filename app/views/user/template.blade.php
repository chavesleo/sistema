@extends('layouts.default')

@section('conteudo')

		<div class="col-sm-9 col-md-10 affix-content">

			<div class="container">

				<div class="row"> &nbsp; </div>

				{{-- AVISOS DE ALERTA DA SESSAO--}}
				@if (Session::has('alert'))
					<div class="row">
						<div class="alert alert-{{Session::get('alert.tipo')}} alert-dismissible" role="alert">
							<button type="button" class="close" data-dismiss="alert" aria-label="Close">
						  		<span aria-hidden="true">&times;</span>
							</button>
							<p>{{Session::get('alert.texto')}}</p>
						</div>
						{{Session::forget('alert')}}
					</div>
				@endif

				<div class="row">
					
					<div class="panel panel-default">

						<div class="panel-heading">
							<div class="row">
								<div class="col-md-10">
									<h4>{{Lang::get('textos.tit_usuarios')}}</h4>
								</div>
								<div class="col-md-2">
									<button type="button" class="btn btn-primary pull-right action-usr" pk="1" title="{{Lang::get('textos.tit_adicionar')}}" data-toggle="modal" data-target="#modalActionUsr">
										<span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
										&nbsp;&nbsp;{{Lang::get('textos.tit_adicionar')}}
										</a>
									</button>
								</div>
							</div>
						</div>
						<div class="table-responsive">
							<table id="user-list" class="table table-bordered table-hover table-condensed table-hovered table-striped">
								<thead>
									<tr>
										<td class="col-md-1 text-center"><strong>{{Lang::get('textos.tit_cod')}}</strong></td>
										<td class="col-md-8"><strong>{{Lang::get('textos.tit_nome')}}</strong></td>
										<td class="col-md-3 text-center"><strong>{{Lang::get('textos.tit_acao')}}</strong></td>
									</tr>
								</thead>
								<tbody>
									@forelse($users as $user)
								    	<tr>
											<td class="text-center"><p>{{$user->id}}</p></td>
											<td><p>{{$user->fullname}}</p></td>
											<td class="text-center">
												<div class="btn-group" role="group" aria-label="...">
													<a href="#" class="btn btn-warning action-usr" pk="2" pka="{{$user->id}}" pkb="{{$user->fullname}}" pkc="{{$user->email}}" pkd="{{$user->active}}" role="button" title="{{Lang::get('textos.tit_editar')}} {{ $user->fullname }}" data-toggle="modal" data-target="#modalActionUsr">
														<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
														&nbsp;&nbsp;{{Lang::get('textos.tit_editar')}}
													</a>
													<a href="#" class="btn btn-danger btn-apagar" pk="{{$user->id}}" usrname="{{ $user->fullname }}" role="button" title="{{Lang::get('textos.tit_apagar')}} {{ $user->fullname }}">
														<span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
														&nbsp;&nbsp;{{Lang::get('textos.tit_apagar')}}
														</a>
													</a>
												</div>
											</td>
								    	</tr>
									@empty
									<tr>
										<td colspan="3">{{Lang::get('textos.nenhum_usuario_cadastrado')}}.</td>
									</tr>
									@endforelse
								</tbody>
							</table>
						</div><!-- table-responsive -->
					</div><!-- panel -->
				</div><!--END ROW-->
			</div> <!-- END CONTAINER -->
		</div> <!-- CONTEUDO -->
	</div><!-- affix-row [pre menu]-->

	<div class="modal fade" id="modalActionUsr" tabindex="-1" role="dialog" aria-labelledby="modalActionUsr">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title text-center" id="modalActionUsrTitle"> </h4>
				</div>
				<div class="modal-body">
					{{Form::open(array('url' => 'user/action', 'id' => 'user-action-form', 'method' => 'post', 'role'=>'form', 'autocomplete' => 'off'))}}
						<input id="pkusr" name="id" type="hidden" value="0">
						<div class="form-group">
							<label for="recipient-name" class="control-label">Nome:</label>
							<input id="usrname" type="text" name="name" class="form-control" required="">
						</div>
						<div class="form-group">
							<label for="recipient-name" class="control-label">Email:</label>
							<input id="usremail" type="email" name="email" class="form-control" required="">
						</div>

						<div class="form-group">
							<label for="recipient-name" class="control-label">Senha <span style="font-weight: normal;">(deixe vazio para não alterar)</span>:</label>	
						    <div class="input-group">
						      	<input id="pass-act-usr" type="password" name="password" class="form-control">
						      	<span class="input-group-btn">
						        	<button id="btn-exibir-senha" class="btn btn-warning" type="button" title="Ver / Ocultar Senha">
						        		<span id="icon-ver-senha" class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>
						        	</button>
						      	</span>
						    </div><!-- /input-group -->
					    </div>
					{{Form::close()}}
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-success" form="user-action-form">
						<span id="icon-modal-usu" class="glyphicon" aria-hidden="true"></span>
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
	{{Form::open(array('url' => 'user/delete', 'id' => 'remove-form', 'method' => 'post', 'role'=>'form'))}}
		<input id="pkdelusr" type="hidden" name="id" value="">
	{{Form::close()}}

@endsection