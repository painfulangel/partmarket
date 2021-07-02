<?php
/**
 * Created by PhpStorm.
 * User: debian
 * Date: 30.01.17
 * Time: 18:19
 */
?>
<hr>
<div class="row-fluid">
    <?php foreach ($models as $k=>$model):?>
        <div class="appliacty" style="width: 49%;float: left;">
            <input id="UsedItemsUsage_mod_id_0<?php echo $k;?>" type="checkbox" name="UsedItemsUsage[mod_id][]" value="<?php echo $model->id;?>">
            <label for="UsedItemsUsage_mod_id_0<?php echo $k;?>"><?php echo $model->name;?> • <?php echo $model->model->name;?> • <?php echo $model->brand->name;?></label>
        </div>
    <?php endforeach;?>
</div>

