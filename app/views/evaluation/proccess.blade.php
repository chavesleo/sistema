<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	<title>{{$evaluation->title}}</title>
	<link href="{{Config::get('define.urlPadrao')}}packages/bootstrap-3.3.7-dist/css/bootstrap.min.css" rel="stylesheet">
@if($cssPagina)
	<link href="{{Config::get('define.urlPadrao')}}{{$cssPagina}}" rel="stylesheet">
@endif
</head>
<body>

	<div class="container">
		<div class="row">&nbsp;</div>
        <div class="row">
			<div class="col-lg-12">
				{{Form::open(array('id'=>'formulario', 'url' => 'proccess/finish', 'method' => 'post', 'role'=>'form', 'autocomplete' => 'off'))}}
					<div class="row form-top-title-row">
						<div class="col-xs-12 col-sm-offset-2 col-sm-8">
							<h1 class="form-top-title text-center">{{$evaluation->company->name}}
							<br/>
							<small>{{$evaluation->title}}</small></h1>
						</div>

						@forelse($evaluation->QuestionEvaluations as $questionEvaluation)
					    	@if ($questionEvaluation->question->type == 'a')
								<div class="col-sm-6 col-xs-12">
									<div class="form-group @if($questionEvaluation->question->mandatory == 's') has-warning @endif">
										<label style="min-height: 20px;"><span class="ordenation">{{$questionEvaluation->order}}</span> - {{$questionEvaluation->question->text}}: <span class="ajax-icon confirm-{{$questionEvaluation->question->id}} glyphicon glyphicon-ok-circle" title="Salvo com sucesso!" aria-hidden="true" style="color: #449D44;"></span><span class="ajax-icon error-{{$questionEvaluation->question->id}} glyphicon glyphicon-remove-circle"  style="color: #C9302C;" aria-hidden="true"  title="Erro ao salvar!" ></span></label>
										<input name="resposta[{{$questionEvaluation->question->id}}]" @if($questionEvaluation->question->mandatory == 's') required="" @endif value="{{$arrayRespostas[$questionEvaluation->question->id]['text']}}" type="text" pkque="{{$questionEvaluation->question->id}}" class="form-control ajax-save inputVerify">
									</div>
								</div>
					    	@elseif($questionEvaluation->question->type == 'b')
								<div class="col-sm-6 col-xs-12">
									<div class="form-group @if($questionEvaluation->question->mandatory == 's') has-warning @endif">
										<label style="min-height: 20px;"><span class="ordenation">{{$questionEvaluation->order}}</span> - {{$questionEvaluation->question->text}}: <span class="ajax-icon confirm-{{$questionEvaluation->question->id}} glyphicon glyphicon-ok-circle" title="Salvo com sucesso!" aria-hidden="true" style="color: #449D44;"></span><span class="ajax-icon error-{{$questionEvaluation->question->id}} glyphicon glyphicon-remove-circle"  style="color: #C9302C;" aria-hidden="true"  title="Erro ao salvar!" ></span></label>
										<textarea name="resposta[{{$questionEvaluation->question->id}}]" @if($questionEvaluation->question->mandatory == 's') required="" @endif class="form-control ajax-save textareaVerify" pkque="{{$questionEvaluation->question->id}}" rows="3">{{$arrayRespostas[$questionEvaluation->question->id]['text']}}</textarea>
									</div>
								</div>
					    	@elseif($questionEvaluation->question->type == 'c')
					    		<div class="col-sm-6 col-xs-12">
					    			<div class="form-group @if($questionEvaluation->question->mandatory == 's') has-warning @endif">
					    				<label style="min-height: 20px;"><span class="ordenation">{{$questionEvaluation->order}}</span> - {{$questionEvaluation->question->text}}: <span class="ajax-icon confirm-{{$questionEvaluation->question->id}} glyphicon glyphicon-ok-circle" title="Salvo com sucesso!" aria-hidden="true" style="color: #449D44;"></span><span class="ajax-icon error-{{$questionEvaluation->question->id}} glyphicon glyphicon-remove-circle"  style="color: #C9302C;" aria-hidden="true"  title="Erro ao salvar!" ></span></label>
										<select name="resposta[{{$questionEvaluation->question->id}}]" @if($questionEvaluation->question->mandatory == 's') required="" @endif pkque="{{$questionEvaluation->question->id}}" value="{{$arrayRespostas[$questionEvaluation->question->id]['text']}}" class="form-control ajax-save inputVerify">
											<option value="">Selecione</option>
											@forelse($questionEvaluation->question->options as $options)
												<option value="{{$options->id}}" @if($arrayRespostas[$questionEvaluation->question->id]['option_id'] == $options->id) selected @endif >{{$options->text}}</option>
											@empty
												<option value="">Não há opção</option>
											@endforelse
										</select>
					    			</div>
					    		</div>
					    	@elseif($questionEvaluation->question->type == 'd')
								<div class="col-sm-6 col-xs-12">
									<div class="form-group @if($questionEvaluation->question->mandatory == 's') has-warning @endif">
										<label style="min-height: 20px;"><span class="ordenation">{{$questionEvaluation->order}}</span> - {{$questionEvaluation->question->text}} <small>Use CTRL para múltiplos</small>: <span class="ajax-icon confirm-{{$questionEvaluation->question->id}} glyphicon glyphicon-ok-circle" title="Salvo com sucesso!" aria-hidden="true" style="color: #449D44;"></span><span class="ajax-icon error-{{$questionEvaluation->question->id}} glyphicon glyphicon-remove-circle"  style="color: #C9302C;" aria-hidden="true"  title="Erro ao salvar!" ></span></label>
										<select multiple name="resposta[{{$questionEvaluation->question->id}}]" @if($questionEvaluation->question->mandatory == 's') required="" @endif pkque="{{$questionEvaluation->question->id}}" value="{{$arrayRespostas[$questionEvaluation->question->id]['text']}}" class="form-control ajax-save inputVerify">
											@forelse($questionEvaluation->question->options as $options)
												<option value="{{$options->id}}" @if(in_array($options->id, $arrayRespostas[$questionEvaluation->question->id]['arrayMultiplasMarcadas']) ) selected @endif >{{$options->text}}</option>
											@empty
												<option value="">Não há opção</option>
											@endforelse
										</select>
									</div>
								</div>
					    	@elseif($questionEvaluation->question->type == 'e')
								<div class="col-sm-6 col-xs-12">
									<div class="form-group @if($questionEvaluation->question->mandatory == 's') has-warning @endif">
										<label style="min-height: 20px;"><span class="ordenation">{{$questionEvaluation->order}}</span> - {{$questionEvaluation->question->text}}: <span class="ajax-icon confirm-{{$questionEvaluation->question->id}} glyphicon glyphicon-ok-circle" title="Salvo com sucesso!" aria-hidden="true" style="color: #449D44;"></span><span class="ajax-icon error-{{$questionEvaluation->question->id}} glyphicon glyphicon-remove-circle"  style="color: #C9302C;" aria-hidden="true"  title="Erro ao salvar!" ></span></label>
										<input name="resposta[{{$questionEvaluation->question->id}}]" value="{{$arrayRespostas[$questionEvaluation->question->id]['text']}}" type="number" @if($questionEvaluation->question->mandatory == 's') required="" @endif pkque="{{$questionEvaluation->question->id}}" class="form-control ajax-save inputVerify">
									</div>
								</div>
					    	@elseif($questionEvaluation->question->type == 'f')
								<div class="col-sm-6 col-xs-12">
									<div class="form-group @if($questionEvaluation->question->mandatory == 's') has-warning @endif">
										<label style="min-height: 20px;"><span class="ordenation">{{$questionEvaluation->order}}</span> - {{$questionEvaluation->question->text}}: <span class="ajax-icon confirm-{{$questionEvaluation->question->id}} glyphicon glyphicon-ok-circle" title="Salvo com sucesso!" aria-hidden="true" style="color: #449D44;"></span><span class="ajax-icon error-{{$questionEvaluation->question->id}} glyphicon glyphicon-remove-circle"  style="color: #C9302C;" aria-hidden="true"  title="Erro ao salvar!" ></span></label>
										<input name="resposta[{{$questionEvaluation->question->id}}]" value="{{$arrayRespostas[$questionEvaluation->question->id]['text']}}" type="text" @if($questionEvaluation->question->mandatory == 's') required="" @endif pkque="{{$questionEvaluation->question->id}}" class="form-control ajax-save mask-data inputVerify">
									</div>
								</div>
					    	@elseif($questionEvaluation->question->type == 'g')
								<div class="col-sm-6 col-xs-12">
									<div class="form-group @if($questionEvaluation->question->mandatory == 's') has-warning @endif">
										<label style="min-height: 20px;"><span class="ordenation">{{$questionEvaluation->order}}</span> - {{$questionEvaluation->question->text}}: <span class="ajax-icon confirm-{{$questionEvaluation->question->id}} glyphicon glyphicon-ok-circle" title="Salvo com sucesso!" aria-hidden="true" style="color: #449D44;"></span><span class="ajax-icon error-{{$questionEvaluation->question->id}} glyphicon glyphicon-remove-circle"  style="color: #C9302C;" aria-hidden="true"  title="Erro ao salvar!" ></span></label>
										<input name="resposta[{{$questionEvaluation->question->id}}]" @if($questionEvaluation->question->mandatory == 's') @endif  value="{{$arrayRespostas[$questionEvaluation->question->id]['text']}}" required="" type="text" pkque="{{$questionEvaluation->question->id}}" class="form-control ajax-save mask-cpf inputVerify">
									</div>
								</div>
					    	@elseif($questionEvaluation->question->type == 'h')
								<div class="col-sm-6 col-xs-12">
									<div class="form-group @if($questionEvaluation->question->mandatory == 's') has-warning @endif">
										<label style="min-height: 20px;"><span class="ordenation">{{$questionEvaluation->order}}</span> - {{$questionEvaluation->question->text}}: <span class="ajax-icon confirm-{{$questionEvaluation->question->id}} glyphicon glyphicon-ok-circle" title="Salvo com sucesso!" aria-hidden="true" style="color: #449D44;"></span><span class="ajax-icon error-{{$questionEvaluation->question->id}} glyphicon glyphicon-remove-circle"  style="color: #C9302C;" aria-hidden="true"  title="Erro ao salvar!" ></span></label>
										<input name="resposta[{{$questionEvaluation->question->id}}]" @if($questionEvaluation->question->mandatory == 's') required="" @endif value="{{$arrayRespostas[$questionEvaluation->question->id]['text']}}" type="text" pkque="{{$questionEvaluation->question->id}}" class="form-control ajax-save mask-cnpj inputVerify">
									</div>
								</div>
					    	@elseif($questionEvaluation->question->type == 'i')
								<div class="col-sm-6 col-xs-12">
									<div class="form-group @if($questionEvaluation->question->mandatory == 's') has-warning @endif">
										<label style="min-height: 20px;"><span class="ordenation">{{$questionEvaluation->order}}</span> - {{$questionEvaluation->question->text}}: <span class="ajax-icon confirm-{{$questionEvaluation->question->id}} glyphicon glyphicon-ok-circle" title="Salvo com sucesso!" aria-hidden="true" style="color: #449D44;"></span><span class="ajax-icon error-{{$questionEvaluation->question->id}} glyphicon glyphicon-remove-circle"  style="color: #C9302C;" aria-hidden="true"  title="Erro ao salvar!" ></span></label>
										<input name="resposta[{{$questionEvaluation->question->id}}]" @if($questionEvaluation->question->mandatory == 's') required="" @endif value="{{$arrayRespostas[$questionEvaluation->question->id]['text']}}" type="text" pkque="{{$questionEvaluation->question->id}}" class="form-control ajax-save mask-cep inputVerify">
									</div>
								</div>
					    	@elseif($questionEvaluation->question->type == 'j')
								<div class="col-sm-6 col-xs-12">
									<div class="form-group @if($questionEvaluation->question->mandatory == 's') has-warning @endif">
										<label style="min-height: 20px;"><span class="ordenation">{{$questionEvaluation->order}}</span> - {{$questionEvaluation->question->text}}: <span class="ajax-icon confirm-{{$questionEvaluation->question->id}} glyphicon glyphicon-ok-circle" title="Salvo com sucesso!" aria-hidden="true" style="color: #449D44;"></span><span class="ajax-icon error-{{$questionEvaluation->question->id}} glyphicon glyphicon-remove-circle"  style="color: #C9302C;" aria-hidden="true"  title="Erro ao salvar!" ></span></label>
										<input name="resposta[{{$questionEvaluation->question->id}}]" @if($questionEvaluation->question->mandatory == 's') required="" @endif value="{{$arrayRespostas[$questionEvaluation->question->id]['text']}}" type="text" pkque="{{$questionEvaluation->question->id}}" class="form-control ajax-save mask-phone inputVerify">
									</div>
								</div>
					    	@elseif($questionEvaluation->question->type == 'k')
								<div class="col-sm-6 col-xs-12">
									<div class="form-group @if($questionEvaluation->question->mandatory == 's') has-warning @endif">
										<label style="min-height: 20px;"><span class="ordenation">{{$questionEvaluation->order}}</span> - {{$questionEvaluation->question->text}}: <span class="ajax-icon confirm-{{$questionEvaluation->question->id}} glyphicon glyphicon-ok-circle" title="Salvo com sucesso!" aria-hidden="true" style="color: #449D44;"></span><span class="ajax-icon error-{{$questionEvaluation->question->id}} glyphicon glyphicon-remove-circle"  style="color: #C9302C;" aria-hidden="true"  title="Erro ao salvar!" ></span></label>
										<input name="resposta[{{$questionEvaluation->question->id}}]" type="email" value="{{$arrayRespostas[$questionEvaluation->question->id]['text']}}" class="form-control ajax-save inputVerify" @if($questionEvaluation->question->mandatory == 's') required="" @endif pkque="{{$questionEvaluation->question->id}}">
									</div>
								</div>
					    	@elseif($questionEvaluation->question->type == 'l')
								<div class="col-sm-6 col-xs-12">
									<div class="form-group has-warning">
										<label style="min-height: 20px;"><span class="ordenation">{{$questionEvaluation->order}}</span> - UF de Interesse:</label>
										<select required="" class="form-control comboUf" destinoid="{{$questionEvaluation->question->id}}">
											<option value="">Selecione</option>
											@forelse($listaUf as $uf)
												<option value="{{$uf->id}}" @if( isset($arrayRespostas[$questionEvaluation->question->id]['selected_state']) && $uf->id == $arrayRespostas[$questionEvaluation->question->id]['selected_state']) selected @endif>{{$uf->name}}</option>
											@empty
												<option value="">Erro ao carregar UF.</option>
											@endforelse
										</select>
									</div>
								</div>
								<div class="col-sm-6 col-xs-12">
									<div class="form-group has-warning">
										<label style="min-height: 20px;"><span class="ordenation">{{$questionEvaluation->order}}</span> - Cidade de Interesse: <span class="ajax-icon confirm-{{$questionEvaluation->question->id}} glyphicon glyphicon-ok-circle" title="Salvo com sucesso!" aria-hidden="true" style="color: #449D44;"></span><span class="ajax-icon error-{{$questionEvaluation->question->id}} glyphicon glyphicon-remove-circle"  style="color: #C9302C;" aria-hidden="true"  title="Erro ao salvar!" ></span></label></label>
										<select id="comboCidade-{{$questionEvaluation->question->id}}" required="" pkque="{{$questionEvaluation->question->id}}" class="form-control ajax-save comboCidades">

											@forelse($arrayRespostas[$questionEvaluation->question->id]['comboCityOfState'] as $cityOfState)
												<option value="{{$cityOfState->id}}" @if( $cityOfState->id == $arrayRespostas[$questionEvaluation->question->id]['text']) selected @endif>{{$cityOfState->name}}</option>
											@empty
												<option value="">Selecione uma UF</option>
											@endforelse

										</select>
									</div>
								</div>
					    	@elseif($questionEvaluation->question->type == 'm')
								<div class="col-sm-6 col-xs-12">
									<label style="min-height: 20px;"><span class="ordenation">{{$questionEvaluation->order}}</span> - {{$questionEvaluation->question->text}}: <span class="ajax-icon confirm-{{$questionEvaluation->question->id}} glyphicon glyphicon-ok-circle" title="Salvo com sucesso!" aria-hidden="true" style="color: #449D44;"></span><span class="ajax-icon error-{{$questionEvaluation->question->id}} glyphicon glyphicon-remove-circle"  style="color: #C9302C;" aria-hidden="true"  title="Erro ao salvar!" ></span></label>
									<div class="input-group">
										<div class="input-group-addon">R$</div>
										<input name="resposta[{{$questionEvaluation->question->id}}]" @if($questionEvaluation->question->mandatory == 's') required="" @endif value="{{$arrayRespostas[$questionEvaluation->question->id]['text']}}" type="text" pkque="{{$questionEvaluation->question->id}}" class="form-control text-right ajax-save mask-money inputVerify">
										<div class="input-group-addon">,00</div>
									</div>
								</div>
								<div class="clearfix"></div>
					    	@elseif($questionEvaluation->question->type == 'n')
					    		N
					    	@elseif($questionEvaluation->question->type == 'o')
								<div class="col-sm-6 col-xs-12">
									<div class="form-group has-warning">
										<label style="min-height: 20px;"><span class="ordenation">{{$questionEvaluation->order}}</span> - {{$questionEvaluation->question->text}} - Selecione uma UF:</label>
										<select required="" class="form-control comboUf" destinoid="{{$questionEvaluation->question->id}}">
											<option value="">Selecione</option>
											@forelse($listaUf as $uf)
												<option value="{{$uf->id}}" @if( isset($arrayRespostas[$questionEvaluation->question->id]['selected_state']) && $uf->id == $arrayRespostas[$questionEvaluation->question->id]['selected_state']) selected @endif>{{$uf->name}}</option>
											@empty
												<option value="">Erro ao carregar UF.</option>
											@endforelse
										</select>
									</div>
								</div>
								<div class="col-sm-6 col-xs-12">
									<div class="form-group has-warning">
										<label style="min-height: 20px;"><span class="ordenation">{{$questionEvaluation->order}}</span> - {{$questionEvaluation->question->text}} - Selecione uma Cidade: <span class="ajax-icon confirm-{{$questionEvaluation->question->id}} glyphicon glyphicon-ok-circle" title="Salvo com sucesso!" aria-hidden="true" style="color: #449D44;"></span><span class="ajax-icon error-{{$questionEvaluation->question->id}} glyphicon glyphicon-remove-circle"  style="color: #C9302C;" aria-hidden="true"  title="Erro ao salvar!" ></span></label></label>
										<select id="comboCidade-{{$questionEvaluation->question->id}}" required="" pkque="{{$questionEvaluation->question->id}}" class="form-control ajax-save comboCidades">
											@forelse($arrayRespostas[$questionEvaluation->question->id]['comboCityOfState'] as $cityOfState)
												<option value="{{$cityOfState->id}}" @if( $cityOfState->id == $arrayRespostas[$questionEvaluation->question->id]['text']) selected @endif>{{$cityOfState->name}}</option>
											@empty
												<option value="">Selecione uma UF</option>
											@endforelse
										</select>
									</div>
								</div>
					    	@endif
						@empty
							<p>Nenhuma questão adicionada.</p>
						@endforelse

					</div>
					<input id="pkeva" name="evaluation_id" type="hidden" value="{{$evaluation->id}}">
					<input id="pkpro" name="proccess_id" type="hidden" value="{{Session::get('proccess_init.proccess_id')}}">
        		{{Form::close()}}
			</div>
        </div>

	</div>



<nav class="navbar navbar-default navbar-fixed-bottom">
  <div class="container">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
			<div id="progress-top" class="progress" title="Progresso">
		  		<div class="barra-progresso progress-bar active progress-bar-info progress-bar-striped" role="progressbar" aria-valuenow="{{$arrayPercentCount['percent']}}" aria-valuemin="0" aria-valuemax="100" style="width: {{$arrayPercentCount['percent']}}%">
					<p class="text-center">{{$arrayPercentCount['percent_formated']}}%</p>
	    			<span class="sr-only">{{$arrayPercentCount['percent_formated']}}% Completo</span>
			  	</div>
			</div>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">

      <ul class="nav navbar-nav">
        <li>
			<div class="dropup">
				<button class="btn btn-block navbar-btn btn-warning dropdown-toggle" type="button" data-toggle="dropdown">
					<span class="glyphicon glyphicon-export" aria-hidden="true"></span>
					&nbsp;&nbsp;Exportar
					<span class="caret"></span>
				</button>
				<ul class="dropdown-menu">
					<li><a href="#"><span class="glyphicon glyphicon-file" aria-hidden="true"></span>&nbsp;&nbsp;CSV</a></li>
					<li class="divider"></li>
					<li><a href="#"><span class="glyphicon glyphicon-link" aria-hidden="true"></span>&nbsp;&nbsp;PDF</a></li>
				</ul>
			</div>
        </li>
        <li>
			<div id="progress-mid" class="progress" title="Progresso">
		  		<div class="barra-progresso progress-bar active progress-bar-info progress-bar-striped" role="progressbar" aria-valuenow="{{$arrayPercentCount['percent']}}" aria-valuemin="0" aria-valuemax="100" style="width: {{$arrayPercentCount['percent']}}%">
					<p class="text-center">{{$arrayPercentCount['percent_formated']}}%</p>
	    			<span class="sr-only">{{$arrayPercentCount['percent_formated']}}% Completo</span>
			  	</div>
			</div>
        </li>
        <li>
			<button class="btn btn-info navbar-btn btn-block pull-right" type="submit" form="formulario">
				<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>&nbsp;&nbsp;<span class="">Finalizar</span>
			</button>
        </li>
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>

	<input id="ajaxurl" type="hidden" value="{{Config::get('define.urlPadrao')}}">
	<input type="hidden" id="defaultRoute" value="{{URL::to('ajax')}}">

	<script src="{{Config::get('define.urlPadrao')}}packages/jquery-1.11.3/jquery-1.11.3.min.js"></script>
	<script src="{{Config::get('define.urlPadrao')}}packages/jquery-1.11.3/jquery.mask.min.js"></script>
	<script src="{{Config::get('define.urlPadrao')}}packages/bootstrap-3.3.7-dist/js/bootstrap.min.js"></script>
@if($jsPagina)
	<script src="{{Config::get('define.urlPadrao')}}{{$jsPagina}}"></script>
@endif
</body>
</html>