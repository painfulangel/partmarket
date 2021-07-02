<?php
	$this->breadcrumbs = AdminBreadcrumbs::get(array(Yii::t('prices', 'Search meta-tags')));
	
	$this->pageTitle = Yii::t('prices', 'Search meta-tags');
?>
<h1><?php echo Yii::t('prices', 'Search meta-tags'); ?></h1>
<div class="btn-toolbar">
    <?= CHtml::link(Yii::t('prices', 'Create'), array('create'), array('class' => 'btn')) ?>
</div>
<?php
$this->widget('bootstrap.widgets.TbGridView', array(
    'id' => 'pricesdatameta-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'columns' => array(
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'id',
            'htmlOptions' => array('style' => 'text-align: center;'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'brand',
            'htmlOptions' => array('style' => 'text-align: center;'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'article',
            'htmlOptions' => array('style' => 'text-align: center;'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'meta_title',
            'htmlOptions' => array('style' => 'text-align: center;'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'ext.AttachmentBehavior.TbEImageColumn',
            'name' => 'image',
            'sortable' => true,
            'filter' => array('1' => Yii::t('katalogVavto', 'Yes'), '2' => Yii::t('katalogVavto', 'No')),
            'noFileFound' => '/images/nofoto.png',
            'htmlOptions' => array('style' => 'max-height: 60px; !important; margin: 5px;'),
            'headerHtmlOptions' => array('style' => 'text-align:center; vertical-align;'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbButtonColumn',
            'template' => '{update} {delete}',
            /*'buttons' => array(
                'update' => array(
                    'url' => 'array(\'update\', \'id\' => $data->id, \'Prices_page\' => (isset($_GET[\'Prices_page\']) ? $_GET[\'Prices_page\'] : \'\'))',
                ),
            ),*/
            'htmlOptions' => array('style' => 'width: 90px;'),
        ),
    ),
));
?>