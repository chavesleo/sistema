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
                <div class="col-lg-4 col-md-6">
                    <div class="panel panel-green">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-user-plus fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge">{{$totalProcessosFinalizados}}</div>
                                </div>
                                <div class="col-xs-12 text-right">
                                    <div style="font-size: 16px;">Candidatos Cadastrados</div>
                                </div>
                            </div>
                        </div>
                        <a href="#">
                            <div class="panel-footer">
                                <span class="pull-left">Ver Detalhes</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="panel panel-blue">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-file-text-o fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge">{{$totalProcessosIniciados}}</div>
                                </div>
                                <div class="col-xs-12 text-right">
                                    <div style="font-size: 16px;">Processos em Andamento</div>
                                </div>
                            </div>
                        </div>
                        <a href="#">
                            <div class="panel-footer">
                                <span class="pull-left">Ver Detalhes</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="panel panel-yellow">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-refresh fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge">{{$totalProcessosAguardando}}</div>
                                </div>
                                <div class="col-xs-12 text-right">
                                    <div style="font-size: 16px;">Aguardando Análise</div>
                                </div>
                            </div>
                        </div>
                        <a href="#">
                            <div class="panel-footer">
                                <span class="pull-left">Ver Detalhes</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">Line Chart</h3>
                        </div>

                        <div class="panel-body">
                            <canvas id="cline" width="503" height="316" style="width: 503px; height: 316px;"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">Donut Chart</h3>
                        </div>

                        <div class="panel-body">
                            <div style="max-width:400px; margin:0 auto;">
                                <canvas class="round" id="cdonut" width="400" height="312" style="width: 400px; height: 312px;"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">Bar Chart</h3>
                        </div>

                        <div class="panel-body">
                            <canvas id="cbar" width="503" height="316" style="width: 503px; height: 316px;"></canvas>
                        </div>
                    </div>
                </div> 
                <div class="col-sm-4">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">Pie Chart</h3>
                        </div>

                        <div class="panel-body">
                            <div style="max-width:400px; margin:0 auto;">
                                <canvas class="round" id="cpie" width="400" height="320" style="width: 400px; height: 320px;"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


























				
			</div> <!-- END CONTAINER -->
		</div> <!-- CONTEUDO -->
	</div><!-- affix-row [pre menu]-->

@endsection