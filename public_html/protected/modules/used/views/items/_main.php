<?php

?>
<?php echo $form->dropDownListRow(
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
	); ?>
		
	<?php echo $form->dropDownListRow(
			$model,
			'model_id',
			$model->isNewRecord?array():CHtml::listData(UsedModels::model()->findAllByAttributes(array('brand_id'=>$model->brand_id)), 'id','name'),
			array(
				'prompt'=>'Выберите модель',
				'ajax' => array(
					'type'=>'POST', //request type
					'url'=>CController::createUrl('/used/items/listMod'),
					'update'=>'#UsedItems_mod_id', //selector to update
					//'data'=>'js:javascript statement' 
				)
			)
	); ?>
		
	<?php echo $form->dropDownListRow(
			$model,
			'mod_id',
			$model->isNewRecord?array():CHtml::listData(UsedMod::model()->findAllByAttributes(array('model_id'=>$model->model_id)), 'id','name'),
			array(
				'prompt'=>'Выберите модификацию',
			)
	); ?>
		
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
		
	<?php echo $form->dropDownListRow(
			$model,
			'unit_id',
			$model->isNewRecord?array():CHtml::listData(UsedUnits::model()->findAllByAttributes(array('node_id'=>$model->node_id)), 'id','name'),
			array(
				'prompt'=>'Выберите агрегат',
			)
	);?>
	
	<?php echo $form->dropDownListRow(
			$model,
			'brand_item_id',
			CHtml::listData(UsedBrandsItems::model()->findAll(), 'id','name'),
			array(
				'prompt'=>'Выберите производителя запчасти',
			)
	);?>
	
	<?php /*$this->widget('ext.typeahead.TbTypeAhead',array(
		'model' => $model,
		'attribute' => 'unit_id',
		'enableHogan' => true,
		'options' => array(
			 array(
				'name' => 'accounts',
				'local' => array(
					'jquery',
					'ajax',
					'bootstrap'
				),
				//'name' => 'countries',
				//'valueKey' => 'name',
				//'remote' => array(
					//'url' => Yii::app()->createUrl('/ajax/countryLists') . '?term=%QUERY',
				//),
				//'template' => '<p>{{name}}<strong>{{code}}</strong> - {{id}}</p>',
				//'engine' => new CJavaScriptExpression('Hogan'),
			)
		),
		//'events' => array(
			//'selected' => new CJavascriptExpression("function(obj, datum, name) {
				//console.log(obj);
				//console.log(datum);
				//console.log(name);
         //}")
		//),
	)); */?>

	<?php echo $form->textFieldRow($model,'name'); ?>
	
	<?php echo $form->textFieldRow($model,'vendor_code',array('size'=>60,'maxlength'=>255)); ?>
		
	<?php echo $form->textFieldRow($model,'original_num',array('size'=>60,'maxlength'=>255)); ?>
		
	<?php echo $form->textFieldRow($model,'replacement',array('size'=>60,'maxlength'=>255)); ?>
		
	<?php echo $form->radioButtonListRow($model,'type', $model->getTypes()); ?>
		
	<?php echo $form->dropDownListRow($model,'state', $model->getStates()); ?>
	
	<?php echo $form->textFieldRow($model,'price',array('size'=>8,'maxlength'=>8)); ?>
		
	<?php echo $form->textFieldRow($model,'delivery_time'); ?>
		
	<?php echo $form->textFieldRow($model,'availability'); ?>
	