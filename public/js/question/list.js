$(function() {

	$('.btn-desativar').on('click', function(){
		var pk = $(this).attr('pk');
		var name = $(this).attr('usrname');

		swal({
			title: name,
			text: "Quer mesmo remover este usuário?",
			type: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#C9302C',
			cancelButtonColor: '#31B0D5',
			confirmButtonText: 'Sim'
		}).then(function() {
			if (pk != 0 && pk != "") {
				$('#pkdelusr').val(pk);
				$('#remove-form').submit();
			}else{
				swal(
					'Erro ao deletar',
					'',
					'error'
				)
			}
		})
	});

	$('#tipo-questao').on('change', function(){
		var tipoOpcao = $(this).val();

		if(tipoOpcao == 'c' || tipoOpcao == 'd'){
			$('#quadro-opcoes').show();
			$('#btn-salvar-pergunta').removeClass('liberado');
		}else{
			$('#quadro-opcoes').hide();
			$('.row-opcao').remove();
			$('#btn-salvar-pergunta').addClass('liberado');
		}

	});

	$('#btn-adicionar-opcao').on('click', function(){

		//captura valor dos inputs
		var optText = $('#option-text').val();
		var optRating = $('#option-rating').val();

		if (optText == '') {
			$('#option-rating').parent('div').removeClass('has-warning');
			$('#option-text').parent('div').addClass('has-warning');
			$('#option-text').focus();
		}else if(optRating == ''){
			$('#option-rating').parent('div').addClass('has-warning');
			$('#option-text').parent('div').removeClass('has-warning');
			$('#option-rating').focus();
		}else{

			//zera o valor dos inputs
			$('#option-text').val('').focus();
			$('#option-rating').val('');
			$('#option-text').parent('div').removeClass('has-warning');
			$('#option-rating').parent('div').removeClass('has-warning');

			//adiciona opções
			$('#opcoes-criadas').show();
			$('#opcoes-criadas').append(' <div class="row row-opcao" style="margin-top: 5px;margin-bottom: 5px;"> <div class="col-xs-6 col-sm-8"> <input name="option[text][]" type="text" class="form-control input-sm" value="'+optText+'" required=""> </div> <div class="col-xs-4 col-sm-2"> <input name="option[rating][]" type="number" step="0.1" class="form-control input-sm" value="'+optRating+'" maxlength="4" max="10"  required=""> </div> <div class="col-xs-2 col-sm-2"> <div class="btn-group" role="group"> <button type="button" class="btn btn-danger btn-sm btn-remover-opcao" title="Remover"><span class="glyphicon glyphicon-minus-sign" aria-hidden="true"></span></button> </div> </div> </div> ');
			$('#btn-salvar-pergunta').addClass('liberado');

		}
	});

	$( document ).on( "click", '.btn-remover-opcao', function() {
		$(this).parent('div').parent('div').parent('div').remove();
	});

	$('#btn-salvar-pergunta').on('click', function(){
		
		if ($(this).hasClass('liberado')) {
			$('#form-trigger').trigger('click');
		}else{
			$('#option-text').val('').focus();
			sweetAlert(
				'Sem opções',
				'Cadastre pelo menos uma opção!',
				'warning'
			)
		}

	});

});