<div class="b_news_item post">
    <h3><?php echo ($data->short_title); ?></h3>
    <p class="date"><?= date('d.m.Y', $data->create_time) ?></p>
    <p><?php echo ($data->short_text); ?></p>
    <p  class="pull-right"><?= CHtml::link(Yii::t('news', 'Go to news'), array('view', 'link' => $data->link), array()) ?></p>
</div>
