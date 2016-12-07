$(document).ready(function(){

	$('.cng-status-btn').on('click', function(){
		var tit = $(this).html();
		var pk = $(this).attr('pk');
		$('#cng-status-title').html(tit);
		$('#new_forced_status').val(pk);
	});

	

});