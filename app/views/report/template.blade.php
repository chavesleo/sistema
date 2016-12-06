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
                <div class="row">
                    <div class="col-xs-4 col-sm-4">
                        <canvas id="pieChart"></canvas>
                    </div>
                    <div class="col-xs-8 col-sm-8">
                        <canvas id="lineChart"></canvas>
                    </div>
                </div>
                <div class="row"> &nbsp; </div>
                <div class="row">
                    <table class="table table-condensed table-responsive">
                    <thead>
                        <tr style="background-color: #B9B9B9; color: #000;">
                            <th class="text-center" colspan="4"><h4>Lista de Candidatos</h4></th>
                        </tr>
                    </thead>
                        <tbody>
                        @forelse($arrayProccessList as $dadosProccessList)
                            <tr style="background-color: #B9B9B9; color: #000;">
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
                                                    <a class="btn btn-info btn-sm" href="{{URL::current()}}/proccessdetails/{{$idProcesso}}" title="Ver Questionário" role="button">
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
                </div>  
				
			</div> <!-- END CONTAINER -->
		</div> <!-- CONTEUDO -->
	</div><!-- affix-row [pre menu]-->

    <script>

        var pieChart = new Chart(document.getElementById("pieChart"), {
            type: 'pie',
            data: {
                labels: ["Reprovados", "Em Análise", "Aprovados"],
                datasets: [{
                    data: [ {{$totalProcessosReprovados}}, {{$totalProcessosEmAnalise}}, {{$totalProcessosAprovados}}],
                    backgroundColor: [
                    '#E33E2B',
                    '#F1B500',
                    '#2CA24C'
                    ],
                    borderWidth: 1
                }]
            }
        });

        /*
        var barChart = new Chart(document.getElementById("barChart"), {
            type: 'bar',
            data: {
                labels: ["January", "February", "March", "April", "May", "June", "July"],
                datasets: [
                {
                    label: "My First dataset",
                    backgroundColor: [
                    'rgb(255, 99, 132)',
                    'rgb(54, 162, 235)',
                    'rgb(255, 206, 86)',
                    'rgb(75, 192, 192)',
                    'rgb(153, 102, 25)',
                    'rgb(255, 159, 64)'
                    ],
                    borderColor: [
                    'rgb(255,99,132)',
                    'rgb(54, 162, 235)',
                    'rgb(255, 206, 86)',
                    'rgb(75, 192, 192)',
                    'rgb(153, 102, 255)',
                    'rgb(255, 159, 64)'
                    ],
                    borderWidth: 1,
                    data: [65, 59, 80, 81, 56, 55, 40],
                }
                ]
            }
        });
        */
        var myLineChart = new Chart(document.getElementById("lineChart"), {
            type: 'line',
            data: {
                labels: ["Setembro",{{$graficoMensal['mes']}}],
                datasets: [
                    {
                        label: 'Novos Candidatos por Mês',
                        fill: false,
                        lineTension: 0.1,
                        backgroundColor: "rgb(75,192,192)",
                        borderColor: "rgb(75,192,192)",
                        borderCapStyle: 'butt',
                        borderDash: [],
                        borderDashOffset: 0.0,
                        borderJoinStyle: 'miter',
                        pointBorderColor: "rgb(75,192,192)",
                        pointBackgroundColor: "#fff",
                        pointBorderWidth: 1,
                        pointHoverRadius: 5,
                        pointHoverBackgroundColor: "rgb(75,192,192)",
                        pointHoverBorderColor: "rgb(220,220,220)",
                        pointHoverBorderWidth: 2,
                        pointRadius: 1,
                        pointHitRadius: 10,
                        data: [0,{{$graficoMensal['total']}}],
                        spanGaps: false,
                    }
                ]
            }
        });
    </script>

@endsection