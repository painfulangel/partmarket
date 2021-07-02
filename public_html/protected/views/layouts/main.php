<?php //$start = microtime(true); ?>
<?php /* @var $this Controller */ ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="language" content="en" />
        <?php if ($this->metaKeywords != null && !empty($this->metaKeywords)) { ?>
            <meta name="description" content="<?php echo htmlspecialchars($this->metaKeywords) ?>" />
        <?php } ?>
        <?php if ($this->metaDescription != null && !empty($this->metaDescription)) { ?>
            <meta name="keywords" content="<?php echo htmlspecialchars($this->metaDescription) ?>" /> 
        <?php } ?>
        <?php
        global $YII_BOOTSTRAP_CLIENT;
        $YII_BOOTSTRAP_CLIENT = true;
        Yii::app()->bootstrap->register();
        
        $cs = Yii::app()->getClientScript();
        $cs->registerScriptFile(Yii::app()->baseUrl.'/js/jquery-migrate.min.js');
        $cs->registerScriptFile(Yii::app()->baseUrl.'/js/jquery.reveal.js');
        $cs->registerScriptFile(Yii::app()->baseUrl.'/js/myYiiJs.js');
        //$cs->registerScriptFile(Yii::app()->theme->baseUrl.'/js/js-carousel.js');
        $cs->registerCssFile(Yii::app()->baseUrl.'/css/reveal.css');
		$cs->registerCssFile(Yii::app()->baseUrl.'/css/main.css');
		$cs->registerCssFile(Yii::app()->baseUrl.'/css/styles.css');
        $cs->registerCssFile(Yii::app()->baseUrl.'/css/custom.responsive.css');
        $cs->registerCssFile(Yii::app()->baseUrl.'/css/font-awesome.min.css');
        ?>
        <title><?php echo CHtml::encode(empty($this->metaTitle) ? $this->pageTitle : $this->metaTitle); ?></title>
      
        <script src="https://www.google.com/recaptcha/api.js" async=""></script>
    </head>

    <body>
        <!--<span>
            <script type='text/javascript'>  document.write('Разрешение экрана: <b>'+window.screen.width+'×'+window.screen.height+'px.</b><br>'); </script>
            <script type='text/javascript'>  document.write('Разрешение браузера: <b>'+window.innerWidth+'×'+window.innerHeight+'px.</b>'); </script>
        </span>-->
        <div id="wrap">
            <div id="wrap2">
                <div class="mt">
                    <div class='row-fluid show-grid'>
<?php
						if (!Yii::app()->user->isGuest && Yii::app()->getModule('userControl')->userUnreadMessages()) {
								$class = 'blink';
?>
							<script type="text/javascript">
								$(function() {
									if ($('a.blink').length) setInterval("$('a.blink').toggleClass('hideBack')", 500);
								});
							</script>
<?php
						}
						
                        $top_menu = array(
                            array('label' => Yii::app()->getModule('userControl')->getUserLoginName(), 'url' => array('/userControl/userProfile/cabinet'), 'visible' => !Yii::app()->user->isGuest, 'linkOptions' => array('style' => 'background-image:url(/images/theme/item2.png);')),
                            array('label' => Yii::t('menu', 'Messages'), 'url' => array('/userControl/userMessages/index'), 'visible' => !Yii::app()->user->isGuest, 'linkOptions' => array('class' => 'blink')),
                            array('label' => Yii::t('site_layout', 'My orders'), 'url' => array('/shop_cart/orders/index'), 'visible' => !Yii::app()->user->isGuest, 'linkOptions' => array('style' => 'background-image:url(/images/theme/item3.png);')),
                            array('label' => Yii::t('site_layout', 'My Garage'), 'url' => array('/userControl/usersCars/index'), 'visible' => !Yii::app()->user->isGuest, 'linkOptions' => array('style' => 'background-image:url(/images/theme/item4.png);')),
                            array('label' => Yii::t('site_layout', 'Finances'), 'url' => array('/userControl/userBalance/index'), 'visible' => !Yii::app()->user->isGuest, 'linkOptions' => array('style' => 'background-image:url(/images/theme/item5.png);')),
                            array('label' => Yii::t('site_layout', 'Settings'), 'url' => array('/userControl/userProfile/settings'), 'visible' => !Yii::app()->user->isGuest, 'linkOptions' => array('style' => 'background-image:url(/images/theme/item6.png);')),
                            array('label' => Yii::t('site_layout', 'Output'), 'url' => array('/lily/user/logout'), 'visible' => !Yii::app()->user->isGuest, 'linkOptions' => array('style' => 'background-image:url(/images/theme/item8.png);')),
                            array('label' => Yii::t('site_layout', 'Input'), 'url' => array('/lily/user/login'), 'visible' => Yii::app()->user->isGuest, 'linkOptions' => array('style' => 'background-image:url(/images/theme/item2.png);')),
                            array('label' => Yii::t('site_layout', 'Registration'), 'url' => array('/userControl/userProfile/registration'), 'visible' => Yii::app()->user->isGuest, 'linkOptions' => array('style' => '')),
                            array('label' => Yii::t('site_layout', 'Admin panel'), 'url' => array('/admin_module/default/index'), 'visible' => Yii::app()->user->checkAccess('texts') ||
                                Yii::app()->user->checkAccess('managerNotDiscount') ||
                                Yii::app()->user->checkAccess('manager') ||
                                Yii::app()->user->checkAccess('mainManager') ||
                                Yii::app()->user->checkAccess('admin') ||
                                Yii::app()->user->checkAccess('userAdmin') ||
                                Yii::app()->user->checkAccess('userModerator'), 'linkOptions' => array('style' => '')),
                        );
                        
                        //echo '<pre>'; print_r(array_merge($_GET, array('_lang' => Yii::app()->params['default_language']))); echo '</pre>';
                        //echo get_class($this);
                        //echo $this->createUrl('', array_merge($_GET, array('_lang' => 'en')));
                        //CathegoriasController
                        //DefaultController();
                        
                //        $top_menu[] = array('label' => Yii::app()->params['default_language_name'], 
               //         					'url' => $this->createUrl('', array_merge($_GET, array('_lang' => Yii::app()->params['default_language']))), 
               //         					'linkOptions' => array('style' => ''));
                //        
                //        foreach (Orders::model()->langsList() as $lang) {
                //            $top_menu[] = array('label' => $lang['short_name'], 
              //              					'url' => $this->createUrl('', array_merge($_GET, array('_lang' => $lang['link_name']))), 
             //               					'linkOptions' => array('style' => ''));
             //           }
                        
                        $this->widget('bootstrap.widgets.TbNavbar', array(
                            'brand' => false,
                            'brandUrl' => '#',
                            'fixed' => false,
                            'collapse' => true, // requires bootstrap-responsive.css
                            'items' => array(
                                array(
                                    'class' => 'bootstrap.widgets.TbMenu',
                                    'items' => $top_menu,
                                ),
//                                "
//            <div class='span4'><ul class='nav pull-right' style=' float:right; margin-right:90px; '>
//               <li><a style='background-image:url(/images/theme/item9.png)' href='#'>Контакты</a></li>
//		</ul></div>",
                            ),
                        ));
?>
                    </div>
                </div>
                <div id="headm" class="row-fluid show-grid">
                    <div class="span3">
                        <a href="/"><img style="margin-top:10px;" src="/images/theme/<?php echo Yii::t('site_layout', 'logo3_en.png') ?>"></a>
                    </div>
                    <div class="span5" id="hece">
                        <?php
                        if (defined('TURNON_CITIES') && TURNON_CITIES == true) {
                            Yii::app()->getModule('cities');

                            $city = Cities::getInfo();

                            if (is_object($city)) {
                                $this->widget('ext.jquery_fancybox.FancyboxWidget');
                                
                                echo ' <span class="hece-config-site"><img src="/images/theme/rus.png"><span id="city_name">'.$city->name.'</span></span>'.'&nbsp;&nbsp;<a class="btn choose_city">'.Yii::t('cities', 'Choose other city').'</a><br>';
                                echo ' <span class="hece-config-site"><img src="/images/theme/item10.png"><span id="city_phone"><a href="Tel:'.$city->phone.'" style="color:#FFF">'.$city->phone.'</a></span></span>';
                            }
                        } else {
                            $temp = Yii::app()->config->get('Site.SiteAdress');
                            if (!empty($temp)) {
                                echo ' <span class="hece-config-site"><img src="/images/theme/rus.png">'.$temp.'</span><br>';
                            }
                            $temp = Yii::app()->config->get('Site.Phone');
                            if (!empty($temp)) {
                                echo ' <span class="hece-config-site"><img src="/images/theme/item10.png"><a href="Tel:'.$temp.'" style="color:#FFF">'.$temp.'</a></span>';
                            }
                        }

                        $temp = Yii::app()->config->get('Site.SiteICQ');
                        if (!empty($temp)) {
                            echo ' <span class="hece-config-site"><img src="/images/theme/item13.png">'.$temp.'</span>';
                        }
                        $temp = Yii::app()->config->get('Site.SiteSkype');
                        if (!empty($temp)) {
                            echo ' <span class="hece-config-site"><img src="/images/theme/item12.png">'.$temp.'</span>';
                        }

                        if (defined('TURNON_CITIES') && TURNON_CITIES == true && is_object($city)) {
                            echo ' <span class="hece-config-site"><img src="/images/theme/item11.png"><span id="city_mail"><a href="mailto:'.$city->email.'" style="color:#FFF">'.$city->email.'</a></span></span>';
                        } else {
                            $temp = Yii::app()->config->get('Site.AdminEmail');
                            if (!empty($temp)) {
                                echo ' <span class="hece-config-site"><img src="/images/theme/item11.png"><a href="mailto:'.$temp.'" style="color:#FFF">'.$temp.'</a></span>';
                            }
                        }
                        ?><br>
<?php
                            $a[] = 'List1';
                            $a[] = 'List2';

                            $search_name = Yii::app()->config->get('Site.SearchType') == 1 ? 'detailSearchNew' : 'detailSearch';
                            
                            if (!Yii::app()->getModule('katalogVavto')->enabledModule) {
                                echo Yii::app()->getModule($search_name)->getSearchForm();
                            } else {
                                echo '<div class="select_byid_name" >'
                                    .CHtml::radioButtonList(
                                            'select',
                                            '0',
                                            array(
                                                '0' => Yii::t('site_layout', 'Search for article'),
                                                '1' => Yii::t('site_layout', 'Search by name')
                                            ),
                                            array('class' => '', 'separator' => '<span class="select-byid-name-separator">&nbsp;</span>'))
                                    .'</div>';
                                echo '<div class="search_by_id" >'.Yii::app()->getModule($search_name)->getSearchForm().'</div>';
                                echo '<div class="search_by_name"  style="display:none">'.Yii::app()->getModule('katalogVavto')->getSearchForm().'</div>';
                                $script = <<<SCRIPT
                                    $(".select_byid_name label,.select_byid_name input").click(function(){
   if($(this).val()=='1'){
       $('.search_by_id').hide();
       $('.search_by_name').show();
                                        }else{
                                         $('.search_by_id').show();
       $('.search_by_name').hide();
                                        }
   });
SCRIPT;
                                Yii::app()->clientScript->registerScript(__CLASS__."#".$search_name, $script, CClientScript::POS_END);
                            }
?>
                    </div>
                    <div class="span4 main-top-shop-cart">
                        <?php echo Yii::app()->getModule('shop_cart')->getCartBlock() ?>
                        <div class="clear-both">
                            <?php
                            echo CHtml::link(Yii::t('site_layout', 'Request for VIN'), array('/requests/requestVin/create'), array('class' => 'btn btn-success')).' ';
                            echo CHtml::link(Yii::t('site_layout', 'Inquiry PU'), array('/requests/requestWu/create'), array('class' => 'btn btn-success')).' ';
//                            echo CHtml::link('Каталоги', array(''), array('class' => 'btn btn-success'));
                            ?> 
                        </div>
                    </div>    
                </div>
                <div id='cenrow' class="row-fluid show-grid">
                    <!-- responsive button for menu -->
                    <div class="spoiler-leftmm">
                        <a href="#" class="spoiler-leftmm-text" onclick="displayMenu();return false;">
                            <?php echo Yii::t('menu', 'Menu');?>
                        </a>
                    </div>
                    <div id="leftmm" class="span3">
                        <div class="inner-leftmm">
<?php
                            $this->widget('bootstrap.widgets.TbMenu', array(
                                'items' => MenuItems::getMenu('left'),
                                'htmlOptions' => array(
                                    'class' => 'nav nav-pills nav-stacked',
                                    'id' => 'leftm',
                                ),
                            ));
                            
                            $this->widget('katalogVavto.widgets.KatalogVavtoWidget');
                            $this->widget('katalogSeoBrands.widgets.KatalogSeoBrandsWidget');
                            
                            $this->widget('news.components.LastNewsWidget', array());
?>
                            <div class="side-banner-main">
                                <?php echo Yii::app()->config->get('Site.Baner'); ?>
                            </div>

                        </div>
                    </div>
                    <div class="span9 rigth-main-block">
                        <?php if (isset($this->breadcrumbs)): ?>
                            <?php
                            $this->widget('bootstrap.widgets.TbBreadcrumbs', array(
                                'links' => $this->breadcrumbs,
                                'htmlOptions' => array('class' => 'sorting-bar'),
                            ));
                            ?><!-- breadcrumbs -->
                        <?php endif ?>
                        <?php echo $content; ?>
                    </div>
                </div>
            </div>
        </div>
        <footer class="footer">
            <div class="row-fluid show-grid">
                <div  class="span3" style="padding-top:15px; padding-right:15px;">
                    <span class="cop"><?php echo Yii::t('site_layout', 'Development') ?>: <a href="http://<?php echo Yii::t('site_layout', 'partexpert.net') ?>"> <img src="/images/sm_partexpert1.png" border="0" title="<?php echo Yii::t('site_layout', 'create auto parts store') ?>"> </a></span>
                </div>

                <div class="span6"  style="padding-top:15px; ">
                    <?php
                    $this->widget('bootstrap.widgets.TbNavbar', array(
                        'brand' => '',
                        'fixed' => false,
                        'items' => array(
                            array(
                                'htmlOptions' => array('class' => ''),
                                'class' => 'bootstrap.widgets.TbMenu',
//                                'items' => array_merge(MenuItems::getMenu('top'), array(
                                'items' => array_merge(array(), array(
                                    array('label' => Yii::t('site_layout', 'Site Map'), 'url' => array('/site/sitemap')),
                                    array('label' => Yii::t('site_layout', 'Administration'), 'visible' => Yii::app()->user->checkAccess('texts') ||
                                        Yii::app()->user->checkAccess('managerNotDiscount') ||
                                        Yii::app()->user->checkAccess('manager') ||
                                        Yii::app()->user->checkAccess('mainManager') ||
                                        Yii::app()->user->checkAccess('admin') ||
                                        Yii::app()->user->checkAccess('userAdmin') ||
                                        Yii::app()->user->checkAccess('userModerator'), 'url' => array('/admin_module/default/index')),
                                        )
                                ),
                                'htmlOptions' => array('class' => 'secondary-menu'),
                                'type' => 'list',
                            ),
                        ),
                        'htmlOptions' => array('class' => 'secondary-menu'),
                    ));
                    ?>
                    <style>
                        .secondary-menu .navbar-inner a{
                            color: #0088cc !important;   
                        }
                        .secondary-menu .navbar-inner{
                            background: none !important;
                            box-shadow:none;
                            border: none;

                        }
                    </style>
                </div>
                <div class="span3" style="padding-top:15px;">

                    <div class="b_footer_ban"><?php echo Yii::app()->config->get('Site.BanerFooter') ?> </div>

                    <div class="clearfix"></div>
<!--                    <img src='/images/theme/mail.png'/>
                    <img src='/images/theme/mail.png'/>-->
                </div>
            </div>
        </footer>
        <div id="myModal" class="reveal-modal">
            <h3 id="modalTitle"></h3>
            <p id="modalText"></p>
            <p id="modalCartButtons">
                <?php echo CHtml::link(Yii::t('site_layout', 'Continue shopping'), '#', array('class' => 'btn btn-primary pull-left', 'onclick' => '$(\'.close-reveal-modal\').click();return false;')) ?>
                <?php echo CHtml::link(Yii::t('site_layout', 'Checkout'), array('/shop_cart/shoppingCart/view'), array('class' => 'btn btn-primary pull-right')) ?>
            </p>
            <a   class="close-reveal-modal">&#215;</a>
        </div>
        <div id="myModalSubmit" class="reveal-modal">
            <h3 id="modalTitleSubmit"><?php echo Yii::t('site_layout', 'Prepaid order') ?></h3>
            <p id="modalTextSubmit"><?php echo Yii::t('site_layout', 'In your order, there are details which require prepayment, select prepaid option.') ?></p>
            <p id="modalCartButtonsSubmit">
                <?php echo CHtml::link(Yii::t('site_layout', 'Full payment'), '#', array('class' => 'btn btn-primary pull-left', 'onclick' => '$(\'#prepaid_type_id\').attr(\'value\',1);$(\'#orders-form\').submit();return false;')) ?>
                <?php echo CHtml::link(Yii::t('site_layout', 'Prepayment').' 30%', '#', array('class' => 'btn btn-primary pull-right', 'onclick' => '$(\'#prepaid_type_id\').attr(\'value\',0.3);$(\'#orders-form\').submit();return false;')) ?>
            </p>
            <a   class="close-reveal-modal">&#215;</a>
        </div>
        <?php /* ?>
        <iframe name="_footer_iframe" style="display: none;"></iframe>
        <?php */ ?>
        <script>
            $(document).ready(function () {
                checkTop();
            });

            $(window).scroll(function(){
                checkTop();
            });

            $(function () {
                if('ontouchstart' in window){
                    console.log('сенсорный экран');
                    jQuery('body').tooltip({'selector':false});
                }
                else {
                    console.log('обычный экран');
                }
            })
        </script>
    </body>
</html>
<?php //echo (microtime(true) - $start); ?>