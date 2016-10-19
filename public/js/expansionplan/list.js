$(function() {

	$('.btn-apagar').on('click', function(){
		var pk = $(this).attr('pk');
		var name = $(this).attr('title')+"?";

		swal({
			title: "Você tem certeza?",
			text: name,
			type: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#C9302C',
			cancelButtonColor: '#31B0D5',
			confirmButtonText: 'Sim'
		}).then(function() {
			$('#pkdelplan').val(pk);
			$('#remove-form').submit();
		})
	});

	$('.btn-editar').on('click', function(){
		$('#btn-adicionar').hide();
		$('#grp-btn-edicao').show();

		var pk = $(this).attr('pk');
		var title = $('.title-'+pk).text();
		var startdate = $('.startdate-'+pk).attr('originalval');
		var enddate = $('.enddate-'+pk).attr('originalval');
		var goal = $('.goal-'+pk).text();

		$('#id_pe').val(pk);
		$('#title').val(title);
		$('#start_date').val(startdate);
		$('#end_date').val(enddate);
		$('#general_goal_units').val(goal);

	});

	$('#btn-cancelar-edicao').on('click', function(){
		$('#id_pe').val(0);
		$('#btn-adicionar').show();
		$('#grp-btn-edicao').hide();
	});

});