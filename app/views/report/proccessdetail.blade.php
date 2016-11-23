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

                <div class="row" style="background-color: #eee;">            	
	                <div class="col-sm-12 text-center" style="background-color: #B9B9B9; color: #444;">
	                	<h4>{{$formularioCompleto['titulo']}}<br><small style="color: #555;">{{$formularioCompleto['descricao']}}</small></h4>
	                </div>
	                <div class="col-sm-3">
	                	<p>
		                	<strong>Início:</strong>
		                	<br>
		                	{{$formularioCompleto['data_ini']}}
	                	</p>
	                </div>
	                <div class="col-sm-3">
	                	<p>
	                		<strong>Progresso:</strong>
	                		<br>
	                		{{$formularioCompleto['progresso']}}%
                		</p>
	                </div>
	                <div class="col-sm-3">
	                	<p>
	                		<strong>Nota Mínima / Nota Final:</strong>
	                		<br>
	                		{{$formularioCompleto['nota_minima']}} / {{$formularioCompleto['nota_final']}}
                		</p>
	                </div>
	                <div class="col-sm-3">
	                	<p>
	                		<strong>Status:</strong>
	                		<br>
	                		<span @if($formularioCompleto['status'] == 'r') class="text-red" @elseif($formularioCompleto['status'] == 'a') class="text-green" @elseif($formularioCompleto['status'] == 'e') class="text-yellow" @endif>{{$arrayStatus[$formularioCompleto['status']]}}<span>
	                	</p>
	                </div>
                </div>

                <div class="row text-center" style="background-color: #B9B9B9;">
                	<h4 style="color: #555;">Perguntas</h4>
                </div>

                <div class="row" style="background-color: #eee;">

					@forelse($formularioCompleto['pergunta'] as $ordem => $questoes)
						<div class="col-sm-6">
							<p><strong>{{$ordem}} - {{$questoes['pergunta']}}:</strong>
            				<br/>
            				@if(!is_array($questoes['resposta']))
            					@if($questoes['resposta'] != '' )
            						<strong>R:</strong> {{$questoes['resposta']}}
            					@else
            						<strong>R:</strong> <span style="color: red;">Não respondido</span>
            					@endif
            				@else
            					<strong>R:</strong>
            					@foreach($questoes['resposta'] as $p2)
            					{{$p2}} -
            					@endforeach
            			 	@endif
							</p>
						</div>
					@empty
						vazio
					@endforelse
                </div>  
				
			</div> <!-- END CONTAINER -->
		</div> <!-- CONTEUDO -->
	</div><!-- affix-row [pre menu]-->

@endsection