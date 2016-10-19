$(function() {

	$('.btn-apagar').on('click', function(){
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

	$('.action-usr').on('click', function(){

		var pk = $(this).attr('pk');
		var pka = $(this).attr('pka');
		var pkb = $(this).attr('pkb');
		var pkc = $(this).attr('pkc');
		var pkd = $(this).attr('pkd');

		$('#user-action-form')[0].reset();
		$('#pass-act-usr').attr('type', 'password');
		$('#icon-ver-senha').removeClass('glyphicon-eye-close').addClass('glyphicon-eye-open');

		switch(pk){
			case '1':
				$('#pkusr').val('0');
				$('#modalActionUsrTitle').text('Adicionar Usuário');
				$('#pass-act-usr').attr('required', '');
				$('#icon-modal-usu').removeClass('glyphicon-floppy-disk').addClass('glyphicon-ok');
				break;
			case '2': 
				$('#modalActionUsrTitle').text('Editar Usuário');
				$('#icon-modal-usu').removeClass('glyphicon-ok').addClass('glyphicon-floppy-disk');
				$('#pass-act-usr').removeAttr('required');
				$('#pkusr').val(pka);
				$('#usrname').val(pkb);
				$('#usremail').val(pkc);
				break;
			default:
				location.reload();
				break;
		}

	});

	$('#btn-exibir-senha').on('click', function(){
		var val = $('#pass-act-usr').attr('type');
		
		switch(val){
			case 'password':
				$('#pass-act-usr').attr('type', 'text');
				$('#icon-ver-senha').removeClass('glyphicon-eye-open').addClass('glyphicon-eye-close');
				break;
			default:
				$('#pass-act-usr').attr('type', 'password');
				$('#icon-ver-senha').removeClass('glyphicon-eye-close').addClass('glyphicon-eye-open');
				break;
		}
	});

});