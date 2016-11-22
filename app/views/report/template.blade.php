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

                <dir class="row">
                    <table class="table table-condensed table-responsive">
                        <tbody>

                        @forelse($arrayProccessList as $dadosProccessList)
                            <tr  style="background-color: #B9B9B9; color: #000;">
                                <td><strong>Cód.: </strong>{{$dadosProccessList['candidate_id']}}</td>
                                <td><strong>Nome: </strong>{{$dadosProccessList['candidate_name']}}</td>
                                <td><strong>Data Cadastro: </strong>{{$dadosProccessList['candidate_date_reg']}}</td>
                                <td><strong>Processo(s): </strong>{{count($dadosProccessList['proccesses'])}}</td>
                            </tr>
                                <tr>
                                    <td colspan="4" style="padding: 0;">
                                        <table class="table table-condensed table-striped table-responsive table-hover" style="margin-bottom: 0px;">
                                            <thead>
                                                <tr style="background-color: #B9B9B9; color: #5e5e5e;">
                                                    <th class="col-xs-4">Formulário</th>
                                                    <th class="col-xs-2">Início</th>
                                                    <th class="col-xs-2">Progresso</th>
                                                    <th class="col-xs-1">Nota</th>
                                                    <th class="col-xs-2">Status</th>
                                                    <th class="col-xs-1">&nbsp;</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                            
                                        @forelse($dadosProccessList['proccesses'] as $idProcesso => $dadosProcesso)
                                            <tr @if($dadosProcesso['proccess_status'] == 'r') class="danger" @elseif($dadosProcesso['proccess_status'] == 'a') class="success" @elseif($dadosProcesso['proccess_status'] == 'e') class="warning" @endif>
                                                <td>{{$dadosProcesso['evaluation_title']}}</td>
                                                <td>{{$dadosProcesso['proccess_init_date']}}</td>
                                                <td>{{number_format($dadosProcesso['proccess_progress'], 1, ',', '')}}%</td>
                                                <td>{{number_format($dadosProcesso['proccess_note'], 2, ',', '')}}</td>
                                                <td @if($dadosProcesso['proccess_status'] == 'r') class="text-red" @elseif($dadosProcesso['proccess_status'] == 'a') class="text-green" @elseif($dadosProcesso['proccess_status'] == 'e') class="text-yellow" @endif>{{$arrayStatus[$dadosProcesso['proccess_status']]}}</td>
                                                <td>
                                                    <a class="btn btn-info btn-sm" href="#" title="Ver Questionário" role="button">
                                                        <i class="fa fa-file-text fa-lg" aria-hidden="true"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4">Nenhum Processo realizado</td>
                                            </tr>
                                        @endforelse
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                        @empty
                            <tr>
                                <td colspan="4">Nenhum Candidato encontrado.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </dir>  
				
			</div> <!-- END CONTAINER -->
		</div> <!-- CONTEUDO -->
	</div><!-- affix-row [pre menu]-->

@endsection