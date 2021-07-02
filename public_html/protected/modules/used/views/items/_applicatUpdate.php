<?php
$appModel = new UsedItemsUsage();
//$modification = UsedMod::model()->findByPk($mod);
//$apps = UsedMod::model()->findAllByAttributes(array('brand_id'=>$modification->brand_id));

/*echo CVarDumper::dump(CHtml::listData($model->usedItemsUsages, 'mod_id', 'mod_id'),10,true);
echo '<div class="row-fluid">';
echo CHtml::activeCheckBoxList(
	$appModel,
	'mod_id',
	$model->getApplicability($mod),
	array(
		'template'=>'<div class="appliacty" style="width: 33%;float: left;">{input} {label}</div>',
		'separator'=>false,
		'container'=>'',
	)
);
echo '</div>';*/
//echo $form->checkBoxRow($appModel, 'mod_id');
?>
<div class="row-fluid">
	<?php foreach ($model->usedItemsUsages as $k=>$us):?>
		<div class="appliacty" style="width: 33%;float: left;">
			<input id="UsedItemsUsage_mod_id_<?php echo $k;?>" type="checkbox" name="UsedItemsUsage[mod_id][]" value="<?php echo $us->mod_id;?>" checked="checked">
			<label for="UsedItemsUsage_mod_id_0"><?php echo $us->mod->brand->name;?> • <?php echo $us->mod->model->name;?> • <?php echo $us->mod->name;?></label>
		</div>
	<?php endforeach;?>
</div>
<div id="addition-modification"></div>
<div class="clearfix">
    <a class="btn btn-info" href="#" onclick="getOtherApp();return false;">Посмотреть все остальные</a>
</div>

<script>
    function getOtherApp() {
        $.get('/used/admin/brands', function (data) {
            $('#addition-modification').append(data);
        });
    }
</script>
