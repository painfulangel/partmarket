<?php
$this->breadcrumbs = AdminBreadcrumbs::get(array(Yii::t('universal', 'Universal catalog')));

$this->pageTitle = Yii::t('universal', 'Universal catalog');
?>
<h1><?php echo Yii::t('universal', 'Universal catalog'); ?></h1>
<?php /**/ ?>
<div class="btn-toolbar">
    <?php echo CHtml::link(Yii::t('universal', 'Create universal catalog section'), array('create'), array('class' => 'btn')); ?>
</div>
<?php
/**/
$this->widget('bootstrap.widgets.TbGridView', array(
    'id' => 'page-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'columns' => array(
        array(
            'name' => 'id',
            'headerHtmlOptions' => array(
                'width' => 50,
            ),
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'name',
            'htmlOptions' => array('style' => 'text-align: center;'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'alias',
            'htmlOptions' => array('style' => 'text-align: center;'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
        ),
    	array(
    		'class' => 'ext.jtogglecolumn.JToggleColumn',
    		'name' => 'active_state',
    		'filter' => array('0' => Yii::t('universal', 'No'), '1' => Yii::t('universal', 'Yes')),
    		'checkedButtonLabel' => Yii::t('universal', 'Disable'),
    		'uncheckedButtonLabel' => Yii::t('universal', 'Enable'),
    		'headerHtmlOptions' => array('style' => 'text-align: center;'),
    		'htmlOptions' => array('style' => 'text-align: center;'),
    	),
        array(
            'class' => 'bootstrap.widgets.TbButtonColumn',
            'template' => '{chars} {products} {update} {delete}',
        	'buttons' => array(
        		'chars' => array(
        			'url' => 'Yii::app()->createUrl("/universal/adminChars/admin", array("id" => $data["id"]))',	
        			//'options' => array('target' => '_blank'),
        			'label' => Yii::t('universal', 'Chars'),
        			'imageUrl' => '/images/admin_icons/icon-list.png',
        		),
        		'products' => array(
        			'url' => 'Yii::app()->createUrl("/universal/adminProducts/admin", array("id" => $data["id"]))',	
        			//'options' => array('target' => '_blank'),
        			'label' => Yii::t('universal', 'Products'),
        			'imageUrl' => '/images/admin_icons/icon-product.png',
        		),
        	),
        ),
    ),
))
?>