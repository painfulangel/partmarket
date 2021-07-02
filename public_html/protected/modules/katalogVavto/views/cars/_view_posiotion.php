<div class="item">
	<div class="pic">
<?php
    if ($data->image) {
        $img = CHtml::image('/'.$data->getAttachment(), $data->short_text);
    } else {
        $img = CHtml::image('/images/KatalogVavtoItems/4.png', $data->short_text);
    }

    $pi = Yii::app()->request->getPathInfo();
    
    $pi = preg_replace('/subtype-[^\/]+/', '', $pi);
    if (substr($pi, -1) == '/') $pi = substr($pi, 0, mb_strlen($pi) - 1);

    $pi = preg_replace('/type-[^\/]+/', '', $pi);
    if (substr($pi, -1) == '/') $pi = substr($pi, 0, mb_strlen($pi) - 1);

    $href = '/'.$pi.'/'.$data->slug.'/';
    //$href = Yii::app()->createUrl('/katalogVavto/items/view', array('id' => $data->id));
    
    //echo CHtml::link($img, array('/katalogVavto/items/view', 'id' => $data->id), array());
?>
        <a href="<?php echo $href; ?>"><?php echo $img; ?></a>
    </div>
    <h2>
    	<?php //echo CHtml::link($data->title, array('/katalogVavto/items/view', 'id' => $data->id), array()) ?><a href="<?php echo $href; ?>"><?php echo $data->title; ?></a><?php echo $data->sub_title ?>
    </h2>
    <div class="txt"><?php echo $data->text ?></div>
    <div class="more"><span><?php echo Yii::t('katalogVavto', 'Article') ?>: <?php echo $data->article ?> </span><?php echo CHtml::link(Yii::t('katalogVavto', 'All offers'), (Yii::app()->config->get('Site.SearchType') == 1 ? array('/detailSearchNew/default/search', 'article' => $data->article) : array('/detailSearch/default/search', 'search_phrase' => $data->article)), array('class' => 'btn  btn-more')) ?></div>
</div>