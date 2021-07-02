<?php
/**
 * Created by PhpStorm.
 * User: debian
 * Date: 22.03.17
 * Time: 20:20
 */
?>
<?php $this->widget('zii.widgets.CListView', array(
    'id'=>'items-list-view',
    'dataProvider'=>$dataProvider,
    'itemView'=>'_item_view',
    'summaryText'=>false,
    'itemsCssClass'=>'items',
    //'viewData'=>array('model'=>$mod),
)); ?>
