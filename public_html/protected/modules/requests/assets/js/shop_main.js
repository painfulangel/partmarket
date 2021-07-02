var ShopCartAddNewItemState = false;
var ShopCartMergeState = false;
var ShopCartMergeBlock = '';
function ShopCartAddNewItem() {
    if (ShopCartAddNewItemState) {
        ShopCartAddNewItemState = !ShopCartAddNewItemState;
        $('#quick_item_form').hide();
    } else {
        ShopCartAddNewItemState = !ShopCartAddNewItemState;
        $('#quick_item_form').show();
    }
}

function ShopCartMergeOrders() {
    if (ShopCartMergeState) {
        ShopCartMergeState = !ShopCartMergeState;
        $('#merge_form').hide();
    } else {
        ShopCartMergeState = !ShopCartMergeState;
        $('#merge_form').show();
    }
}

function ShopCartMergeAddField() {
    if (ShopCartMergeBlock == '')
        ShopCartMergeInitBlock();
    temp = document.createElement('div');
    temp.innerHTML = ShopCartMergeBlock;
    document.getElementById('merge_form_fields').appendChild(temp);
}

function ShopCartMergeInitBlock() {
    ShopCartMergeBlock = document.getElementById('merge_form_fields').innerHTML;
}

function ShopCartMergeRun() {
    document.getElementById('merge-form').submit();
    //   document.getElementById('merge_form_fields').innerHTML = ShopCartMergeBlock;
}

function ShopCartMergeCheck() {
    var data = {
        merge_id: {},
        id: 0,
        YII_CSRF_TOKEN: "",
    };
    var price = new Array;
    i = 0;
    $('#merge_form input').each(function() {
        if ($(this).attr('name') == 'YII_CSRF_TOKEN')
            data.YII_CSRF_TOKEN = $(this).attr('value');
        else if ($(this).attr('name') == 'merge_id[]') {
            price.push("data.merge_id[" + i + "]=" + $(this).attr('value'));
            data.merge_id[i] = $(this).attr('value');
            i++;
        }
        else
            data.id = $(this).attr('value');
    });
    $.ajax({
        type: "POST",
        url: "/shop_cart/adminOrders/mergeOrdersCheck",
        cache: false,
        data: data,
        dataType: "json",
        timeout: 5000,
        success: function(data) {
            if (data.is_not_payed) {
                if (window.confirm("Объединение заказа приведёт к удалению присоединяемых заказов. Все позиции будут перенесены в текущий заказ. Продолжить?")) {
                    if (data.is_not_one_owner) {
                        if (window.confirm("Один или несколько присоединяемых заказов от разных клиентов, продолжить?")) {
                            ShopCartMergeRun();
                        }
                    } else {
                        ShopCartMergeRun();
                    }
                }
            } else {
                alert('Присоединение невозможно, есть оплаченные заказы.');
            }
        },
        error: function() {
            alert('Произошла ошибка. Возможно Вы не авторизованы.');
        }
    });
}

function ShopCartPriceAllChange() {
    $('#Items_price_all').val($('#Items_quantum').val() * $('#Items_price').val());
}

function ShopChangePrintOrder() {
    $('.print_clear').each(function() {
        $(this).hide();
    });
    $('.preprint').each(function() {
        $(this).parent().append($(this).attr('value'));
        $(this).hide();
    });
    window.print();
}

function ShopCartDeleteTrash(url) {
    if (!confirm("Вы точно желаете удалить все товары в корзине?")) {
        return false;
    }
    $.ajax({
        type: 'POST',
        url: url,
        data: ShopCartCSRF,
        cache: false,
        success: function(data) {
            $.fn.yiiGridView.update('Orders-grid');
            ShowWindow('Очистка корзины', 'Данные успешно удалены');
        },
    });
}