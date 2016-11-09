@extends('layouts.default')

@section('conteudo')

		<div class="col-sm-9 col-md-10 affix-content">
			<div class="container">
				<div class="row"> &nbsp; </div>

					<div class="panel panel-default">

						<div class="panel-heading">
							<div class="row">
								<div class="col-md-10">
									<h4>Perguntas</h4>
								</div>
								<div class="col-md-2">
									<button type="button" class="btn btn-primary pull-right action-usr" pk="1" title="{{Lang::get('textos.tit_adicionar')}}" data-toggle="modal" data-target="#modalAdicionarPergunta">
										<span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
										&nbsp;&nbsp;{{Lang::get('textos.tit_adicionar')}}
										</a>
									</button>
								</div>
							</div>
						</div>
						<div class="table-responsive">
							<table id="user-list" class="table table-bordered table-hover table-condensed table-hovered table-striped">
								<thead>
									<tr>
										<td class="col-md-1 text-center"><strong>{{Lang::get('textos.tit_cod')}}</strong></td>
										<td class="col-md-7"><strong>Texto</strong></td>
										<td class="col-md-3"><strong>Tipo</strong></td>
										<td class="col-md-1 text-center"><strong><small>Obrig.</small></strong></td>
									</tr>
								</thead>
								<tbody>
									@forelse($questions as $question)
								    	<tr>
											<td class="text-center"><p>{{$question->id}}</p></td>
											<td><p>{{$question->text}}</p></td>
											<td><p>{{$arrayEnumTipo[$question->type]}}</p></td>
											<td class="text-center"><p>{{$arrayEnumObrig[$question->mandatory]}}</p></td>
								    	</tr>
									@empty
									<tr>
										<td colspan="5">Nenhuma pergunta cadastrada.</td>
									</tr>
									@endforelse
								</tbody>
							</table>
						</div><!-- table-responsive -->
					</div><!-- panel -->

					<div class="modal fade" id="modalAdicionarPergunta" tabindex="-1" role="dialog" aria-labelledby="modalAdicionarPergunta">
						<div class="modal-dialog" role="document">
							<div class="modal-content">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
									<h4 class="modal-title text-center">Cadastrar Pergunta</h4>
								</div>
								<div class="modal-body">
									{{Form::open(array('url' => 'question/add', 'id' => 'question-add-form', 'method' => 'post', 'role'=>'form', 'autocomplete' => 'off'))}}
										
										<div class="row">
											<div class="col-lg-4">
												<div class="form-group">
													<label class="control-label">Texto:</label>
													<input type="text" name="text" class="form-control input-sm" required="">
												</div>
											</div>
											<div class="col-lg-5">
												<div class="form-group">
													<label class="control-label">Tipo:</label>
													<select id="tipo-questao" name="type" class="form-control input-sm" required="">
														<option value="">Selecione</option>
														<option value="a">Texto Curto</option>
														<option value="b">Texto Longo</option>
														<option value="c">Seleção Única</option>
														<option value="d">Seleção Múltipla</option>
														<option value="e">Somente Números</option>
														<option value="f">Data (11/11/1111)</option>
														<option value="g">CPF (999.999.999-99)</option>
														<option value="h">CNPJ (99.999.999/0001-99)</option>
														<option value="i">CEP (99.999-999)</option>
														<option value="j">Telefone (99) 99999-9999</option>
														<option value="k">E-mail (email&Ocirc;email.com)</option>
														<option value="l">Cidade de Interesse</option>
													</select>
												</div>
											</div>
											<div class="col-lg-3">
												<div class="form-group">
													<label class="control-label">Obrigatório:</label>
													<select name="mandatory" class="form-control input-sm" required="">
														<option value="">Selecione</option>
														<option value="s">Sim</option>
														<option value="n">Não</option>
													</select>
												</div>
											</div>
										</div>
										<hr />
										<div id="quadro-opcoes" style="display:none;">						
											<div class="row" style="background-color: #ccc; padding-top: 5px;">
												<div class="col-xs-6 col-sm-8"><label>Texto da Opção:</label></div>
												<div class="col-xs-4 col-sm-2"><label>Peso:</label></div>
												<div class="col-xs-2 col-sm-2"><label>Ação:</label></div>
											</div>
											<div class="row" style="background-color: #ccc; padding-bottom: 5px;">
												<div class="col-xs-6 col-sm-8">
													<input id="option-text" type="text" class="form-control input-sm">
												</div>
												<div class="col-xs-4 col-sm-2">
													<input id="option-rating" type="number" step="0.1" class="form-control input-sm" maxlength="4" min="0" max="10" >
												</div>
												<div class="col-xs-2 col-sm-2">
													<div class="btn-group" role="group">
														<button id="btn-adicionar-opcao" type="button" class="btn btn-primary btn-sm" title="Adicionar"><span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span></button>
													</div>
												</div>
											</div>
										</div>
										<div id="opcoes-criadas" style="display:none;">	</div>
										<input id="form-trigger" type="submit" style="display: none;">
									{{Form::close()}}
								</div>
								<div class="modal-footer">
									<button id="btn-salvar-pergunta" type="button" class="liberado btn btn-success">
										<span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span>
										&nbsp;Salvar
									</button>
									<button type="button" class="btn btn-info" data-dismiss="modal">
										<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
										&nbsp;Cancelar
									</button>
								</div>
							</div>
						</div>
					</div><!-- MODAL -->

				</div><!--END ROW-->
			</div> <!-- END CONTAINER -->
		</div> <!-- CONTEUDO -->
	</div><!-- affix-row [pre menu]-->

	{{--FORMULÁRIO DE REMOÇÃO--}}
	{{Form::open(array('url' => 'question/delete', 'id' => 'remove-form', 'method' => 'post', 'role'=>'form'))}}
		<input id="pkdelquestion" type="hidden" name="id" value="">
	{{Form::close()}}

@endsection