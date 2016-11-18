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
                            <tr>
                                <th>Cód.</th>
                                <th>Nome</th>
                                <th>Data Cadastro</th>
                                <th>Processo(s)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>Primeiro Candidato</td>
                                <td>15/05/2015</td>
                                <td>3</td>
                            </tr>
                            <tr>
                                <td colspan="4">    
                                    <table class="table table-condensed table-striped table-responsive table-hover">
                                        <thead>
                                            <tr>
                                                <th>Formulário</th>
                                                <th>Início</th>
                                                <th>Fim</th>
                                                <th>Progresso</th>
                                                <th>Nota</th>
                                                <th>Status</th>
                                                <th>&nbsp;</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Formulário Eventos 2016</td>
                                                <td>15/05/2016</td>
                                                <td>17/05/2016</td>
                                                <td>89,5%</td>
                                                <td>68,5</td>
                                                <td>Iniciado</td>
                                                <td>
                                                    <a class="btn btn-info btn-sm" href="#" title="Ver Questionário" role="button">
                                                        <span class="glyphicon glyphicon-expand" aria-hidden="true"></span>
                                                    </a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Formulário Eventos 2016</td>
                                                <td>15/05/2016</td>
                                                <td>17/05/2016</td>
                                                <td>89,5%</td>
                                                <td>68,5</td>
                                                <td>Aprovado</td>
                                                <td>
                                                    <a class="btn btn-info btn-sm" href="#" title="Ver Questionário" role="button">
                                                        <span class="glyphicon glyphicon-expand" aria-hidden="true"></span>
                                                    </a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Formulário Eventos 2016</td>
                                                <td>15/05/2016</td>
                                                <td>17/05/2016</td>
                                                <td>89,5%</td>
                                                <td>68,5</td>
                                                <td>Em Análise</td>
                                                <td>
                                                    <a class="btn btn-info btn-sm" href="#" title="Ver Questionário" role="button">
                                                        <span class="glyphicon glyphicon-expand" aria-hidden="true"></span>
                                                    </a>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>Segundo Candidato</td>
                                <td>07/05/2016</td>
                                <td>5</td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>Terceiro Candidato</td>
                                <td>11/06/2015</td>
                                <td>2</td>
                            </tr>
                        </tbody>
                    </table>
                </dir>  
				
			</div> <!-- END CONTAINER -->
		</div> <!-- CONTEUDO -->
	</div><!-- affix-row [pre menu]-->

@endsection