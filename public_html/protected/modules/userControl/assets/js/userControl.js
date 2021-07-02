$(function() {
	$('button.add_detail').click(function() {
		$('div.add_detail').toggle();
		$(this).hide();
	});
});

function updateDetail(obj) {
	var id = obj.attr('href');

	$.post('/userControl/usersCars/getDetail/', { id: id, YII_CSRF_TOKEN : $(':hidden[name=YII_CSRF_TOKEN]').val() }, function( data ) {
    	if (typeof(data.name) != 'undefined') {
    		$('#users-cars-detail-form').find(':hidden[name=id]').val(data.id);

    		$('#UsersCarsDetails_brand').val(data.brand);
    		$('#UsersCarsDetails_article').val(data.article);
    		$('#UsersCarsDetails_name').val(data.name);

    		$('button.add_detail').click();
    	}
    }, "json");
}