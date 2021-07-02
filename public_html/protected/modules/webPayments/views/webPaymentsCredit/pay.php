<html><head>
        <!--[if lt IE 9]><script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta name="language" content="en">

        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php echo Yii::t('webPayments', 'Order payment'); ?></title>
    </head>

    <body><?php
        $this->breadcrumbs = array(
            Yii::t('webPayments', 'Order payment'),
        );

        $this->pageTitle = Yii::t('webPayments', 'Order payment');
        ?>

        <h1><?php echo Yii::t('webPayments', 'Wait, You will redirect to payment system...') ?></h1>

        <?php echo $this->renderPartial('_form_redirect', array('model' => $model, 'order' => $order)); ?>
    </body>
</html>