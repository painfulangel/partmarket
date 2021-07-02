<?php
	$this->metaDescription = 'SEO-каталог';
	$this->metaKeywords = 'SEO-каталог';
	$this->pageTitle = 'SEO-каталог';
	
	if (is_object($seo)) echo $seo->seo1;
?>
<ul class="select-car">
<?php
	$count = count($brands);
	for ($i = 0; $i < $count; $i ++) {
		$model = $brands[$i];
?>
<li>
	<div class="display-table">
		<div class="table-cell" style="height: 78px;">
			<div class="title">
				<?php echo CHtml::link($model->title, array('/katalogVavto/brands/view', 'id' => $model->primaryKey)); ?>
			</div>
	
			<div class="blockimg">
				
<?php
					echo CHtml::image('/'.$model->getAttachment(), $model->short_text);
?>
				
			</div>
		</div>
	
		<div class="table-cell" style="height: 78px;">
			<ul class="car-models">
<?php
			$count2 = count($model->katalogVavtoCars);
			for ($j = 0; $j < $count2; $j ++) {
?>
				<li><?php echo CHtml::link($model->katalogVavtoCars[$j]->title, array('/katalogVavto/cars/view', 'id' => $model->katalogVavtoCars[$j]->primaryKey)); ?></li>
<?php
			}
?>
			</ul>
		</div>
	</div>
</li>
<?php
}
?>
</ul>
<?php
	if (is_object($seo)) echo $seo->seo2;
	
	if (count($items)) {
?>
<div class="items">
	<h3><?php echo Yii::t('katalogVavto', 'Available spares'); ?></h3>
<?php
		foreach ($items as $array) {
?>
	<div class="brandname"><?php echo CHtml::link($array['cath']->brand->title.' '.$array['cath']->title, array('/katalogVavto/cars/view', 'id' => $array['cath']->primaryKey)); ?></div>
	<table class="items table">
		<tr>
			<th><?php echo Yii::t('katalogVavto', 'Producer'); ?></th>
			<th><?php echo Yii::t('katalogVavto', 'Article'); ?></th>
			<th><?php echo Yii::t('katalogVavto', 'Name'); ?></th>
			<th><?php echo Yii::t('katalogVavto', 'Cost'); ?></th>
			<th></th>
		</tr>
<?php
			$count = count($array['items']);
			for ($i = 0; $i < $count; $i ++) {
				$a = $array['items'][$i];
?>
		<tr>
			<td><?php echo $array['cath']->brand->title; ?></td>
			<td><?php echo $a->article; ?></td>
			<td><?php echo CHtml::link($a->title, array('/katalogVavto/items/view', 'id' => $a->id)) ?></td>
			<td><?php echo CHtml::link(Yii::t('katalogVavto', 'Learn price'), array('/requests/requestGetPrice/create', 'detail' => $a->article), array('target' => '_blank')); ?></td>
			<td><?php echo CHtml::link('<div class="incart"><span></span></div>', array('/requests/requestGetPrice/create', 'detail' => $a->article), array('target' => '_blank', 'title' => Yii::t('katalogVavto', 'Learn price'))); ?></td>
		</tr>
<?php
			}
?>
	</table>
<?php
			echo Chtml::link(CHtml::button(Yii::t('katalogVavto', 'View all'), array('class' => 'btn btn-success')), array('/katalogVavto/cars/view', 'id' => $array['cath']->primaryKey), array('class' => 'viewall'));
		}
?>
</div>
<?php
	}
?>