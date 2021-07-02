<?php
$this->breadcrumbs = AdminBreadcrumbs::get(array(Yii::t('prices', 'Prices')));

$this->pageTitle = Yii::t('prices', 'Prices');

$this->admin_subheader = array(
    array(
        'name' => Yii::t('prices', 'Prices'),
        'url' => array('/prices/admin/admin'),
        'active' => true,
    ),
    array(
        'name' => Yii::t('admin_layout', 'Auto Price list'),
        'url' => array('/prices/adminAutoloadRules/admin'),
        'active' => false,
    ),
    array(
        'name' => Yii::t('admin_layout', 'Mailboxes'),
        'url' => array('/prices/adminMailboxes/admin'),
        'active' => false,
    ),
    array(
        'name' => Yii::t('admin_layout', 'Export price lists'),
        'url' => array('/prices/adminPricesExportRules/admin'),
        'active' => false,
    ),
    array(
        'name' => Yii::t('admin_layout', 'Load price list'),
        'url' => array('/prices/admin/create'),
        'active' => false,
    ),
);
?>
<h1><?= Yii::t('prices', 'Prices') ?></h1>
<?php
$this->widget('bootstrap.widgets.TbGridView', array(
    'id' => 'prices-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'columns' => array(
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'type' => 'raw',
            'name' => 'name',
            'value' => '"<input type=\'text\' value=\'{$data->name}\' class=\'new_name\' name=\'{$data->id}\' />"'
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'type' => 'raw',
            'name' => 'original_article',
            'value' => '"<input type=\'text\' value=\'{$data->original_article}\' class=\'new_article\' name=\'{$data->id}\'  />"'
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'type' => 'raw',
            'name' => 'brand',
            'value' => '"<input type=\'text\' value=\'{$data->brand}\' class=\'new_brand\' name=\'{$data->id}\'  />"'
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'type' => 'raw',
            'name' => 'price',
            'value' => '"<input type=\'text\' value=\'{$data->price}\' class=\'new_price\' name=\'{$data->id}\'  />"'
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'type' => 'raw',
            'name' => 'quantum',
            'value' => '"<input type=\'text\' value=\'{$data->quantum}\' class=\'new_quantum\' name=\'{$data->id}\'  />"'
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'type' => 'raw',
            'name' => 'delivery',
            'value' => '"<input type=\'text\' value=\'{$data->delivery}\' class=\'new_delivery\' name=\'{$data->id}\'  />"'
        ),
        array(
            'class' => 'bootstrap.widgets.TbButtonColumn',
            'template' => '{delete}',
            'buttons' => array(
                'delete' => array(
                    'url' => 'array("deletePrice","id" => $data->id)',
                ),
            ),
        ),
    ),
));
?>

<div class="row buttons">
    <input type="button" value="<?= Yii::t('prices', 'Save') ?>" id='save-new-prices' class="btn btn-primary" />
    <img src="/images/loading.gif" style='display: none' id = "ajax-busy"/>
</div>

<?php
$url = $this->createUrl('priceSave');
$csrfName = Yii::app()->request->csrfTokenName;
$csrfToken = Yii::app()->request->csrfToken;
$text_success = Yii::t('prices', 'The data was successfully saved');
$text_name = Yii::t('prices', 'Prices');
$text_error = Yii::t('prices', 'An error has occurred. Perhaps You are not logged in.');

Yii::app()->clientScript->registerScript('priceSave', <<<EOP
    /*
    Collects data from /cross/admin/crossTable and sends it to server to update prices
     */
    $('#save-new-prices').click(function(){
        var prices = new Array, name;
        var name = {}, article = {}, brand = {}, price = {}, quantum = {}, delivery = {};
        $('.new_name').each(function(){
            prices.push("name[" + $(this).attr('name') + "]=" + $(this).attr('value'));
            name[$(this).attr('name')] = $(this).attr('value')
        });
        $('.new_article').each(function(){
            prices.push("article[" + $(this).attr('name') + "]=" + $(this).attr('value'));
            article[$(this).attr('name')] = $(this).attr('value')
        });
        $('.new_brand').each(function(){
            prices.push("brand[" + $(this).attr('name') + "]=" + $(this).attr('value'));
            brand[$(this).attr('name')] = $(this).attr('value')
        });
        
        $('.new_price').each(function(){
            prices.push("price[" + $(this).attr('name') + "]=" + $(this).attr('value'));
            price[$(this).attr('name')] = $(this).attr('value')
        });
        $('.new_quantum').each(function(){
            prices.push("quantum[" + $(this).attr('name') + "]=" + $(this).attr('value'));
            quantum[$(this).attr('name')] = $(this).attr('value')
        });
        $('.new_delivery').each(function(){
            prices.push("delivery[" + $(this).attr('name') + "]=" + $(this).attr('value'));
            delivery[$(this).attr('name')] = $(this).attr('value')
        });
        
        $('#save-new-prices').addClass('disabled');
        $.ajax({
            type: "POST",
            url: "$url",
            cache: false,
            data:
            {
                 name:name, 
                 article:article, 
                 brand:brand, 
                 price:price, 
                 quantum:quantum, 
                 delivery:delivery, 
                $csrfName : "$csrfToken",
            },
            dataType: "json",
            timeout: 5000,
            beforeSend : function(){
                $('#ajax-busy').show();
            },
            complete : function(){
                $('#ajax-busy').hide();
            },
            success: function (data) {
                ShowWindow('$text_name','$text_success');
                $('#save-new-prices').removeClass('disabled');
            },
            error: function() {
                alert('$text_error');
            }
        });
    });
EOP
);
?>