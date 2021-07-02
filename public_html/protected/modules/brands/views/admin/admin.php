<?php
$this->breadcrumbs = AdminBreadcrumbs::get(array(Yii::t('brands', 'Brands')));

$this->pageTitle = Yii::t('brands', 'Brands');
?>
<h1><?php echo Yii::t('brands', 'Brands'); ?></h1>
<div class="btn-toolbar">
    <?php echo CHtml::link(Yii::t('brands', 'Create'), array('create'), array('class' => 'btn')); ?>
</div>
<?php
$this->widget('bootstrap.widgets.TbGridView', array(
    'id' => 'brands-grid',
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
            'name' => 'name',
            'htmlOptions' => array('style' => 'text-align: center;'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'ext.AttachmentBehavior.TbEImageColumn',
            'name' => 'image',
            'sortable' => true,
            'filter' => array('1' => Yii::t('brands', 'Yes'), '2' => Yii::t('brands', 'No')),
            'noFileFound' => '/images/nofoto.png',
            'htmlOptions' => array('style' => 'max-height: 60px; !important; margin: 5px;'),
            'headerHtmlOptions' => array('style' => 'text-align:center; vertical-align;'),
        ),
        array(
            'class' => 'ext.jtogglecolumn.JToggleColumn',
            'name' => 'hide',
            'filter' => array('0' => Yii::t('brands', 'No'), '1' => Yii::t('brands', 'Yes')),
            'checkedButtonLabel' => Yii::t('brands', 'Deactivate'),
            'uncheckedButtonLabel' => Yii::t('brands', 'Activate'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
            'htmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'ext.jtogglecolumn.JToggleColumn',
            'name' => 'active_state',
            'filter' => array('0' => Yii::t('brands', 'No'), '1' => Yii::t('brands', 'Yes')),
            'checkedButtonLabel' => Yii::t('brands', 'Deactivate'),
            'uncheckedButtonLabel' => Yii::t('brands', 'Activate'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
            'htmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbButtonColumn',
            'template' => '{update} {delete}',
            'htmlOptions' => array('style' => 'width: 90px;'),
        ),
    ),
));