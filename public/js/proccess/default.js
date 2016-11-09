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

	$('.ajax-save').on('change', function(){

		var tk = $("[name='_token']").val();
		var pkpro = $('#pkpro').val();
		var resp = $(this).val();
		var pkque = $(this).attr('pkque');
		var url = $('#defaultRoute').val();

		if (resp.trim() != '') {
			var jqxhr = $.post( url, { "_token": tk, "proccess_id": pkpro, "question_id": pkque, "text": resp }, function($retorno) {
				//insere o loading
				console.log("success");
				console.log($retorno);
			})
			.done(function() {
				console.log("done");
			})
			.fail(function() {
				console.log("error");
			})
			.always(function() {
				console.log("always");
				//remove o loading
			});
		}
		
	});


});