<?php
$this->metaDescription = 'Автозапчасти Ford купить в Иньернеь-Магазине запчастей для иномарок CarGlobus';
$this->metaKeywords = 'автозапчасти ford, интренет-магазин запчастей для иномарок';
$this->pageTitle = 'Автозапчасти на Ford с автосервисом и шиномонтажём.';

?>
<div class="car_links" style="margin-top: -40px">
  <h1>Нужна запчасть? Время не теряй - покупай on-line!</h1>
  <nav>
    <a href="/katalog/ford/"><em class="ic ic_1">&nbsp;</em>FORD</a>
    <a href="/katalog/mitsubishi/"><em class="ic ic_2">&nbsp;</em>MITSUBISHI</a>
    <a href="/katalog/chevrolet/"><em class="ic ic_3">&nbsp;</em>CHEVROLET</a>
    <a href="/katalog/renault/"><em class="ic ic_4">&nbsp;</em>RENAULT</a>
    <a href="/katalog/mazda/"><em class="ic ic_5">&nbsp;</em>MAZDA</a>
    <a href="/katalog/opel/"><em class="ic ic_6">&nbsp;</em>OPEL</a>
  </nav>
</div>

<div class="car_list">
  <?php
  $count = count($brands);
  for ($i = 0; $i < $count; $i ++) {
    $model = $brands[$i];
    ?>
    <span class="car_list-title"><?=$model->title?></span>

  <ul class="car_list--catalog">
  <?php
  $count2 = count($model->katalogVavtoCars);
  for ($j = 0; $j < $count2; $j ++) {

    ?>
    <li>
      <img src="/<?=$model->katalogVavtoCars[$j]->getAttachment()?>" alt="" style="max-width: 217px;max-height: 131px"/>
      <?php echo CHtml::link($model->katalogVavtoCars[$j]->title, array('/katalogVavto/cars/view', 'id' => $model->katalogVavtoCars[$j]->primaryKey),['style'=>'display: block;']);?>
      
    </li>
    <?php
  }
  ?>
  </ul>
    <?php
  }
  ?>
</div>
