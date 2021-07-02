<?php
	$this->breadcrumbs = AdminBreadcrumbs::get(array(Yii::t('admin_layout', 'Settings')));
	
	$this->pageTitle = Yii::t('admin_layout', 'Settings');

	$this->admin_subheader = array(
		array(
				'name' => Yii::t('admin_layout', 'Settings'),
				'url' => array('/katalogVavto/adminKatalog/admin'),
				'active' => true,
		),
		array(
				'name' => Yii::t('admin_layout', 'Brands'),
				'url' => array('/katalogVavto/adminBrands/admin'),
				'active' => false,
		),
		array(
				'name' => Yii::t('admin_layout', 'Model'),
				'url' => array('/katalogVavto/adminCars/admin'),
				'active' => false,
		),
		array(
				'name' =>Yii::t('admin_layout', 'Goods'),
				'url' => array('/katalogVavto/adminItems/admin'),
				'active' => false,
		),
		array(
				'name' => Yii::t('admin_layout', 'Export brands'),
				'url' => array('/katalogVavto/adminBrands/export'),
				'active' => false,
		),
		array(
				'name' => Yii::t('admin_layout', 'Import brands'),
				'url' => array('/katalogVavto/adminBrands/import'),
				'active' => false,
		),
		array(
				'name' => Yii::t('admin_layout', 'Exporting models'),
				'url' => array('/katalogVavto/adminCars/export'),
				'active' => false,
		),
		array(
				'name' => Yii::t('admin_layout', 'Import models'),
				'url' =>array('/katalogVavto/adminCars/import'),
				'active' => false,
		),
		array(
				'name' => Yii::t('admin_layout', 'Exports of goods'),
				'url' => array('/katalogVavto/adminItems/export'),
				'active' => false,
		),
		array(
				'name' => Yii::t('admin_layout', 'Imports of goods'),
				'url' => array('/katalogVavto/adminItems/import'),
				'active' => false,
		),
	);
?>
<h1><?php echo Yii::t('admin_layout', 'Settings'); ?></h1>
<?php
	$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
		'id' => 'katalog-vavto-settings-form',
		'enableAjaxValidation' => false,
		'type' => 'horizontal',
	));
	
	echo $form->errorSummary($model);
	
	$tabs = array(
	    array(
	        'label' => Yii::t('katalogVavto', 'Page'),
	        'content' => $this->renderPartial('_content', array('form' => $form, 'model' => $model), true),
	        'active' => true
	    ),
	);
	
	foreach ($model->langsList() as $row) {
	    $tabs[] = array(
	        'label' => $row['name'],
	        'content' => $this->renderPartial('application.views.adminLanguages._form_edit_languange', array('form' => $form, 'model' => $model->getTranslatedModel($row['link_name'], true), 'lang' => $row), true),
	    );
	}
	
	$this->widget('bootstrap.widgets.TbTabs', array(
	    'type' => 'tabs',
	    'tabs' => $tabs,
	));
?>
<div class="form-actions">
    <?php
    $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType' => 'submit',
        'type' => 'primary',
        'label' => $model->isNewRecord ? Yii::t('katalogVavto', 'Add') : Yii::t('katalogVavto', 'Save'),
    ));
    ?>
</div>
<?php $this->endWidget(); ?>