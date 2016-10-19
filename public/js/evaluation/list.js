$(function() {

	$('.btn-apagar').on('click', function(){

		var pk = $(this).attr('pk');
		var title = $('#title-eva-'+pk).text();

		swal({
			title: title,
			text: "Quer mesmo remover este formulário?",
			type: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#C9302C',
			cancelButtonColor: '#31B0D5',
			confirmButtonText: 'Sim'
		}).then(function() {
			if (pk != 0 && pk != "") {
				$('#pkdelevaluation').val(pk);
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

});