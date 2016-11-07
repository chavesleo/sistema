$(function() {

	$('.btnaddquestion').on('click', function(){

		var pk = $(this).attr('pk');
		var rating = $('#rating-'+pk).val();

		$('#pkquestion').val(pk);
		$('#rating').val(rating);
		$('#addqform').submit();

	});

	$(".rating-input").maskMoney({
		showSymbol:false,
		decimal:","
	});

});