$(function() {

	$('#comboUf').on('change', function(){
		
		var idUf = $(this).val();
		var url = $('#defaultRoute').val();

		if (idUf != '') {
			$.get(url + '/' + idUf, function(data, status){
		        if (status == 'success') {
		       	 	$('#comboCidades').html(data);
		        }
		    });
		}else{
			$('#comboCidades').html('<option value="">Selecione uma UF</option>');
		}
	});

	$('#btn-adicionar-cidade').on('click', function(){

		if ($('#comboUf').val() == '') {
			$('#comboUf').parent('div').addClass('has-warning');
		}else if($('#comboCidades').val() == ''){
			$('#comboUf').parent('div').removeClass('has-warning');
			$('#comboCidades').parent('div').addClass('has-warning');
		}else if($('#inputraio').val() == ''){
			$('#comboUf').parent('div').removeClass('has-warning');
			$('#comboCidades').parent('div').removeClass('has-warning');
			$('#inputraio').parent('div').addClass('has-warning');
		}else if($('#comboFormato').val() == ''){
			$('#comboUf').parent('div').removeClass('has-warning');
			$('#comboCidades').parent('div').removeClass('has-warning');
			$('#comboFormato').parent('div').addClass('has-warning');
		}else if($('#inputmeta').val() == ''){
			$('#comboUf').parent('div').removeClass('has-warning');
			$('#comboCidades').parent('div').removeClass('has-warning');
			$('#comboFormato').parent('div').removeClass('has-warning');
			$('#inputmeta').parent('div').addClass('has-warning');
		}else if($('#inputinvest').val() == ''){
			$('#comboUf').parent('div').removeClass('has-warning');
			$('#comboCidades').parent('div').removeClass('has-warning');
			$('#comboFormato').parent('div').removeClass('has-warning');
			$('#inputmeta').parent('div').removeClass('has-warning');
			$('#inputinvest').parent('div').addClass('has-warning');
		}else{
			var formatoId = $('#comboFormato').val();
			var cidadeId = $('#comboCidades').val();
			var meta = $('#inputmeta').val();
			var investimento = $('#inputinvest').val();
			var formatoText = $('#comboFormato option:selected').text();
			var cidadeText = $('#comboCidades option:selected').text();
			var ufSigla = $('#comboUf option:selected').attr('sigla');
			var contador = $('.cidade-adicionada').length + 1;
			var raio = $('#inputraio').val();

			$('#tbCidades').children('tbody').append('<tr class="cidade-adicionada linha-'+contador+'"> <input type="hidden" name="cidade['+contador+'][formato]" value="'+formatoId+'"> <input type="hidden" name="cidade['+contador+'][id]" value="'+cidadeId+'"> <input type="hidden" name="cidade['+contador+'][raio]" value="'+raio+'"> <input type="hidden" name="cidade['+contador+'][meta]" value="'+meta+'"> <input type="hidden" name="cidade['+contador+'][investment]" value="'+investimento+'"> <td>'+cidadeText+ ' / '+ ufSigla+'</td> <td class="text-center">'+formatoText+'</td> <td class="text-center">'+meta+'</td> <td class="text-center"> R$ '+investimento+',00</td> <td> <button type="button" class="btn btn-danger apagarlinha" linha="'+contador+'" title="Apagar"> <span class="glyphicon glyphicon-trash" aria-hidden="true"></span></button> </td> </tr>');

			$('.badge-cidades-selecionadas').text(contador);

			$('#comboUf').val('');
			$('#comboCidades').val('');
			$('#comboFormato').val('');
			$('#inputmeta').val('');
			$('#inputinvest').val('');
		}
	});

	$( "body" ).on( "click", ".apagarlinha", function(){
		var linha = $(this).attr('linha');
		var contador = $('.cidade-adicionada').length - 1;

		$('.linha-'+linha).remove();
		$('.badge-cidades-selecionadas').text(contador);

	});

	$('#inputinvest').mask('0.000.000', {reverse: true});

});

$(document).ready(function(){
  $('[data-toggle="tooltip"]').tooltip({
    container : 'body'
  });
});