<?php

?>
<?php echo $form->hiddenField($model, 'brand_id', array('value'=>$modification->brand->id));?>
<?php echo $form->hiddenField($model, 'model_id', array('value'=>$modification->model->id));?>
<?php echo $form->hiddenField($model, 'mod_id', array('value'=>$modification->id));?>
<?php /*echo $form->dropDownListRow(
			$model,
			'brand_id',
			CHtml::listData(UsedBrands::model()->findAll(), 'id','name'),
			array(
				'prompt'=>'Выберите бренд',
				'ajax' => array(
					'type'=>'POST', //request type
					'url'=>CController::createUrl('/used/items/listModels'),
					'update'=>'#UsedItems_model_id', //selector to update
					//'data'=>'js:javascript statement' 
				)
			)
	);*/ ?>
		
	<?php /*echo $form->dropDownListRow(
			$model,
			'model_id',
			$model->isNewRecord?array():CHtml::listData(UsedBrands::model()->findAllByAttributes(array('id'=>$model->brand_id)), 'id','name'),
			array(
				'prompt'=>'Выберите модель',
				'ajax' => array(
					'type'=>'POST', //request type
					'url'=>CController::createUrl('/used/items/listMod'),
					'update'=>'#UsedItems_mod_id', //selector to update
					//'data'=>'js:javascript statement' 
				)
			)
	);*/ ?>
		
	<?php /*echo $form->dropDownListRow(
			$model,
			'mod_id',
			$model->isNewRecord?array():CHtml::listData(UsedMod::model()->findAllByAttributes(array('model_id'=>$model->model_id)), 'id','name'),
			array(
				'prompt'=>'Выберите модификацию',
			)
	);*/ ?>

	<?php if(!$node):?>
	<?php echo $form->dropDownListRow(
			$model,
			'node_id',
			CHtml::listData(UsedNodes::model()->findAll(), 'id','name'),
			array(
				'prompt'=>'Выберите узел',
				'ajax' => array(
					'type'=>'POST', //request type
					'url'=>CController::createUrl('/used/items/listNodes'),
					'update'=>'#UsedItems_unit_id', //selector to update
					//'data'=>'js:javascript statement' 
				)
			)
	); ?>
	<?php else:?>
		<?php echo $form->hiddenField($model, 'node_id', array('value'=>$node));?>
	<?php endif;?>


	<?php if(!$unit):?>
		<?php if($model->unit_id && $model->node_id):?>
			<?php echo $form->dropDownListRow(
				$model,
				'unit_id',
				CHtml::listData(UsedUnits::model()->findAllByAttributes(array('node_id'=>$model->node_id)), 'id','name'),
				array(
					'prompt'=>'Выберите агрегат',
				)
			);?>
		<?php else:?>
			<?php echo $form->dropDownListRow(
					$model,
					'unit_id',
					$model->isNewRecord?((!$node)?array():CHtml::listData(UsedUnits::model()->findAllByAttributes(array('node_id'=>$node)), 'id','name')):CHtml::listData(UsedUnits::model()->findAllByAttributes(array('node_id'=>$model->node_id)), 'id','name'),
					array(
						'prompt'=>'Выберите агрегат',
					)
			);?>
		<?php endif;?>
	<?php else:?>
		<?php echo $form->hiddenField($model, 'unit_id', array('value'=>$unit));?>
	<?php endif;?>

<div class="control-group ">
	<?php echo $form->labelEx($model, 'brand_item_id', array('class'=>'control-label'));?>
	<div class="controls">
	<!-- Автокомплит выбора производителя -->
	<?php $this->widget('ext.yii-selectize.YiiSelectize', array(
		'cssTheme'=>'bootstrap3',
		//'model' => $model,
		//'attribute' => 'brand_item_id',
		'name'=>'brand_item',
		'value'=>'',
		'fullWidth'=>false,
		'options'=>array(
			'valueField'=>'id',
			'labelField'=>'text',
			'searchField'=>['text'],
			'create'=>true,
			'placeholder'=>'Введите название'
		),

		'data' => array(
		),
		'callbacks' => array(

			'load'=>"function(query, callback) {
				if (!query.length) return callback();
				$.ajax({
					url: '/used/brandsItems/list',
					type: 'GET',
					dataType: 'json',
					data: {
						q: query,
					},
					error: function() {
						callback();
					},
					success: function(res) {
						callback(res.results);
					}
				});
			}",
			'onChange' => 'myOnChangeTest',
			'onOptionAdd' => 'newTagAdded',
		),
	));?>

	<?php echo $form->hiddenField($model, 'brand_item_id');?>
		<?php echo $form->error($model, 'brand_item_id');?>
	</div>
</div>

	<?php echo $form->textFieldRow($model,'name'); ?>
	
	<?php echo $form->textFieldRow($model,'vendor_code',array('size'=>60,'maxlength'=>255)); ?>
		
	<?php echo $form->textFieldRow($model,'original_num',array('size'=>60,'maxlength'=>255)); ?>
		
	<?php echo $form->textFieldRow($model,'replacement',array('size'=>60,'maxlength'=>255)); ?>
		
	<?php echo $form->radioButtonListRow($model,'type', $model->getTypes()); ?>
		
	<?php echo $form->dropDownListRow($model,'state', $model->getStates()); ?>
	
	<?php echo $form->textFieldRow($model,'price',array('size'=>8,'maxlength'=>8)); ?>
		
	<?php echo $form->textFieldRow($model,'delivery_time'); ?>
		
	<?php echo $form->textFieldRow($model,'availability'); ?>

<?php
$csrfTokenName = Yii::app()->request->csrfTokenName;
$csrfToken = Yii::app()->request->csrfToken;

?>
<script>
	function myOnChangeTest(value) {
		//console.log('select:'+value);
		$('#UsedItems_brand_item_id').val(value);
	}

	function newTagAdded(value, $item) {
		if(isNaN(value)){
			$.post('/used/brandsItems/createAjax', {
				'UsedBrandsItems[name]':value, <?php echo $csrfTokenName;?>:'<?php echo $csrfToken;?>'
			}, function (data) {
				//console.log(data);
				$('#UsedItems_brand_item_id').val(data);
			});
		}
		//console.log(isNaN(value));
		//console.log('new item: ' + value);
		//console.log($item);
	}
</script>