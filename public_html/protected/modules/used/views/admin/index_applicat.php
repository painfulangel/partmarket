<?php
/* @var $this DefaultController */
?>
<div class="container-fluid">
    <div class="row-fluid">
        <div class="span122">
            <?php $this->widget('zii.widgets.CListView', array(
                'id'=>'brand-list-view',
                'dataProvider'=>$model,
                'itemView'=>'_view_applicat',
                'summaryText'=>false
            )); ?>

        </div>
    </div>

    <div style="height: 60px;"></div>

</div>
<div class="clearfix"></div>