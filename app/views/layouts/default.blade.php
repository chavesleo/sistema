<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>{{$tituloPagina}}</title>
	<link href="{{Config::get('define.urlPadrao')}}packages/bootstrap-3.3.7-dist/css/bootstrap.min.css" rel="stylesheet">
	<link href="{{Config::get('define.urlPadrao')}}packages/sweetalert2/sweetalert2.min.css" rel="stylesheet">
	<link href="{{Config::get('define.urlPadrao')}}packages/font-awesome-4.6.3/css/font-awesome.min.css" rel="stylesheet">
	<link href="{{Config::get('define.urlPadrao')}}css/layouts/menu.css" rel="stylesheet">
	@if($cssPagina)
		<link href="{{Config::get('define.urlPadrao')}}{{$cssPagina}}" rel="stylesheet">
	@endif
</head>
<body>

	<div class="row affix-row">

		<!--MENU-->
		<div class="col-sm-3 col-md-2 affix-sidebar">
			<div class="sidebar-nav">
				<div class="navbar navbar-default" role="navigation">
					<div class="navbar-header">
						<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-navbar-collapse">
							<span class="sr-only">Alternar Navegação</span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
						</button>
						<span class="visible-xs navbar-brand">&nbsp;</span>
					</div>
					<div class="navbar-collapse collapse sidebar-navbar-collapse">
						<ul class="nav navbar-nav" id="sidenav01">
							<li class="active">
								<a href="#" style="color: #2E7E3E;" data-toggle="collapse" data-target="#toggleDemo0" data-parent="#sidenav01" class="collapsed">
									<p style="margin: 0 10px;">
										<strong>Olá,</strong> {{Auth::user()->fullname}} <span class="caret"></span>
									</p>
								</a>
								<div class="collapse" id="toggleDemo0" style="height: 0px;">
									<ul class="nav nav-list">
										<li><a href="#" style="color: #2E7E3E;" data-toggle="modal" data-target="#modalChangePass" ><i class="fa fa-key fa-lg"></i> Alterar Senha</a></li>
										<li><a href="{{Config::get('define.urlPadrao')}}logout" style="color: #2E7E3E;"><i class="fa fa-sign-out fa-lg"></i> Sair</a></li>
									</ul>
								</div>
							</li>
							{{-- <li @if($menuAtivo == 1) class="active" @endif><a href="{{Config::get('define.urlPadrao')}}"><span style="font-size: 26px;"><i class="fa fa-home"></i></span>&nbsp;&nbsp;Início</a></li>--}}
							<li @if($menuAtivo == 2) class="active" @endif><a href="{{Config::get('define.urlPadrao')}}evaluation/list"><span style="font-size: 24px;"><i class="fa fa-list-alt"></i></span>&nbsp;&nbsp;Formulários</a></li>
							<li @if($menuAtivo == 3) class="active" @endif><a href="{{Config::get('define.urlPadrao')}}question/list"><span style="font-size: 24px;"><i class="fa fa-list"></i></span>&nbsp;&nbsp;Banco de Perguntas</a></li>
							<li @if($menuAtivo == 4) class="active" @endif><a href="{{Config::get('define.urlPadrao')}}expansionplan"><span style="font-size: 24px;"><i class="fa fa-line-chart"></i></span>&nbsp;&nbsp;Plano de Expansão</a></li>
							<li @if($menuAtivo == 5) class="active" @endif><a href="{{Config::get('define.urlPadrao')}}user/list"><span style="font-size: 24px;"><i class="fa fa-users"></i></span>&nbsp;&nbsp;Usuários</a></li>
							<li @if($menuAtivo == 6) class="active" @endif><a href="{{Config::get('define.urlPadrao')}}report"><span style="font-size: 24px;"><i class="fa fa-bar-chart"></i></span>&nbsp;&nbsp;Relatórios</a></li>
						</ul>
					</div><!--/.nav-collapse -->
				</div>
			</div>
		</div><!-- MENU -->

		@yield('conteudo')

		<div class="modal fade" id="modalChangePass" tabindex="-1" role="dialog" aria-labelledby="modalChangePass">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title text-center">Alterar Senha</h4>
					</div>
					<div class="modal-body">
						{{Form::open(array('url' => 'user/changepass', 'id' => 'user-change-pass', 'method' => 'post', 'role'=>'form', 'autocomplete' => 'off'))}}
							<input id="pkusr" name="id" type="hidden" value="0">

							<div class="form-group">
								<label for="recipient-name" class="control-label">Senha Atual:</label>	
							    <div class="input-group">
							      	<input id="pass-act-usr-1" type="password" name="old_password" class="form-control">
							      	<span class="input-group-btn">
							        	<button class="btn btn-warning btn-exibir-senha" pk="1" type="button" title="Ver / Ocultar Senha">
							        		<span id="icon-ver-senha-1" class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>
							        	</button>
							      	</span>
							    </div><!-- /input-group -->
						    </div>

							<div class="form-group">
								<label for="recipient-name" class="control-label">Nova Senha:</label>	
							    <div class="input-group">
							      	<input id="pass-act-usr-2" type="password" name="new_password" class="form-control" minlength="4">
							      	<span class="input-group-btn">
							        	<button class="btn btn-warning btn-exibir-senha" pk="2" type="button" title="Ver / Ocultar Senha">
							        		<span id="icon-ver-senha-2" class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>
							        	</button>
							      	</span>
							    </div><!-- /input-group -->
						    </div>

							<div class="form-group">
								<label for="recipient-name" class="control-label">Confirme a Nova Senha:</label>	
							    <div class="input-group">
							      	<input id="pass-act-usr-3" type="password" name="new_password_confirmation" class="form-control" minlength="4">
							      	<span class="input-group-btn">
							        	<button class="btn btn-warning btn-exibir-senha" pk="3" type="button" title="Ver / Ocultar Senha">
							        		<span id="icon-ver-senha-3" class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>
							        	</button>
							      	</span>
							    </div><!-- /input-group -->
						    </div>

						{{Form::close()}}
					</div>
					<div class="modal-footer">
						<button type="submit" class="btn btn-success" form="user-change-pass">
							<span id="icon-modal-usu" class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span>
							&nbsp;Salvar
						</button>
						<button type="button" class="btn btn-info" data-dismiss="modal">
							<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
							&nbsp;Cancelar
						</button>
					</div>
				</div>
			</div>
		</div><!-- MODAL -->
		
	<script src="{{Config::get('define.urlPadrao')}}packages/jquery-1.11.3/jquery-1.11.3.min.js"></script>
	<script src="{{Config::get('define.urlPadrao')}}packages/jquery-1.11.3/jquery.maskMoney.min.js"></script>
	<script src="{{Config::get('define.urlPadrao')}}packages/bootstrap-3.3.7-dist/js/bootstrap.min.js"></script>
	<script src="{{Config::get('define.urlPadrao')}}packages/sweetalert2/sweetalert2.min.js"></script>
	<script src="{{Config::get('define.urlPadrao')}}js/layout/default.js"></script>
	@if($jsPagina)
		<script src="{{Config::get('define.urlPadrao')}}{{$jsPagina}}"></script>
	@endif

</body>
</html>