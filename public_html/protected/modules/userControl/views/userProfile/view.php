<?php
$this->breadcrumbs = array(
    Yii::t('userControl', 'View Profile'),
);
$this->pageTitle = Yii::t('userControl', 'View Profile');
?>

<h1><?= Yii::t('userControl', 'View Profile') ?></h1>

<?php
if ($model->legal_entity == 1) {
    $this->widget('bootstrap.widgets.TbDetailView', array(
        'data' => $model,
        'attributes' => array(
            'email',
            'first_name',
            'second_name',
            'father_name',
            'phone',
            'extra_phone',
            'skype',
            //'balance',
            'organization_name',
            'organization_type',
            'organization_inn',
            'organization_ogrn',
            'okpo',
            'bank_kpp',
            'bank_bik',
            'bank',
            'bank_rc',
            'bank_ks',
            'organization_director',
            'delivery_zipcode',
            'delivery_city',
            'delivery_country',
            'delivery_street',
            'delivery_house',
            'comment',
            'legal_zipcode',
            'legal_city',
            'legal_country',
            'legal_street',
            'legal_house',
        ),
    ));
} else if ($model->legal_entity == 2) {
    $this->widget('bootstrap.widgets.TbDetailView', array(
        'data' => $model,
        'attributes' => array(
            'email',
            'first_name',
            'second_name',
            'father_name',
            'phone',
            'extra_phone',
            'skype',
            //'balance',
            'organization_name',
            'organization_inn',
            'ogrnip',
            'okpo',
            'bank_kpp',
            'bank_bik',
            'bank',
            'bank_rc',
            'bank_ks',
//            'organization_director',
            'delivery_zipcode',
            'delivery_city',
            'delivery_country',
            'delivery_street',
            'delivery_house',
            'comment',
            'legal_zipcode',
            'legal_city',
            'legal_country',
            'legal_street',
            'legal_house',
        ),
    ));
} else {

    $this->widget('bootstrap.widgets.TbDetailView', array(
        'data' => $model,
        'attributes' => array(
            'email',
            'first_name',
            'second_name',
            'father_name',
            'phone',
            'extra_phone',
            'skype',
//            'balance',
            'delivery_zipcode',
            'delivery_city',
            'delivery_country',
            'delivery_street',
            'delivery_house',
            'comment',
        ),
    ));
}
?>
