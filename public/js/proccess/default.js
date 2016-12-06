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

	$('.mask-cpf').on('change', function(e){
		var valor = $(this).val();
		var valida = valor.match(/\d/g);
		valida = valida.join("");

		if (!validaCPF(valida)) {
			alert('CPF INVÁLIDO!');
			$(this).val('');
		}

	});

	$('.ajax-save').on('change', function(){

		var tk = $("[name='_token']").val();
		var pkpro = $('#pkpro').val();
		var resp = $(this).val();
		var pkque = $(this).attr('pkque');
		var url = $('#defaultRoute').val();

		if ($.trim(resp) != '') {
			$.post( url+'/svquestion', { "_token": tk, "proccess_id": pkpro, "question_id": pkque, "text": resp })
			.success(function(retorno) {
				//console.log(retorno);
				$('.confirm-'+pkque).fadeIn('slow');
				$('.error-'+pkque).hide();
				atualizaProgresso(pkpro);
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
	$('select').each(function(){
		if ($(this).val() != '') {
			$(this).parent('div').removeClass('has-warning');
		}
	});
});

function atualizaProgresso(pkpro){

	var url = $('#defaultRoute').val();
	var tk = $("[name='_token']").val();

	$.getJSON( url+'/atualizaprogresso/'+pkpro+'/true')
	.success(function(retorno) {
		//console.log(retorno);
		$('.barra-progresso').css( "width", retorno.percent+"%" );
		$('.barra-progresso').children('p').text(retorno.percent_formated+"%");
		$('.barra-progresso').children('span').text(retorno.percent_formated+"% Completo");
	})
	.fail(function() {
		console.log('erro');
	})
}

function validaCPF(strCPF) {
    var Soma;
    var Resto;
    Soma = 0;
	if (strCPF == "00000000000") return false;
	if (strCPF == "11111111111") return false;
	if (strCPF == "22222222222") return false;
	if (strCPF == "33333333333") return false;
	if (strCPF == "44444444444") return false;
	if (strCPF == "55555555555") return false;
	if (strCPF == "66666666666") return false;
	if (strCPF == "77777777777") return false;
	if (strCPF == "88888888888") return false;
	if (strCPF == "99999999999") return false;
    
	for (i=1; i<=9; i++) Soma = Soma + parseInt(strCPF.substring(i-1, i)) * (11 - i);
	Resto = (Soma * 10) % 11;
	
    if ((Resto == 10) || (Resto == 11))  Resto = 0;
    if (Resto != parseInt(strCPF.substring(9, 10)) ) return false;
	
	Soma = 0;
    for (i = 1; i <= 10; i++) Soma = Soma + parseInt(strCPF.substring(i-1, i)) * (12 - i);
    Resto = (Soma * 10) % 11;
	
    if ((Resto == 10) || (Resto == 11))  Resto = 0;
    if (Resto != parseInt(strCPF.substring(10, 11) ) ) return false;
    return true;
}