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
		<div class="row">
			<div class="col-lg-12">
				<h1 class="text-center">{{$evaluation->company->name}}</h1>
			</div>
			<div class="col-lg-12">
				<h2 class="text-center"></h2>
			</div>
		</div><!-- ROW TITULOS -->

        <div class="row">
			<div class="col-lg-12">
				{{Form::open(array('url' => 'proccess/finish', 'method' => 'post', 'role'=>'form', 'autocomplete' => 'off'))}}
					<div class="row form-top-title-row">
						<div class="col-xs-12 col-sm-offset-2 col-sm-8">
							<h1 class="form-top-title text-center">{{$evaluation->title}}<br/><small>{{$evaluation->description}}</small></h1>
						</div>

						@forelse($evaluation->QuestionEvaluations as $questionEvaluation)
					    	@if ($questionEvaluation->question->type == 'a')
								<div class="col-sm-6 col-xs-12">
									<div class="form-group @if($questionEvaluation->question->mandatory == 's') has-warning @endif">
										<label><span class="ordenation">{{$questionEvaluation->order}}</span> - {{$questionEvaluation->question->text}}:</label>
										<input name="resposta[{{$questionEvaluation->question->id}}]" @if($questionEvaluation->question->mandatory == 's') required="" @endif value="{{$questionEvaluation->question->id}}" type="text" pkque="{{$questionEvaluation->question->id}}" class="form-control ajax-save">
									</div>
								</div>
					    	@elseif($questionEvaluation->question->type == 'b')
								<div class="col-sm-6 col-xs-12">
									<div class="form-group @if($questionEvaluation->question->mandatory == 's') has-warning @endif">
										<label><span class="ordenation">{{$questionEvaluation->order}}</span> - {{$questionEvaluation->question->text}}:</label>
										<textarea name="resposta[{{$questionEvaluation->question->id}}]" @if($questionEvaluation->question->mandatory == 's') required="" @endif class="form-control ajax-save" pkque="{{$questionEvaluation->question->id}}" rows="3" style="resize:none;"></textarea>
									</div>
								</div>
					    	@elseif($questionEvaluation->question->type == 'c')
					    		<div class="col-sm-6 col-xs-12">
					    			<div class="form-group @if($questionEvaluation->question->mandatory == 's') has-warning @endif">
					    				<label><span class="ordenation">{{$questionEvaluation->order}}</span> - {{$questionEvaluation->question->text}}:</label>
										<select name="resposta[{{$questionEvaluation->question->id}}]" @if($questionEvaluation->question->mandatory == 's') required="" @endif pkque="{{$questionEvaluation->question->id}}" class="form-control ajax-save">
											<option value="">Selecione</option>
											@forelse($questionEvaluation->question->options as $options)
												<option value="{{$options->id}}">{{$options->text}}</option>
											@empty
												<option value="">Não há opção</option>
											@endforelse
										</select>
					    			</div>
					    		</div>
					    	@elseif($questionEvaluation->question->type == 'd')
					    		D
					    	@elseif($questionEvaluation->question->type == 'e')
								<div class="col-sm-6 col-xs-12">
									<div class="form-group @if($questionEvaluation->question->mandatory == 's') has-warning @endif">
										<label><span class="ordenation">{{$questionEvaluation->order}}</span> - {{$questionEvaluation->question->text}}:</label>
										<input name="resposta[{{$questionEvaluation->question->id}}]" type="text" @if($questionEvaluation->question->mandatory == 's') required="" @endif pkque="{{$questionEvaluation->question->id}}" class="form-control ajax-save mask-phone">
									</div>
								</div>
					    	@elseif($questionEvaluation->question->type == 'f')
								<div class="col-sm-6 col-xs-12">
									<div class="form-group @if($questionEvaluation->question->mandatory == 's') has-warning @endif">
										<label><span class="ordenation">{{$questionEvaluation->order}}</span> - {{$questionEvaluation->question->text}}:</label>
										<input name="resposta[{{$questionEvaluation->question->id}}]" type="text" @if($questionEvaluation->question->mandatory == 's') required="" @endif pkque="{{$questionEvaluation->question->id}}" class="form-control ajax-save mask-data">
									</div>
								</div>
					    	@elseif($questionEvaluation->question->type == 'g')
								<div class="col-sm-6 col-xs-12">
									<div class="form-group @if($questionEvaluation->question->mandatory == 's') has-warning @endif">
										<label><span class="ordenation">{{$questionEvaluation->order}}</span> - {{$questionEvaluation->question->text}}:</label>
										<input name="resposta[{{$questionEvaluation->question->id}}]" @if($questionEvaluation->question->mandatory == 's') required="" @endif type="text" pkque="{{$questionEvaluation->question->id}}" class="form-control ajax-save mask-cpf">
									</div>
								</div>
					    	@elseif($questionEvaluation->question->type == 'h')
					    		H
					    	@elseif($questionEvaluation->question->type == 'i')
					    		I
					    	@elseif($questionEvaluation->question->type == 'j')
					    		J
					    	@elseif($questionEvaluation->question->type == 'k')
								<div class="col-sm-6 col-xs-12">
									<div class="form-group @if($questionEvaluation->question->mandatory == 's') has-warning @endif">
										<label><span class="ordenation">{{$questionEvaluation->order}}</span> - {{$questionEvaluation->question->text}}:</label>
										<input name="resposta[{{$questionEvaluation->question->id}}]" type="email" class="form-control ajax-save" @if($questionEvaluation->question->mandatory == 's') required="" @endif>
									</div>
								</div>
					    	@elseif($questionEvaluation->question->type == 'l')
					    		L
					    	@endif
						@empty
							<p>Nenhuma questão adicionada.</p>
						@endforelse

						<div class="col-xs-12 col-sm-3">
							<button class="btn btn-info btn-block pull-right" style="font-size: 16px;">
								<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>&nbsp;&nbsp;<span class="">Exportar</span>
							</button>
						</div>

						<div class="col-xs-12 col-sm-3 col-sm-offset-6">
							<button class="btn btn-info btn-block pull-right" type="submit" style="font-size: 16px;">
								<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>&nbsp;&nbsp;<span class="">Finalizar</span>
							</button>
						</div>
					</div>
        		{{Form::close()}}
			</div>
        </div>

	</div>

	<input id="pkeva" type="hidden" value="{{$evaluation->id}}">
	<input id="pkpro" type="hidden" value="{{Session::get('proccess_init.proccess_id')}}">
	<input id="ajaxurl" type="hidden" value="{{Config::get('define.urlPadrao')}}">
	<input type="hidden" id="defaultRoute" value="{{URL::to('ajax/svquestion')}}">

	<script src="{{Config::get('define.urlPadrao')}}packages/jquery-1.11.3/jquery-1.11.3.min.js"></script>
	<script src="{{Config::get('define.urlPadrao')}}packages/jquery-1.11.3/jquery.mask.min.js"></script>
@if($jsPagina)
	<script src="{{Config::get('define.urlPadrao')}}{{$jsPagina}}"></script>
@endif
</body>
</html>