<?php
$this->pageTitle = Yii::t('adminDetailSearch', 'Search spare parts');

$this->breadcrumbs = array(Yii::t('adminDetailSearch', 'Search spare parts'));
?>

<div id="admin_content">
    <?php echo Yii::t('adminDetailSearch', 'Please wait. Searching.') ?>
    <script type="text/javascript">
        $().ready(function () {
            Filter_search_page('<?= $search_phrase ?>');
        })
    </script>
</div>