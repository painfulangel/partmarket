<?php ?>

<div class="span3 view-katalog-avto-cathegory">
    <div class="view-katalog-avto-header">
        <?php
        if (!empty($data->image)) {
            echo CHtml::image('/' . $data->getAttachment(), $data->short_title);
        }
        echo CHtml::tag('h4', array(), $data->short_title);
        ?>
    </div>
    <div class="clear"></div>
    <?php
    $childs = $data->children()->findAll('active_state=1');

    if (count($childs) > 0) {
        $dataProvider = new CArrayDataProvider('User');
        $dataProvider->setData($childs);

        $this->widget('bootstrap.widgets.TbListView', array(
            'dataProvider' => $dataProvider,
            'itemView' => '_view_line',
            'template' => '{items} {pager}',
            'id' => 'katalog-vavto-index',
        ));
    }
    ?>
</div>