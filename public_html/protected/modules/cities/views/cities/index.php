<h3><?php echo Yii::t('cities', 'Cities'); ?></h3>
<?php
	$count = count($model);
	for ($i = 0; $i < $count; $i ++) {
		echo '<a class="city" rel="'.$model[$i]->primaryKey.'">'.$model[$i]->name.'</a>';
	}
?>
<input type="hidden" name="YII_CSRF_TOKEN" value="<?php echo Yii::app()->request->csrfToken; ?>">