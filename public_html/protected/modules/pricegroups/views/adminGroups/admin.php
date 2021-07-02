<?php
$this->breadcrumbs = AdminBreadcrumbs::get(array(Yii::t('pricegroups', 'Editing price range')));

$this->pageTitle = Yii::t('pricegroups', 'Editing price range');

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('prices-rules-groups-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1><?= Yii::t('pricegroups', 'Editing price range') ?></h1>
<div class="btn-toolbar">
    <?= CHtml::link(Yii::t('pricegroups', 'Create'), array('create'), array('class' => 'btn')) ?>

</div>
<?php
$this->widget('bootstrap.widgets.TbGridView', array(
    'id' => 'prices-rules-groups-grid',
    'dataProvider' => $model->search(),
    'columns' => array(
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'id',
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
            'htmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'name',
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
            'htmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbButtonColumn',
            'template' => '{view} {update} {delete}',
            'buttons' => array(
                'view' => array(
                    'url' => 'array(\'adminRules/admin\', \'id\' => $data->id, \'PricesRulesGroups_page\' => (isset($_GET[\'PricesRulesGroups_page\']) ? $_GET[\'PricesRulesGroups_page\'] : \'\'))',
                    'label' => Yii::t('pricegroups', 'Settings rules'),
                    'icon' => 'pencil',
                ),
                'update' => array(
                    'url' => 'array(\'update\', \'id\' => $data->id, \'PricesRulesGroups_page\' => (isset($_GET[\'PricesRulesGroups_page\']) ? $_GET[\'PricesRulesGroups_page\'] : \'\'))',
                    'label' => Yii::t('pricegroups', 'Change the name of the rule'),
                    'icon' => 'rename',
                ),
            ),
        ),
    ),
));
?>
