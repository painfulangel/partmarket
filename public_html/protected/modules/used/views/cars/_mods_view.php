<?php
/**
 * Created by PhpStorm.
 * User: debian
 * Date: 20.02.17
 * Time: 15:54
 */
?>
<a class="span6 link-model-wrap" href="<?php echo Yii::app()->createUrl('/used/cars/modification', array('brand'=>$model->brand->slug, 'model'=>$data->model->slug, 'modification'=>$data->slug));?>" style="display: inline-block; margin-bottom: 20px;">
    <span class="item-model span12">
        <span class="pull-left item-model-img">
            <img alt="<?php echo $data->name;?>" src="<?php echo $data->getImageUrl();?>" style="width:160px;height:120px;">
        </span>
        <span class="item-model-info" style="text-transform: uppercase; margin-left: 10px;"> <?php echo $data->name;?> </span>
    </span>
</a>

<?php $c = $index+1;?>
<?php if($c%2 == 0):?>
</div>
<div class="row-fluid clear">
<?php endif;?>
