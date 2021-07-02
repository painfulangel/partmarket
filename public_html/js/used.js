/**
 * Created by foreach on 28.11.16.
 */
$(function() {
    var input = $("#quick-search-group");
    var context = $("#category-treeview li");
    input.on("input", function() {
        var term = $(this).val();
        //console.log(term);
        context.show().unmark();
        if (term) {
            context.mark(term, {
                done: function(counter) {
                    console.log(counter);
                    context.not(":has(mark)").hide();
                    $('#category-treeview > li').removeClass('expandable').addClass('collapsable');
                    $('#category-treeview > li > ul').show();
                    if(counter){
                        $('.add-control-unit').hide();
                    }
                },
                noMatch: function (term) {
                    console.log('not match');
                    $('.add-control-unit').show();
                },
                log: window.console
            });
        }
    });
});



/*_______  Поиск по артикулу, наименованию _________*/
$(function () {
    var minlen = 3; // минимальная длина слова
    var keyint = 500; // интервал между нажатиями клавиш
    var term = ''; //ключевое слово

    //Удаление обработчика события.
    $(document).off('input propertychange', '#quick_search_icon_search');

    //вешаем обработчик на инпут поиска
    $('#quick-search-group-a').on('input propertychange', function()
    {
        //дата
        var d1 = new Date();
        //время нажатия клавиши
        time_keyup = d1.getTime();

        // проверяем, изменилась ли строка
        if ($('#quick-search-group-a').val() != term) {
            // проверяем длину строки
            if ($('#quick-search-group-a').val().length>=minlen) {
                //если меньше 3 символов, ждем следующего нажатия
                setTimeout(function(){
                    var d2 = new Date();
                    time_search = d2.getTime();
                    // проверяем интервал между нажатиями
                    if (time_search-time_keyup>=keyint) {
                        // если все в порядке, приступаем к поиску
                        dosearch();
                    }
                }, keyint);
            } else {
                //открываем кнопку поиска
                $('#quick_search_icon_search').removeClass('hidden');
                //скрываем кнопку очищения
                $('#quick_search_icon_remove').addClass('hidden');
                //Отключаем кнопку поиск, что бы исключить повторные нажатия
                $('.btn-quick-search-action').prop('disabled', true).data('type', 'search');
            }
        }
        //Если инпут пустой, меняем иконки на кнопке
        if ($.trim($('#quick-search-group-a').val()).length == 0) {

            $('#quick_search_icon_search').removeClass('hidden');
            $('#quick_search_icon_remove').addClass('hidden');
            $('.btn-quick-search-action').prop('disabled', true).data('type', 'search');

        }
    });

    //Обработка клика на кнопке поиска
    $('.btn-quick-search-action').click(function(event) {
        //получаем дата атрибут type
        data = $(this).data('type');
        //Если тип кнопки remove, меняем иконки и очищаем поиск
        if ('remove' == data) {

            $('#quick_search_icon_search').removeClass('hidden');
            $('#quick_search_icon_remove').addClass('hidden');
            $('.btn-quick-search-action').prop('disabled', true).data('type', 'search');

            $('#quick-search-group-a').val('');

        }

    });

});

/**
 * Выполнение поиска
 */
function dosearch(){
    //Получить значение из инпута
    term = $('#quick-search-group-a').val();
    //поменять иконки
    $('#quick_search_icon_search').addClass('hidden');
    $('#quick_search_icon_remove').removeClass('hidden');
    //Отключить кнопку и установить тип remove
    $('.btn-quick-search-action').prop('disabled', false).data('type', 'remove');
    
    //Проверка, что символы введены в инпут
    if( term.length > 0) {
        $("#quick_search_icon_search").addClass('hidden');
        $("#quick_search_icon_remove").removeClass('hidden');
    }
    //получить url страницы поиска
    var urlSearch = $('#quick-search-group-a').data('urlSearch');
    console.log(urlSearch);

    //еще какой то url
    var url = $('#quick-search-group-a').data('url');
    console.log(url);

    //что за хуйня?
    var data = $('#quick-search-group-a').data();
    console.log(data);

    //удаляем зачем то из массива url
    delete(data.url);
    //и url писка
    delete(data.urlSearch);
    //добавляем в массив элемент q который содержит поисковую строку
    data['q'] = term;

    //Что за дерьмо?
    if ($('#quick-search-group-a').data('group') !== 'undefined') {
        data['group'] = $('#quick-search-group-a').data('group')
    }

    //для валидации формы
    data['YII_CSRF_TOKEN'] = $('#csrf-tok').val();

    //Непосредственно сам запрос
    $.ajax({
            url: urlSearch,
            type: 'post',
            dataType: 'json',
            data: data
        }).done(function(response) {
            //вывод в консоль, что получили от сервера
            console.log(response.grops);
            $('#items-list-view').html('');
            var arr = response.grops;
            arr.forEach(function(item, i, arr){
                $('#items-list-view').append(item);
            });

        }).fail(function() {
            console.log("error");
        });
}



