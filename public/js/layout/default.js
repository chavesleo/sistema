$(function(){
	$('.btn-exibir-senha').on('click', function(){
		var pk = $(this).attr('pk');
		var val = $('#pass-act-usr-'+pk).attr('type');
		
		switch(val){
			case 'password':
				$('#pass-act-usr-'+pk).attr('type', 'text');
				$('#icon-ver-senha-'+pk).removeClass('glyphicon-eye-open').addClass('glyphicon-eye-close');
				break;
			default:
				$('#pass-act-usr-'+pk).attr('type', 'password');
				$('#icon-ver-senha-'+pk).removeClass('glyphicon-eye-close').addClass('glyphicon-eye-open');
				break;
		}
	});
});