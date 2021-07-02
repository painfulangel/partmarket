<?php
	$this->pageTitle = Yii::t('detailSearch', 'Search of spare parts');
	
	$this->breadcrumbs = array(Yii::t('detailSearch', 'Search of spare parts'));
	
	$this->widget('ext.jquery_fancybox.FancyboxWidget');
	
	echo Yii::t('detailSearch', 'Please, wait. There is a search.');
?>
<script type="text/javascript">
$().ready(function () {
    Filter_search_page('<?php echo $search_phrase; ?>');
})</script>