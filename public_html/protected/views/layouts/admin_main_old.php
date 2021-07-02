<?php
/* @var $this Controller */
if ($this->metaTitle != null && !empty($this->metaTitle))
    $this->pageTitle = $this->metaTitle;
?>
<!DOCTYPE html>
<html>
<head lang="ru">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="language" content="en"/>
    <?php if ($this->metaKeywords != null && !empty($this->metaKeywords)) { ?>
        <meta name="description" content="<?= htmlspecialchars($this->metaKeywords) ?>"/>
    <?php } ?>
    <?php if ($this->metaDescription != null && !empty($this->metaDescription)) { ?>
        <meta name="keywords" content="<?= htmlspecialchars($this->metaDescription) ?>"/>
    <?php } ?>
    <title><?php echo CHtml::encode($this->pageTitle); ?></title>

    <?php Yii::app()->bootstrap->register(); ?>
    <!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">-->
    <link rel="stylesheet" href="/fonts/fonts.css">
    <link rel="stylesheet" href="/css/admin_layout.css">
    <?php
    $cs = Yii::app()->getClientScript();
    $cs->registerScriptFile(Yii::app()->baseUrl . '/js/jquery-migrate.min.js');
    $cs->registerScriptFile(Yii::app()->baseUrl . '/js/jquery.reveal.js');
    $cs->registerScriptFile(Yii::app()->baseUrl . '/js/myYiiJs.js');
    $cs->registerCssFile(Yii::app()->baseUrl . '/css/reveal.css');
    $cs->registerCssFile(Yii::app()->baseUrl . '/css/admin.css');
    ?>
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
<?php $this->widget('ext.notifIt.NotifItWidget');?>
<div class="container b_page_justice">

    <div class="navbar">
        <div class="nav-header">
            <div
                class="b_log_out pull-right"><?= CHtml::link(Yii::t('admin_layout', 'Exit'), array('/lily/user/logout')) ?></div>
            <img class="logo" src="/images/admin_site/logo.png"/>

            <div class="header-title"><?= Yii::t('admin_layout', 'Website\'s admin control panel') ?> </div>
            <div class="b_name"><?= CHtml::link(Yii::app()->config->get('Site.SiteName'), array('/site/index')) ?></div>
            <div class="b_breadcrumbs">
                <?php if (isset($this->breadcrumbs)): ?>
                    <?php
                    $this->widget('bootstrap.widgets.TbBreadcrumbs', array(
                        'links' => $this->breadcrumbs,
                    ));
                    ?><!-- breadcrumbs -->
                <?php endif ?>
            </div>
        </div>
        <div class="nav-body">
            <div class="nav-elements">
                <?php
                $this->widget('bootstrap.widgets.TbMenu', array('encodeLabel' => false, 'items' => array(
//                                Yii::app()->getModule('userControl')->getAsUserBlock(),
                    array('label' => '<img alt="search" src="/images/admin_site/search.png"/><div class="nav-sign">' . Yii::t('admin_layout', 'Search Spare parts') . '</div>', 'url' => array('/adminDetailSearch/default/search', 'search_phrase' => ''), 'itemOptions' => array('class' => 'nav-element')),
                    array('label' => '<img alt="trash" src="/images/admin_site/trash.png"/><div class="nav-sign">' . Yii::t('admin_layout', 'Basket') . '</div>', 'url' => array('/shop_cart/adminShoppingCart/view'), 'itemOptions' => array('class' => 'nav-element')),
                    array('label' => '<img alt="trash" src="/images/admin_site/orders.png"/><div class="nav-sign">' . Yii::t('admin_layout', 'Orders') . '</div>', 'url' => array('/shop_cart/adminOrders/admin'), 'itemOptions' => array('class' => 'nav-element')),
                    array('label' => '<img alt="trash" src="/images/admin_site/vin.png"/><div class="nav-sign">' . Yii::t('admin_layout', 'Requests') . '</div>', 'url' => array('/requests/adminRequestGetPrice/admin'), 'itemOptions' => array('class' => 'nav-element')),
                    array('label' => '<img alt="trash" src="/images/admin_site/praisi.png"/><div class="nav-sign">' . Yii::t('admin_layout', 'Price-lists') . '</div>', 'url' => array('/prices/admin/admin'), 'itemOptions' => array('class' => 'nav-element')),
                    array('label' => '<img alt="trash" src="/images/admin_site/price.png"/><div class="nav-sign">' . Yii::t('admin_layout', 'Price politics') . '</div>', 'url' => array('/pricegroups/adminGroups/admin'), 'itemOptions' => array('class' => 'nav-element')),
                    array('label' => '<img alt="trash" src="/images/admin_site/clients.png"/><div class="nav-sign">' . Yii::t('admin_layout', 'Clients') . '</div>', 'url' => array('/userControl/adminUserProfile/admin'), 'itemOptions' => array('class' => 'nav-element')),
                    array('label' => '<img alt="trash" src="/images/admin_site/seo_new.png"/><div class="nav-sign">' . Yii::t('admin_layout', 'Site pages') . '</div>', 'url' => array('/pages_left/admin/index'), 'itemOptions' => array('class' => 'nav-element')),
                    array('label' => '<img alt="trash" src="/images/admin_site/seo.png"/><div class="nav-sign">' . Yii::t('admin_layout', 'Сatalogs') . '</div>', 'url' => array('/cronLogs/catalogs'), 'itemOptions' => array('class' => 'nav-element')),
                    array('label' => '<img alt="trash" src="/images/admin_site/settings.png"/><div class="nav-sign">' . Yii::t('admin_layout', 'Settings') . '</div>', 'url' => array('/config/admin/adminTotal'), 'itemOptions' => array('class' => 'nav-element')),
                )));
                ?>
            </div>
        </div>


    </div>

    <ul class="nav nav-pills pills-2">
        <?php
        foreach ($this->admin_header as $row) {
            echo '<li role="presentation" class="nav-btn pills-2-left ' . ($row['active'] ? 'active' : '') . '">' . CHtml::link(str_replace('{pageTitle}', $this->pageTitle, $row['name']), $row['url']) . '</li>';
        }
        ?>
    </ul>
    <?php if (count($this->admin_subheader) > 0) {

        $script = <<<SCRIPT
                                    $( "body" ).scrollTop( 160 );
SCRIPT;
        Yii::app()->clientScript->registerScript(__CLASS__ . "#scrollTo", $script, CClientScript::POS_END);
        ?>

        <ul class="nav nav-pills pills-2 b_second_level">
            <?php
            foreach ($this->admin_subheader as $row) {
                echo '<li role="presentation" class="nav-btn pills-2-left ' . ($row['active'] ? 'active' : '') . '">' . CHtml::link($row['name'], $row['url']) . '</li>';
            }
            ?>
        </ul>
    <?php } ?>
    <?= $content ?>
</div>
</body>
<div id="myModal" class="reveal-modal">
    <h1 id="modalTitle"></h1>

    <p id="modalText"></p>
    <a class="close-reveal-modal">&#215;</a>
</div>
<div id="myModalSubmit" class="reveal-modal">
    <h1 id="modalTitleSubmit"><?= Yii::t('admin_layout', 'Prepaid order') ?></h1>

    <p id="modalTextSubmit"><?= Yii::t('admin_layout', 'In your order, there are details which require prepayment, select prepaid option.') ?></p>

    <p id="modalCartButtonsSubmit">
        <?= CHtml::link(Yii::t('admin_layout', 'Full payment'), '#', array('class' => 'btn btn-primary pull-left', 'onclick' => '$(\'#prepaid_type_id\').attr(\'value\',1);$(\'#orders-form\').submit();return false;')) ?>
        <?= CHtml::link(Yii::t('admin_layout', 'Prepayment') . ' 30%', '#', array('class' => 'btn btn-primary pull-right', 'onclick' => '$(\'#prepaid_type_id\').attr(\'value\',0.3);$(\'#orders-form\').submit();return false;')) ?>
    </p>
    <a class="close-reveal-modal">&#215;</a>
</div>
<iframe name="_footer_iframe" style="display: none;"/>
<?php
$this->widget('application.extensions.juitip.EJuiTooltip', array(
        'selector' => '.tool-tip',
        'options' => array(),
    )
);
?>
</html>