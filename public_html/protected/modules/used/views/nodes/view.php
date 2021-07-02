<?php
/* @var $this NodesController */
/* @var $model UsedNodes */

$this->breadcrumbs=array(
	'Used Nodes'=>array('index'),
	$model->name,
);
?>

<h3><?php echo $model->name;?></h3>

<div class="row-fluid">
	<?php foreach ($items as $item):?>
		<div class="item-<?php echo $item->id;?>">
			<a href="/used/items/update/id/<?php echo $item->id;?>" target="_blank">
			<div class="parts-item_box">
				<div class="boxheader row-fluid">
					<div class="span10">
						<!--<a href="#">-->
						<?php echo $item->name;?>
						<!--</a>-->
						<?php /*echo CHtml::ajaxLink(
							$text = $item->name,
							$url = '/used/items/viewAjax/id/'.$item->id,
							$ajaxOptions=array (
								'type'=>'GET',
								'dataType'=>'html',
								'success'=>'function(html){ jQuery("#items-list-view").html(html); }'
							),
							$htmlOptions=array ('id'=>'it-'.uniqid())
						);*/?>
					</div>
					<div class="span2 text-right">
						<!--<a href="/used/items/update/id/<?php /*echo $item->id;*/?>"><i class="icon-edit"></i></a>
						--><?php /*echo CHtml::ajaxLink(
							$text = '<i class="icon-remove"></i>',
							$url = '/used/items/deleteAjax/id/'.$item->id,
							$ajaxOptions=array (
								'type'=>'GET',
								'dataType'=>'html',
								'success'=>'function(html){if(html==1){jQuery(".item-'.$item->id.'").remove();} }'
							),
							$htmlOptions=array ('id'=>'it-'.uniqid())
						);*/?>
					</div>
				</div>
				<div class="row-fluid">
					<div class="item-img span3">
						<?php /*$this->widget('ext.jquery_fancybox.FancyboxWidget', array(
							'id'=>uniqid(),
							'items'=>$item->imagesItemsForWidget()
						));*/?>
						<img src="<?php echo $item->getFrontImage();?>" style="min-width: 100%;">
					</div>
					<div class="span9">
						<span class="item-line_info">Производитель: <?php echo $item->brandItem->name;?></span>
						<span class="item-line_info">Состояние: <?php echo $item->getState();?></span>
						<span class="item-line_info">Наличие: <?php echo $item->availability;?></span>
						<span class="item-line_info">Тип: <?php echo $item->getType();?></span>
						<span class="item-line_info">Внутренний номер: <?php echo $item->vendor_code;?></span>
						<span class="item-line_info">Номер ЗЧ: <?php echo $item->original_num;?></span>
					</div>
				</div>
				<div class="row-fluid">
					<div class="span12">
						<p class="pull-right"><?php echo $item->price;?></p>
					</div>
				</div>
			</div>
			</a>
		</div>
	<?php endforeach;?>
</div>

