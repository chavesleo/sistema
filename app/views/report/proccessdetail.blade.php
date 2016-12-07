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
	                	<label>Status: </label>
	                	<div class="btn-group" role="group" aria-label="...">
	                		<button class="btn btn-sm btn-default text-bold" style="background-color: #eee; outline:none; cursor: default;" readonly><span @if($formularioCompleto['status'] == 'r') class="text-bold text-red" @elseif($formularioCompleto['status'] == 'a') class="text-bold text-green" @elseif($formularioCompleto['status'] == 'e') class="text-bold text-yellow" @endif>{{$arrayStatus[$formularioCompleto['status']]}}<span></button>
	                		<button class="btn btn-sm btn-warning" title="Alterar" data-toggle="modal" data-target="#modalChangeStatus"><span class="glyphicon glyphicon glyphicon-pencil" aria-hidden="true"></span></button>
	                	</div>
	                </div>

	                @if($objProcessoCorrente->forced_status)
		                <div class="col-sm-12 alert alert-info" role="alert">
		                	<p><span class="glyphicon glyphicon-alert" aria-hidden="true"></span> Status alterado manualmente, observação: <b>{{$objProcessoCorrente->forced_comment}}</b></p>
						</div>
	                @endif

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

							@if($formularioCompleto['status'] == 'a')
								<tr>
									<td colspan="2">Análise não disponível para formulários aprovados.</td>
								</tr>
							@else
								@forelse($arrayAnaliseSecundaria as $idFormAnaliseSec => $ddFormAnaliseSec)
								    <tr style="cursor: pointer;" data-toggle="modal" data-target="#modal-analise-{{$idFormAnaliseSec}}">
								    	<td>{{ $ddFormAnaliseSec['title'] }}</td>
								    	<td><span @if($ddFormAnaliseSec['status'] == 'r') class="text-red" @elseif($ddFormAnaliseSec['status'] == 'a') class="text-green" @elseif($ddFormAnaliseSec['status'] == 'e') class="text-yellow" @endif>{{$arrayStatus[$ddFormAnaliseSec['status']]}}<span></td>
								    </tr>

<div class="modal fade in" id="modal-analise-{{$idFormAnaliseSec}}" tabindex="-1" role="dialog" aria-labelledby="modal-analise-{{$idFormAnaliseSec}}">
	<div class="modal-dialog modal-md" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
				<h4 class="modal-title text-center">{{$ddFormAnaliseSec['title']}}</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-xs-12">
						@if(!$ddFormAnaliseSec['analise']['nota_minima'])
							<p><span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
							Nota Final <strong>{{str_replace('.',',',$ddFormAnaliseSec['final_note'])}}</strong> ficou <strong>abaixo</strong> do esperado que é <strong>{{str_replace('.',',',$ddFormAnaliseSec['min_note'])}}</strong> </p>
						@else
							<p><span class="glyphicon glyphicon-ok" aria-hidden="true"></span></p>
							Nota Final <strong>{{str_replace('.',',',$ddFormAnaliseSec['final_note'])}}</strong> ficou <strong>acima</strong> do esperado que é <strong>{{str_replace('.',',',$ddFormAnaliseSec['min_note'])}}</strong> </p>
						@endif
					</div>
					<div class="col-xs-12">
						<p><span class="glyphicon @if(!$ddFormAnaliseSec['analise']['cidade_interesse']) glyphicon-remove @else glyphicon-ok @endif" aria-hidden="true"></span>
						@if($ddFormAnaliseSec['analise']['cidade_interesse']) Selecionou uma cidade de Interesse @else Selecionou uma cidade fora de Interesse @endif</p>
					</div>

					@if(!$ddFormAnaliseSec['analise']['cidade_interesse'])
						<tr>
							@if($ddFormAnaliseSec['analise']['cidade_interesse']) 
								<th><span class="glyphicon glyphicon-ok" aria-hidden="true"></span></th>
								<td> Selecionou uma cidade próxima que está dentro do raio, a {{round($ddFormAnaliseSec['analise']['cidade_interesse'],0)}}km</td>
							@else
								<th><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></th>
								<td>Cidade selecionada não está dentro do raio desejado</td>
							@endif
						</tr>
					@endif

					<div class="col-xs-12">
						<p><span class="glyphicon @if(!$ddFormAnaliseSec['analise']['investimento']) glyphicon-remove @else glyphicon-ok @endif" aria-hidden="true"></span>
						@if(!$ddFormAnaliseSec['analise']['investimento']) <strong>Não</strong> @endif <strong>Possui</strong> o investimento necessário</p>
					</div>

					<div class="col-xs-12 alert alert-warning text-center text-bold">
						Respostas em comum
					</div>
						@forelse($ddFormAnaliseSec['questoes'] as $dadosQuestSec)
							<div class="col-sm-6">
								<p>
									<strong>{{$dadosQuestSec['pergunta']}}</strong>
									<br>
									<strong>R: </strong>
									@if(isset($dadosQuestSec['option_text']))
										{{$dadosQuestSec['option_text']}}
									@else
										{{$dadosQuestSec['text']}}
									@endif
								</p>
							</div>
						@empty
							<p>Não há questões em comum.</p>
						@endforelse
				</div>
			</div>
		</div>
	</div>
</div>
								@empty
									<tr>
										<td colspan="2">Todos os formulários já foram verificados.</td>
									</tr>
								@endforelse
							@endif

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
				<div class="row"> &nbsp; </div>
				<div class="row"> 
	                <div class="col-sm-4">
						<a href="{{URL::to('proccess/export/csv/')}}/{{Session::get('proccess_init.proccess_id')}}" target="_blank" class="btn btn-primary btn-block" role="button">
							<span class="glyphicon glyphicon-file" aria-hidden="true"></span>&nbsp;&nbsp;EXPORTAR CSV
						</a>
	                </div>
	                <div class="col-sm-4">
						<a href="#" class="btn btn-info btn-block" role="button" disabled>
							<span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span>&nbsp;&nbsp;EXPORTAR PDF
						</a>
	                </div>
	                <div class="col-sm-4">
						<a href="{{URL::to('proccess/export/json/')}}/{{Session::get('proccess_init.proccess_id')}}" target="_blank" class="btn btn-primary btn-block" role="button">
							<span class="glyphicon glyphicon-link " aria-hidden="true"></span>&nbsp;&nbsp;EXPORTAR JSON
						</a>
	                </div>
                </div>
				<div class="row"> &nbsp; </div>
			</div> <!-- END CONTAINER -->
		</div> <!-- CONTEUDO -->
	</div><!-- affix-row [pre menu]-->

	<div class="modal fade in" id="modalChangeStatus" tabindex="-1" role="dialog" aria-labelledby="modalChangeStatus">
		<div class="modal-dialog modal-md" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
					<h4 class="modal-title text-center">Alterar Status</h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-xs-12">
							<div class="input-group">
								<div class="input-group-btn">
									<button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span id="cng-status-title">Status</span> <span class="caret"></span></button>
									<ul class="dropdown-menu">
										<li><a href="#" class="cng-status-btn" pk="a">Aprovado</a></li>
										<li><a href="#" class="cng-status-btn" pk="r">Reprovado</a></li>
										<li><a href="#" class="cng-status-btn" pk="e">Em Análise</a></li>
									</ul>
								</div><!-- /btn-group -->
								{{Form::open(array('url' => URL::to('proccess/changestatus').'/'.Session::get('proccess_init.proccess_id'), 'id' => 'frm-change-status', 'method' => 'post', 'role'=>'form', 'autocomplete' => 'off'))}}
								<input name="forced_obs" type="text" placeholder="Observação" maxlength="150" class="form-control" aria-label="Observação">
								<input id="new_forced_status" name="new_status" type="hidden" >
								{{Form::close()}}
							</div><!-- /input-group -->
						</div><!-- /.col-lg-6 -->
						<div class="col-xs-12">&nbsp;</div>
						<div class="col-xs-12">
							<button type="submit" form="frm-change-status" class="btn btn-info btn-block" aria-expanded="true"><span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span> Salvar</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

@endsection