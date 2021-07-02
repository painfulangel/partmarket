$(function() {
	$('div.volume a').click(function() {
		if (!$(this).hasClass('active')) {
			$('div.volume a.active').removeClass('active');
			$(this).addClass('active');
			
			$('a.fancybox').hide();
			$('a[rel=' + $(this).attr('rel') + ']').show();
		}
	});
});