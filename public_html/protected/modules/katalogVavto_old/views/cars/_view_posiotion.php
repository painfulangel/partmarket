<div class="item">
	<div class="pic">
    <?php if (!empty($data->image)) {
    	echo CHtml::link(CHtml::image('/'.$data->getAttachment(), $data->short_text), array('/katalogVavto/items/view', 'id' => $data->id), array());
    } else {
    	echo CHtml::link(CHtml::image('/images/KatalogVavtoItems/4.png', $data->short_text), array('/katalogVavto/items/view', 'id' => $data->id), array());
    }
    ?>
    </div>
    <h2>
    	<?php echo CHtml::link($data->title, array('/katalogVavto/items/view', 'id' => $data->id), array()) ?> <?php echo $data->sub_title ?>
    </h2>
    <div class="txt"><?php echo $data->text ?></div>
    <div class="more"><span><?php echo Yii::t('katalogVavto', 'Article') ?>: <?php echo $data->article ?> </span><?php echo CHtml::link(Yii::t('katalogVavto', 'All offers'), (Yii::app()->config->get('Site.SearchType') == 1 ? array('/detailSearchNew/default/search', 'article' => $data->article) : array('/detailSearch/default/search', 'search_phrase' => $data->article)), array('class' => 'btn  btn-more')) ?></div>
</div>