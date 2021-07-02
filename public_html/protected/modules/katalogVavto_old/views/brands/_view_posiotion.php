<div class="item">
    <?php if (!empty($data->image)) { ?>
        <div class="pic"><?= CHtml::link(CHtml::image('/' . $data->getAttachment()), array('items/view', 'id' => $data->id), array('onclick' => 'Filter_search_page(\'' . $data->article . '\');return false;', 'target' => '_blank')) ?></div>
    <?php } ?>
    <h2><?= CHtml::link($data->title, array('items/view', 'id' => $data->id), array('onclick' => 'Filter_search_page(\'' . $data->article . '\');return false;', 'target' => '_blank')) ?><?= $data->sub_title ?></h2>
    <div class="txt"><?= $data->text ?></div>
    <div class="more"><span><?= Yii::t('katalogVavto', 'Article') ?>: <?= $data->article ?> </span><?= CHtml::link(Yii::t('katalogVavto', 'All offers'), array(), array('class' => 'btn  btn-more', 'onclick' => 'Filter_search_page(\'' . $data->article . '\');return false;')) ?></div>
</div>