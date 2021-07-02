<?php echo $form->checkBoxRow($model, 'set');?>
	
	<div id="forms-sets"></div>

<div class="row-fluid">
	<div class="span8">
		<?php foreach($model->itemSets as $set):?>
			<div class="parts-item_box">
				<div class="boxheader row-fluid">
					<div class="span10"><a href="#"><?php echo $set->name;?></a></div>
					<div class="span2 text-right">
						<a target="_blank" href="/used/itemSets/update/id/<?php echo $set->id;?>"><i class="icon-edit"></i></a>
						<a href="/used/itemSets/delete/id/<?php echo $set->id;?>" data-method="POST"><i class="icon-remove"></i></a>
					</div>
				</div>
				<div class="row-fluid">
					<div class="item-img span3">

					</div>
					<div class="span9">
						<span class="item-line_info">Производитель: <?php echo $set->brandItem->name;?></span>
						<!--<span class="item-line_info">Состояние: <?php /*//echo $set->getState();*/?></span>-->
						<span class="item-line_info">Наличие: <?php echo $set->availability;?></span>
						<!--<span class="item-line_info">Тип: <?php /*//echo $set->getType();*/?></span>-->
					</div>
				</div>
				<div class="row-fluid">
					<div class="span12">
						<p class="pull-right"><?php echo $set->price;?></p>
					</div>
				</div>
			</div>
		<?php endforeach;?>
	</div>
</div>

