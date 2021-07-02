$(function() {
    console.log($('a.choose_city').length);
    $('a.choose_city').click(function() {
        console.log(123);
        var href = '/city/choose/';

        $.fancybox.open({
            href : href,
            type : "iframe",
            padding : 15,
            width: 600,
            height: 400,
            maxHeight: 400
        });
            
        return false;
    });

    $('a.city').click(function() {
        var city = $(this).attr('rel');

        $.cookie('city', city, { expires: 365, path: '/' });

        $.post('/city/set/' + city + '/', { YII_CSRF_TOKEN: $(':hidden[name=YII_CSRF_TOKEN]').val() }, function(data) {
          if (typeof(data.name) != 'undefined') parent.document.getElementById('city_name').innerHTML = data.name;
          if (typeof(data.phone) != 'undefined') parent.document.getElementById('city_phone').innerHTML = '<a href="Tel:' + data.phone + '">' + data.phone + '</a>';
          if (typeof(data.email) != 'undefined') parent.document.getElementById('city_mail').innerHTML = '<a href="mailto:' + data.email + '">' + data.email + '</a>';

          parent.window.location.reload();
        }, "json");

        parent.jQuery.fancybox.close();
    });
});