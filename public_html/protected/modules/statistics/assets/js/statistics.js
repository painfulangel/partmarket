$(function() {
	$('.btn-export-all-managers').click(function() {
		var form = $('#statisticsForm');
		
		var data = form.serializeArray();
		
	    $.post('/statistics/admin/checkManagerForm/', data, function(data) {
	    	if (data.error != '') {
		        alert(data.error);
	    	} else {
	    		form.attr('action', '/statistics/admin/exportAllManagers/');
				form.submit();
	    	}
	    }, "json");
	});
	
	$('.btn-export-sales-all-managers').click(function() {
		var form = $('#statisticsForm');

		var data = form.serializeArray();
		
	    $.post('/statistics/admin/checkManagerForm/', data, function(data) {
	    	if (data.error != '') {
		        alert(data.error);
	    	} else {
	    		form.attr('action', '/statistics/admin/exportSalesAllManagers/');
	    		form.submit();
	    	}
	    }, "json");
	});
});