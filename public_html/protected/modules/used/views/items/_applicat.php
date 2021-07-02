<?php
$appModel = new UsedItemsUsage();
//$modification = UsedMod::model()->findByPk($mod);
//$apps = UsedMod::model()->findAllByAttributes(array('brand_id'=>$modification->brand_id));

//echo CVarDumper::dump(CHtml::listData($apps, 'id', 'name'),10,true);
echo '<div class="row-fluid">';
if(isset($mod)){
	echo CHtml::activeCheckBoxList(
		$appModel,
		'mod_id',
		$model->getApplicability($mod),
		array(
			'template'=>'<div class="appliacty" style="width: 49%;float: left;">{input} {label}</div>',
			'separator'=>false,
			'container'=>'',
		)
	);
}

echo '</div>';
//echo $form->checkBoxRow($appModel, 'mod_id');
?>
<div id="addition-modification"></div>
<div class="clearfix">
    <a class="btn btn-info" href="#" onclick="getOtherApp();return false;">Посмотреть все остальные</a>
</div>

<script>
    function getOtherApp() {
        $.get('/used/admin/brands', function (data) {
            //$('#addition-modification').html(data);
			$('#addition-modification').append(data);
        });
    }
</script>
