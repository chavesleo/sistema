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
		
		var formatoId = $('#comboFormato').val();
		var cidadeId = $('#comboCidades').val();
		var meta = $('#inputmeta').val();
		var formatoText = $('#comboFormato option:selected').text();
		var cidadeText = $('#comboCidades option:selected').text();
		var ufText = $('#comboUf option:selected').text();
		var contador = $('.cidade-adicionada').length + 1;

		$('#tbCidades').children('tbody').append('<tr class="cidade-adicionada linha-'+contador+'"> <input type="hidden" name="cidade['+contador+'][formato]" value="'+formatoId+'"> <input type="hidden" name="cidade['+contador+'][id]" value="'+cidadeId+'"> <input type="hidden" name="cidade['+contador+'][meta]" value="'+meta+'"> <td>'+cidadeText+ '/'+ ufText+'</td> <td>'+formatoText+'</td> <td>'+meta+'</td> <td> <button type="button" class="btn btn-danger apagarlinha" linha="'+contador+'" title="Apagar"> <span class="glyphicon glyphicon-trash" aria-hidden="true"></span></button> </td> </tr>');

		$('.badge-cidades-selecionadas').text(contador);
	});

	$( "body" ).on( "click", ".apagarlinha", function(){
		var linha = $(this).attr('linha');
		var contador = $('.cidade-adicionada').length - 1;

		$('.linha-'+linha).remove();
		$('.badge-cidades-selecionadas').text(contador);

	});

});