<div class="view">
    <div class="span2 pull-left">
        <div>
            <b>     
                <?= $data->article ?>
            </b>
        </div>
    </div>
    <div class="span3 pull-left">
        <div>
            <b>     
                <?= CHtml::link($data->title, array('items/view', 'id' => $data->id)) ?>
            </b>
        </div>
    </div>
    <div class="span2 pull-left">
        <b>
            <?= Chtml::link(Yii::t('katalogVavto', 'Cost') . ' >>>', array('items/view', 'id' => $data->id), array('onclick' => 'Filter_search_page(\'' . $data->article . '\')', 'target' => '_blank')) ?>
        </b>
    </div>
    <div class="clear"></div>
</div>