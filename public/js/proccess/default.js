$(function() {

	var SPMaskBehavior = function (val) {
		return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
	},
	spOptions = {
		onKeyPress: function(val, e, field, options) {
			field.mask(SPMaskBehavior.apply({}, arguments), options);
		}
	};

	$('.mask-phone').mask(SPMaskBehavior, spOptions);
	$('.mask-data').mask('00/00/0000');
	$('.mask-cpf').mask('000.000.000-00');
	$('.mask-cnpj').mask('00.000.000/0000-00');
	$('.mask-cep').mask('00.000-000');
	$('.mask-money').mask('000.000.000', {reverse: true});

	$('.ajax-save').on('change', function(){

		var tk = $("[name='_token']").val();
		var pkpro = $('#pkpro').val();
		var resp = $(this).val();
		var pkque = $(this).attr('pkque');
		var url = $('#defaultRoute').val();

		if ($.trim(resp) != '') {
			var jqxhr = $.post( url, { "_token": tk, "proccess_id": pkpro, "question_id": pkque, "text": resp }, function(retorno) {
				console.log(retorno);
				//insere o loading
				$('.confirm-'+pkque).fadeIn('slow');
				$('.error-'+pkque).hide();
			})
			.done(function() {
				//console.log(retorno);
			})
			.fail(function() {
				$('.confirm-'+pkque).hide();
				$('.error-'+pkque).fadeIn('slow');
			})
			.always(function() {
				$('.confirm-'+pkque).delay(1000).fadeOut('slow');
			});
		}
		
	});

	$('.comboUf').on('change', function(){
		
		var idUf = $(this).val();
		var url = $('#ajaxurl').val();
		var idDestino = $(this).attr('destinoid');

		if (idUf != '') {
			$.get(url + 'ajax/listCitiesByStateId/' + idUf, function(data, status){
		        if (status == 'success') {
		       	 	$('#comboCidade-'+idDestino).html(data);
		        }else{
		        	$('#comboCidade-'+idDestino).html('<option value="">Erro ao carregar</option>');
		        }
		    });
		}else{
			$('#comboCidade-'+idDestino).html('<option value="">Selecione uma UF</option>');
		}
	});

});

$(document).ready(function(){ 
	$('.inputVerify').each(function(){
		if ($(this).val() != '') {
			$(this).parent('div').removeClass('has-warning');
		}
	});
	$('.textareaVerify').each(function(){
		if ($(this).val() != '') {
			$(this).parent('div').removeClass('has-warning');
		}
	});
});