<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Acesso</title>
	<link href="{{Config::get('define.urlPadrao')}}packages/bootstrap-3.3.7-dist/css/bootstrap.min.css" rel="stylesheet">
	<link href="{{Config::get('define.urlPadrao')}}css/login.css" rel="stylesheet">
</head>
<body>
	<div class="container">
		<div class="row">
			<div class="col-md-6 col-md-offset-3">
				<div class="panel panel-login">
					<div class="panel-heading">
						<div class="row">
							<div class="col-xs-6">
								<a href="#" @if (count($errors) == 0) class="active" @endif id="login-form-link">Login</a>
							</div>
							<div class="col-xs-6">
								<a href="#"  @if (count($errors) > 0) class="active" @endif id="register-form-link">Registrar</a>
							</div>
						</div>
						<hr>
					</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-lg-12">
							@if (count($errors) > 0)
								{{Form::open(array('url' => 'login', 'id' => 'login-form', 'method' => 'post', 'role'=>'form', 'style' => 'display: none;', 'enctype'=> 'multipart/form-data' ))}}
							@else
								{{Form::open(array('url' => 'login', 'id' => 'login-form', 'method' => 'post', 'role'=>'form', 'style' => 'display: block;', 'enctype'=> 'multipart/form-data' ))}}
							@endif

							@if (Session::has('alerta') || count($errors) > 0)
								<div class="alert alert-warning" role="alert">
								@foreach ($errors->all() as $msg)
									<p>
										<span class="glyphicon  glyphicon-warning-sign" aria-hidden="true"></span>
										&nbsp;&nbsp;&nbsp;{{$msg}}
									</p>
								@endforeach
									<p>
										<span class="glyphicon  glyphicon-warning-sign" aria-hidden="true"></span>
										&nbsp;&nbsp;&nbsp;{{Session::pull('alerta')}}
									</p>
								</div>
							@endif
									<div class="form-group">
										<input type="email" name="email" tabindex="1" class="form-control" placeholder="Email" required="">
									</div>
									<div class="form-group">
										<input type="password" name="password" tabindex="2" class="form-control" placeholder="Senha" required="">
									</div>
									<br/>
									<div class="form-group">
										<div class="row">
											<div class="col-sm-6 col-sm-offset-3">
												<button type="submit" class="form-control btn btn-login"  tabindex="3">
													<span class="glyphicon glyphicon-log-in" aria-hidden="true"></span>
													&nbsp;&nbsp;&nbsp;Entrar
												</button>
											</div>
										</div>
									</div>
									<div class="form-group hide">
										<div class="row">
											<div class="col-lg-12">
												<div class="text-center">
													<a href="#" tabindex="5" class="forgot-password">Esqueci minha senha</a>
												</div>
											</div>
										</div>
									</div>
								{{Form::close()}}

								@if (count($errors) > 0)
									{{Form::open(array('url' => 'empresa/cadastrar', 'id' => 'register-form', 'method' => 'post', 'role'=>'form', 'style' => 'display: block;', 'enctype'=> 'multipart/form-data' ))}}
								@else
									{{Form::open(array('url' => 'empresa/cadastrar', 'id' => 'register-form', 'method' => 'post', 'role'=>'form', 'style' => 'display: none;', 'enctype'=> 'multipart/form-data' ))}}
								@endif
									
									@if (count($errors) > 0)
										<div class="alert alert-warning" role="alert">
										@foreach ($errors->all() as $msg)
											<p>
												<span class="glyphicon  glyphicon-warning-sign" aria-hidden="true"></span>
												&nbsp;&nbsp;&nbsp;{{$msg}}
											</p>
										@endforeach
										</div>
									@endif

									<div class="form-group">
										<input type="text" name="company_name" tabindex="1" class="form-control" placeholder="Nome da Empresa" required="">
									</div>
						            <!-- div class="input-group">
						                <label class="input-group-btn">
						                    <span class="btn btn-primary btn-upload">
						                        Buscar&hellip; <input name="logo" type="file" style="display: none;" >
						                    </span>
						                </label>
						                <input type="text" class="form-control" value="Selecione um logotipo (opcional)" readonly>
						            </div -->
									<div class="form-group">
										<select name="segment_id" class="form-control" required="" style="height: 45px;">
											<option value="">Selecione um Segmento</option>
								            @forelse($arraySegmentos as $segmento)
											    <li></li>
											    <option value="{{$segmento->id}}">{{$segmento->name}}</option>
											@empty
											@endforelse
										</select>
									</div>
									<div class="form-group">
										<input type="text" name="user_name" tabindex="2" class="form-control" placeholder="Nome do Usuário" required="">
									</div>
									<div class="form-group">
										<input type="email" name="user_email" tabindex="3" class="form-control" placeholder="Email do Usuário" required="">
									</div>
									<div class="form-group">
										<input type="password" name="user_pass" tabindex="4" class="form-control" placeholder="Senha" required="">
									</div>
									<div class="form-group">
										<input type="password" name="user_pass_confirmation" tabindex="5" class="form-control" placeholder="Confirme a Senha" required="">
									</div>
									<div class="form-group">
										<div class="row">
											<div class="col-sm-6 col-sm-offset-3">
												<button type="submit" class="form-control btn btn-register" tabindex="6">
													<span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span>
													&nbsp;&nbsp;&nbsp;Cadastrar
												</button>
											</div>
										</div>
									</div>
								{{Form::close()}}
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<script src="{{Config::get('define.urlPadrao')}}packages/jquery-1.11.3/jquery-1.11.3.min.js"></script>
	<script src="{{Config::get('define.urlPadrao')}}js/login.js"></script>
</body>
</html>