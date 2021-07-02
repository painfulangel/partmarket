<?php
//$this->pageTitle = $model->title;
//$this->metaTitle = $model->meta_title;
//$this->metaDescription = $model->meta_description;
//$this->metaKeywords = $model->meta_keywords;


$this->pageTitle = $model->title;
$this->metaTitle = $model->meta_title;
$this->metaDescription = $model->meta_description;
$this->metaKeywords = $model->meta_keywords;
$this->breadcrumbs = $model->breadcrumbs;
?>




<div class="b-brend-about">

    <div class="brend-desc">
        <?php
        if (!empty($model->image)) {
            echo '<div class="pic">';
            echo CHtml::image('/' . $model->getAttachment(), $model->short_text
                    , array('width' => '185px')
            );
            echo '</div>';
        }
        ?>

        <div class="txt">

           <h1>Выбирите модель <?= $model->title ?>: </h1>
            <ul>
                <?php
                $childs = KatalogVavtoCars::model()->getCarTypes();
//                $childs = array_flip($childs);

//                array(
//                    'Седаны и купе' => 'char1',
//                    'Хэтчбеки и универсалы' => 'char2',
//                    'Кроссоверы и внедорожники' => 'char3',
//                    'Коммерческие автомобили' => 'char4',
//                );
                $childs_id = array();
                $type = Yii::app()->request->getParam('type', '');
                foreach ($childs as $key => $child) {
                    
                }
                ?>
            </ul>
        </div>
    </div>
</div>
<br>
<br>
<style>
    .empty{
        margin-left: 20px;
    }
</style>
<?php
//print_r();
$this->widget('bootstrap.widgets.TbListView', array(
    'dataProvider' => $model->findAllCars(),
    'itemView' => '_view_car',
    'template' => '{items} {pager}',
    'id' => 'katalog-vavto',
    'htmlOptions' => array('class' => 'brends-list'),
));
?>
 <?= $model->text ?>

