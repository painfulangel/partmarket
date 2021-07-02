<?php
class LastNewsWidget extends CWidget {
    public function init() {
        // этот метод будет вызван внутри CBaseController::beginWidget()
    }

    public function run() {
    	if (strpos(Yii::app()->getRequest()->getUrl(), '/news/') === false) {
	        echo '<div id="newsList">
	        <p><i class="icon-bullhorn"></i> ' . Yii::t('news', 'NEWS') . '</p>';
	        $datas = News::model()->findAllByAttributes(array('active_state' => '1'), array(
	            'order' => 'create_time desc',
	            'limit' => '2',
	        ));
	        foreach ($datas as $data) {
	            echo '<div class = "post">
	        <p class = "date">' . date('d.m.Y', $data->create_time) . '</p>
	        <p>' . $data->short_text . '</p>
	        <div class="pull-right">' . CHtml::link(Yii::t('news', 'in detail..'), array('/news/default/view', 'link' => $data->link)) . '</div>
	              </div>';
	        }
	
	        echo ' <div class="to_more"><div class="btn btn-info btn-small"><i class="icon-file icon-white"></i> <a href="' . Yii::app()->createUrl('/news/default/index') . '">' . Yii::t('news', 'News archive') . '</a></div></div>
	     </div>';
    	}
    }
}