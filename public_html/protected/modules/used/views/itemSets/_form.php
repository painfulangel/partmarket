<?php
/* @var $this ItemSetsController */
/* @var $model UsedItemSets */
/* @var $form CActiveForm */
?>
<?php
$csrfTokenName = Yii::app()->request->csrfTokenName;
$csrfToken = Yii::app()->request->csrfToken;

?>

<div class="form" id="set-form-<?php echo $index;?>">

	<div class="control-group ">
		<?php echo CHtml::activeLabelEx($model, '['.$index.']brand_item_id', array('class'=>'control-label'));?>
		<div class="controls">
			<!-- Автокомплит выбора производителя -->
			<?php $this->widget('ext.yii-selectize.YiiSelectize', array(
				'cssTheme'=>'bootstrap3',
				//'model' => $model,
				//'attribute' => 'brand_item_id',
				'name'=>'brand_item_'.$index,
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
			<script>
				function myOnChangeTest(value) {
					//console.log('select:'+value);
					$('#UsedItemSets_<?php echo $index;?>_brand_item_id').val(value);
				}

				function newTagAdded(value, $item) {
					if(isNaN(value)){
						$.post('/used/brandsItems/createAjax', {
							'UsedBrandsItems[name]':value, <?php echo $csrfTokenName;?>:'<?php echo $csrfToken;?>'
					}, function (data) {
							//console.log(data);
							$('#UsedItemSets_<?php echo $index;?>_brand_item_id').val(data);
						});
					}
					//console.log(isNaN(value));
					//console.log('new item: ' + value);
					//console.log($item);
				}
			</script>

			<?php echo CHtml::activeHiddenField($model, '['.$index.']brand_item_id');?>
			<?php echo CHtml::error($model, '['.$index.']brand_item_id');?>
		</div>
	</div>

	<?php //echo TbHtml::activeDropDownListControlGroup($model,'['.$index.']brand_item_id', $model->getBrandsItem(), array('prompt'=>'Выберите производителя')); ?>
	
		<?php echo TbHtml::activeTextFieldControlGroup($model,'['.$index.']name', array('required'=>'required')); ?>

		<?php echo TbHtml::activeTextFieldControlGroup($model,'['.$index.']vendor_code',array('size'=>60,'maxlength'=>255)); ?>
		
		<?php //echo TbHtml::activeTextFieldControlGroup($model,'['.$index.']original_num',array('size'=>60,'maxlength'=>255)); ?>
		
		<?php echo TbHtml::activeTextFieldControlGroup($model,'['.$index.']replacement',array('size'=>60,'maxlength'=>255)); ?>
		
		<?php //echo TbHtml::activeRadioButtonListControlGroup($model,'type', $items->getTypes()); ?>
		
		<?php //echo TbHtml::activeDropDownListControlGroup($model,'state', $items->getStates()); ?>
		
		<?php echo TbHtml::activeTextAreaControlGroup($model,'['.$index.']comment',array('rows'=>6, 'cols'=>50)); ?>
		
		<?php echo TbHtml::activeTextFieldControlGroup($model,'['.$index.']price',array('size'=>8,'maxlength'=>8)); ?>

		<?php echo TbHtml::activeTextFieldControlGroup($model,'['.$index.']delivery_time'); ?>
		
		<?php echo TbHtml::activeTextFieldControlGroup($model,'['.$index.']availability'); ?>

</div><!-- form -->


<div class="control-group buttons">
		<?php //echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
		<a class="add-set" href="#" onclick="return false;">Добавить деталь в комплект</a>
	</div>

<script>
	$('.add-set').click(function(){
		$.get('/used/itemSets/create?index=<?php echo $index+1;?>', function(data){
			$('#forms-sets').append(data);
		});
	});
</script>