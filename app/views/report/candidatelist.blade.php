@extends('layouts.default')

@section('conteudo')

		<div class="col-sm-9 col-md-10 affix-content">
			<div class="container">
				<div class="row"> &nbsp; </div>

					<div class="panel panel-default">

						<div class="panel-heading">
							<div class="row">
								<div class="col-md-12">
									<h4>Candidatos Cadastrados</h4>
								</div>
							</div>
						</div>
						
						<table class="table table-condensed table-bordered table-hover table-striped">
							<thead>
								<tr>
									<td class="col-md-7"><strong>Nome</strong></td>
									<td class="col-md-3"><strong>Email</strong></td>									
								</tr>
							</thead>
							<tbody>

							</tbody>
						</table>
						
					</div><!-- panel -->

				</div><!--END ROW-->
			</div> <!-- END CONTAINER -->
		</div> <!-- CONTEUDO -->
	</div><!-- affix-row [pre menu]-->

@endsection