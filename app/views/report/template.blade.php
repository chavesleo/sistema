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
                        <thead>
                            <tr style="background-color: #9c9c9c; color: whitesmoke;">
                                <th>Cód.</th>
                                <th>Nome</th>
                                <th>Data Cadastro</th>
                                <th>Processo(s)</th>
                            </tr>
                        </thead>
                        <tbody>

                        @forelse($arrayProccessList as $dadosProccessList)
                            <tr  style="font-weight: bold; background-color: #B9B9B9; color: whitesmoke;">
                                <td>{{$dadosProccessList['candidate_id']}}</td>
                                <td>{{$dadosProccessList['candidate_name']}}</td>
                                <td>{{$dadosProccessList['candidate_date_reg']}}</td>
                                <td>{{count($dadosProccessList['proccesses'])}}</td>
                            </tr>

                            
                                <tr>
                                    <td colspan="4" style="padding: 0;">
                                        <table class="table table-condensed table-striped table-responsive table-hover" style="margin-bottom: 0px;">
                                            <thead>
                                                <tr style="background-color: #B9B9B9;">
                                                    <th>Formulário</th>
                                                    <th>Início</th>
                                                    <th>Progresso</th>
                                                    <th>Nota</th>
                                                    <th>Status</th>
                                                    <th>&nbsp;</th>
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
                                                    <a class="btn btn-info btn-xs" href="#" title="Ver Questionário" role="button">
                                                        <span class="glyphicon glyphicon-arrow-right" aria-hidden="true"></span>
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