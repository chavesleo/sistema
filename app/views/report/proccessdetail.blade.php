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
	                <div class="col-sm-3 col-sm-offset-1">
	                	<p>
		                	<strong>Iniciado em:</strong>
		                	<br>
		                	{{$formularioCompleto['data_ini']}}
	                	</p>
	                </div>
	                <div class="col-sm-4 text-center">
						<strong>Total Preenchido:</strong>	                	
						<div class="progress">
							<div class="progress-bar progress-bar-info progress-bar-striped" role="progressbar" aria-valuenow="{{$formularioCompleto['progresso']}}" aria-valuemin="0" aria-valuemax="100" style="width: {{$formularioCompleto['progresso']}}%">
								<p>{{$formularioCompleto['progresso']}}%</p>
							</div>
						</div>
	                </div>
	                <div class="col-sm-2 col-sm-offset-2">
	                	<p>
	                		<strong>Status:</strong>
	                		<br>
	                		<span @if($formularioCompleto['status'] == 'r') class="text-red" @elseif($formularioCompleto['status'] == 'a') class="text-green" @elseif($formularioCompleto['status'] == 'e') class="text-yellow" @endif>{{$arrayStatus[$formularioCompleto['status']]}}<span>
	                	</p>
	                </div>
					<div class="col-sm-6 alert @if($formularioCompleto['status'] == 'r') alert-danger @elseif($formularioCompleto['status'] == 'a') alert-success @elseif($formularioCompleto['status'] == 'e') alert-warning @endif" role="alert">
						<div class="col-sm-12 text-center">
							<h4>Este Formlário<h4>
						</div>
						<table class="table table-responsive table-hover table-condensed">
							<tr>
							@if(!$objAnalisePrimaria->auxPercentual)
								<th><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></th>
								<td>Nota Final <strong>{{str_replace('.',',',$formularioCompleto['nota_final'])}}</strong> ficou <strong>abaixo</strong> do esperado que é <strong>{{str_replace('.',',',$formularioCompleto['nota_minima'])}}</strong> </td>
							@else
								<th><span class="glyphicon glyphicon-ok" aria-hidden="true"></span></th>
								<td>Nota Final <strong>{{str_replace('.',',',$formularioCompleto['nota_final'])}}</strong> ficou <strong>acima</strong> do esperado que é <strong>{{str_replace('.',',',$formularioCompleto['nota_minima'])}}</strong> </td>
							@endif
							</tr>

							<tr>
								<th><span class="glyphicon @if(!$objAnalisePrimaria->auxCidadeInteresse) glyphicon-remove @else glyphicon-ok @endif" aria-hidden="true"></span></th>
								<td>@if($objAnalisePrimaria->auxCidadeInteresse) Selecionou uma cidade de Interesse @else Selecionou uma cidade fora de Interesse @endif</td>
							</tr>
							@if(!$objAnalisePrimaria->auxCidadeInteresse)
								<tr>
									@if($objAnalisePrimaria->auxCidadeRaio) 
										<th><span class="glyphicon glyphicon-ok" aria-hidden="true"></span></th>
										<td> Selecionou uma cidade próxima que está dentro do raio, a {{round($objAnalisePrimaria->auxCidadeRaio,0)}}km</td>
									@else
										<th><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></th>
										<td>Cidade selecionada não está dentro do raio desejado</td>
									@endif
								</tr>
							@endif
							<tr>
								<th><span class="glyphicon @if(!$objAnalisePrimaria->auxInvestimento) glyphicon-remove @else glyphicon-ok @endif" aria-hidden="true"></span></th>
								<td>@if(!$objAnalisePrimaria->auxInvestimento) <strong>Não</strong> @endif <strong>Possui</strong> o investimento necessário</td>
							</tr>
						</table>
					</div>
					<div class="col-sm-6 alert @if($formularioCompleto['status'] == 'r') alert-danger @elseif($formularioCompleto['status'] == 'a') alert-success @elseif($formularioCompleto['status'] == 'e') alert-warning @endif" role="alert">
						<div class="col-sm-12 text-center">
							<h4>Outros Formulários<h4>
						</div>
						<table class="table table-responsive table-hover table-condensed">
							<tr>
								<th>Formulário</th>
								<th>Status</th>
							</tr>
							<tr>
								<td>SUL - Interior - 2016/02</td>
								<td>Reprovado</td>
							</tr>
						</table>
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