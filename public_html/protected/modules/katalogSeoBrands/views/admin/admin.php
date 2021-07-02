<?php
	$this->breadcrumbs = AdminBreadcrumbs::get(array(Yii::t('admin_layout', 'Settings')));
	
	$this->pageTitle = Yii::t('admin_layout', 'Settings');

	$this->admin_subheader = array(
		array(
			'name' => Yii::t('admin_layout', 'Settings'),
			'url' => array('/katalogSeoBrands/admin/admin'),
			'active' => true,
		),
	    array(
	        'name' => Yii::t('katalogSeoBrands', 'Brands'),
	        'url' => array('/katalogSeoBrands/admin/brands'),
	        'active' => false,
	    ),
	);
?>
<h1><?php echo Yii::t('admin_layout', 'Settings'); ?></h1>
<?php
	echo CHtml::button(Yii::t('katalogSeoBrands', 'Clear all'), array('class' => 'btn btn-danger btn-clear-all', 'onclick' => 'clearAll();'));

	$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
		'id' => 'katalog-vavto-settings-form',
		'enableAjaxValidation' => false,
		'type' => 'horizontal',
	));
	
	echo $form->errorSummary($model);

	echo $form->checkBoxRow($model, 'index_active');

	echo $form->checkBoxRow($model, 'show_widget');

	echo $form->textFieldRow($model, 'cron_count', array('class' => 'span5', 'maxlength' => 255));
	
	echo $form->dropDownListRow($model, 'pricegroup', $pricesrules, array('class' => 'span5'));

	echo $form->textFieldRow($model, 'url', array('class' => 'span5', 'maxlength' => 255));
?>
<div class="control-group">
	<label class="control-label required" for="KatalogSeoBrandsSettings_url"><?php echo Yii::t('katalogSeoBrands', 'Stores list'); ?> <span class="required">*</span></label>
	<div class="controls">
		<select name="stores[]" class="stores" multiple="multiple">
<?php
		$count = count($stores);
		for ($i = 0; $i < $count; $i ++) {
			$selected = in_array($stores[$i]->primaryKey, $active_store) ? ' selected' : '';
			echo '<option value="'.$stores[$i]->primaryKey.'"'.$selected.'>'.$stores[$i]->name.'</option>'."\n";
		}
?>
		</select>
	</div>
</div>
<h3><?php echo Yii::t('katalogSeoBrands', 'Main page settings'); ?></h3>
<?php
	echo $form->textFieldRow($model, 'index_title', array('class' => 'span5', 'maxlength' => 255));
	echo $form->textAreaRow($model, 'index_keywords', array('class' => 'span5', 'maxlength' => 255));
	echo $form->textAreaRow($model, 'index_description', array('class' => 'span5', 'maxlength' => 255));
	echo $form->textFieldRow($model, 'index_h1', array('class' => 'span5', 'maxlength' => 255));

		//!!! Виджет, выводящий редактор нужного типа
	$this->widget('application.extensions.redactor.widgets.Redactor', array('form' => $form, 'model' => $model, 'attribute' => 'index_text'));
	//!!! Виджет, выводящий редактор нужного типа
?>
<h3><?php echo Yii::t('katalogSeoBrands', 'Brand page settings'); ?></h3>
<p>
	<?php echo Yii::t('katalogSeoBrands', 'You can use the following characters, which will be replaced when output to the page:'); ?><br>
	<b>@brand@</b> - <?php echo Yii::t('katalogSeoBrands', 'Brand'); ?>
</p>
<?php
	echo $form->textFieldRow($model, 'brand_title', array('class' => 'span5', 'maxlength' => 255));
	echo $form->textAreaRow($model, 'brand_keywords', array('class' => 'span5', 'maxlength' => 255));
	echo $form->textAreaRow($model, 'brand_description', array('class' => 'span5', 'maxlength' => 255));
	echo $form->textFieldRow($model, 'brand_h1', array('class' => 'span5', 'maxlength' => 255));
	//echo $form->textFieldRow($model, 'brand_buy', array('class' => 'span5', 'maxlength' => 255));

	//!!! Виджет, выводящий редактор нужного типа
	$this->widget('application.extensions.redactor.widgets.Redactor', array('form' => $form, 'model' => $model, 'attribute' => 'brand_text'));
	//!!! Виджет, выводящий редактор нужного типа
?>
<h3><?php echo Yii::t('katalogSeoBrands', 'Category page settings'); ?></h3>
<p>
	<?php echo Yii::t('katalogSeoBrands', 'You can use the following characters, which will be replaced when output to the page:'); ?><br>
	<b>@brand@</b> - <?php echo Yii::t('katalogSeoBrands', 'Brand'); ?>
	<b>@category@</b> - <?php echo Yii::t('katalogSeoBrands', 'Category'); ?>
</p>
<?php
	echo $form->textFieldRow($model, 'category_title', array('class' => 'span5', 'maxlength' => 255));
	echo $form->textAreaRow($model, 'category_keywords', array('class' => 'span5', 'maxlength' => 255));
	echo $form->textAreaRow($model, 'category_description', array('class' => 'span5', 'maxlength' => 255));
	echo $form->textFieldRow($model, 'category_h1', array('class' => 'span5', 'maxlength' => 255));
	echo $form->textFieldRow($model, 'category_buy', array('class' => 'span5', 'maxlength' => 255));

		//!!! Виджет, выводящий редактор нужного типа
	$this->widget('application.extensions.redactor.widgets.Redactor', array('form' => $form, 'model' => $model, 'attribute' => 'category_text'));
	//!!! Виджет, выводящий редактор нужного типа
?>
<h3><?php echo Yii::t('katalogSeoBrands', 'Article page settings'); ?></h3>
<p>
	<?php echo Yii::t('katalogSeoBrands', 'You can use the following characters, which will be replaced when output to the page:'); ?><br>
	<b>@article@</b> - <?php echo Yii::t('katalogSeoBrands', 'Article'); ?><br>
	<b>@brand@</b> - <?php echo Yii::t('katalogSeoBrands', 'Brand'); ?><br>
	<b>@description@</b> - <?php echo Yii::t('katalogSeoBrands', 'Name'); ?><br>
	<b>@price@</b> - <?php echo Yii::t('katalogSeoBrands', 'Price'); ?>
</p>
<?php
	echo $form->textFieldRow($model, 'article_title', array('class' => 'span5', 'maxlength' => 255));
	echo $form->textAreaRow($model, 'article_keywords', array('class' => 'span5', 'maxlength' => 255));
	echo $form->textAreaRow($model, 'article_description', array('class' => 'span5', 'maxlength' => 255));
	echo $form->textFieldRow($model, 'article_h1', array('class' => 'span5', 'maxlength' => 255));
	echo $form->textFieldRow($model, 'article_buy', array('class' => 'span5', 'maxlength' => 255));

	$this->widget('application.extensions.redactor.widgets.Redactor', array('form' => $form, 'model' => $model, 'attribute' => 'article_content'));

	//!!! Виджет, выводящий редактор нужного типа
	$this->widget('application.extensions.redactor.widgets.Redactor', array('form' => $form, 'model' => $model, 'attribute' => 'article_text'));
	//!!! Виджет, выводящий редактор нужного типа
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
<script>
	function clearAll() {
		var btn = $('.btn-clear-all');

		if (confirm("<?php echo Yii::t('katalogSeoBrands', 'Are you shure remove data'); ?>")) {
			btn.attr('disabled', 'disabled');

		    $.post("/katalogSeoBrands/admin/clearall/", { '<?php echo Yii::app()->request->csrfTokenName; ?>': '<?php echo Yii::app()->request->csrfToken; ?>' }, function(data) {
		    	alert("<?php echo Yii::t('katalogSeoBrands', 'Data was removed'); ?>");
		    }, "json");
		}
	}
</script>
<style>
	.form-horizontal .control-label {
		text-align: left;
		width: 180px;
	}

	.stores {
		height: 150px !important;
		width: 300px;
	}
</style>