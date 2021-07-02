<?php

// This is the configuration for yiic console application.
// Any writable CConsoleApplication properties can be configured here.
//require_once dirname(__FILE__) . '/db_main.php';
require_once dirname(__FILE__) . '/all_params.php';

define('PLAN_TASK', 1);

return array(
    'aliases' => array(
        'lily' => 'ext.lily',
    ),
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'name' => 'My Console Application',
    // preloading 'log' component
    'preload' => array(
        'log',
        'config',
    ),
    'import' => array(
        'application.models.*',
        'application.components.*',
        'application.helpers.*',
        'ext.eoauth.*',
        'ext.eoauth.lib.*',
        'ext.lightopenid.*',
        'ext.eauth.*',
        'ext.eauth.services.*',
        'ext.yii-mail.YiiMailMessage',
        'lily.LilyModule',
        'ext.imperavi-redactor-widget.ImperaviRedactorWidget',
        'ext.phpexcelreader.JPhpExcelReader',
        'application.modules.currencies.models.*',
        'application.modules.pricegroups.models.*',
        'application.modules.currencies.components.*',
        'application.extensions.jtogglecolumn.*',
        'ext.menu.helpers.MenuItems',
        'application.modules.prices.components.*',
        'application.modules.prices.models.*',
        'application.modules.pricegroups.models.*',
        'application.modules.crosses.models.*',
        'application.modules.parsers.components.*',
        'application.modules.parsersApi.components.*',
        'application.modules.parsersApi.models.*',
        'application.modules.parsers.models.*',
    	
        'application.modules.detailSearch.components.*',
        'application.modules.detailSearchNew.components.*',
    	
        'application.modules.shop.components.*',
        'application.modules.shop.models.*',
        'application.modules.userControl.models.*',
        'application.modules.shop_cart.models.*',
        'ext.GalleryManager.*',
        'ext.GalleryManager.models.*',
        'ext.yii-image.*',
        'ext.smsSend.*',
        'application.modules.katalogAccessories.components.*',
        'application.modules.katalogVavto.components.*',
        'application.modules.katalogVavto.models.*',
        'application.modules.katalogAccessories.models.*',
        'application.modules.admin_module.components.*',
        'ext.bootstrap.components.TbHtml',
        'application.extensions.sftp.*',
        'application.extensions.crontab.*',
        'application.modules.brands.models.*',
        'application.modules.cities.models.*',
        'application.modules.katalogSeoBrands.models.*',
    // 'ext.phpexcel.Classes.*',
    ),
    'modules' => array(
        'api',
        'auth' => array(
            'userClass' => 'UserProfile',
            'userIdColumn' => 'uid',
            'userNameColumn' => 'fullNameId',
            'defaultLayout' => '//layouts/admin_column2',
        ),
        'admin_module' => array(),
        'webPayments' => array(),
        'katalogAccessories' => array(
            'enabledModule' => $enables['katalogAccessories'],
        ),
        'katalogVavto' => array(
            'enabledModule' => $enables['katalogVavto'],
        ),
        'requests',
        'userControl',
        'parsersApi',
        'shop_cart',
        'parsers',
        'katalogSeoBrands',
    	
        'detailSearch' => array(
            'zerosDeliveryValue' => $zerosDeliveryValue,
        ),
        'adminDetailSearch' => array(
            'zerosDeliveryValue' => $zerosDeliveryValue,
        ),
    	'detailSearchNew' => array(
    		'zerosDeliveryValue' => $zerosDeliveryValue,
    	),
    	
        'menu' => array(
            'class' => 'ext.menu.MenuModule',
        ),
        'config' => array(
            'class' => 'ext.config.ConfigModule',
        ),
        'news' => array(),
        'comment' => array(
            'class' => 'ext.comment-module.CommentModule',
            'commentableModels' => array(
// define commentable Models here (key is an alias that must be lower case, value is the model class name)
                'post' => 'Post'
            ),
            'allowSubcommenting' => true,
// set this to the class name of the model that represents your users
            'userModelClass' => 'User',
// set this to the username attribute of User model class
            'userNameAttribute' => 'username',
// set this to the email attribute of User model class
            'userEmailAttribute' => 'email',
// you can set controller filters that will be added to the comment controller {@see CController::filters()}
//          'controllerFilters'=>array(),
// you can set accessRules that will be added to the comment controller {@see CController::accessRules()}
//          'controllerAccessRules'=>array(),
// you can extend comment class and use your extended one, set path alias here
//          'commentModelClass'=>'comment.models.Comment',
        ),
        'pages_top' => array(
            'class' => 'ext.pages.PagesModule',
            'cacheId' => 'pagesPathsMapTop',
            'position' => '_top'
        ),
        'pages_left' => array(
            'class' => 'ext.pages.PagesModule',
            'cacheId' => 'pagesPathsMapLeft',
            'position' => '_left'
        ),
        'pricegroups' => array(),
        'prices' => array(
            'maxFileSize' => 26214400, //for 25MB
            'extraCharacters' => array('cp1251' => 'cp1251', 'UTF-8' => 'utf'),
            'radionButtonFunction' => 'radioButtonListRow',
            'pathExportFiles' => '/upload_files/',
//'getPriceFunction' => '$data->price',
        ),
        'crosses' => $crosses,
        'imperavi' => array(
            'class' => 'ext.imperavi-redactor-widget.ImperaviModule',
        ),
        'currencies' => array(
            'defaultCurrencyMarker' => 'руб.',
        ),
        'lily' => array(
            'class' => 'lily.LilyModule',
//LilyModule properties
            'hashFunction' => 'md5', //hash function name
            'hashSalt' => 'any abracadbra you want to use as salt', //hash Salt, string that will be appended to hashing value before hashing 
            'randomKeyLength' => 20, //lengths of random keys, generated by application (e.g. activation key)
//'passwordRegexp' => '~^[a-zA-Z0-9\\-\\_\\|\\.\\,\\;\\=\\+\\~/\\\\\\[\\]\\{\\}\\!\\@\\#\\$\\%\\^\\*\\&\\(\\)\\ ]{8,32}$~', //regular expression for password checking
            'passwordRegexp' => '#.?#', //regular expression for password checking
            'sessionTimeout' => 604800, //timeout, after that session will be classified as expired
            'enableUserMerge' => true, //whether to allow user merging
            'userNameFunction' => array('UserProfile', 'getName'), //callback, that takes LUser object as argument and return user's name
            'allowedRoutes' => array('userControl/userProfile/create', 'shop_cart/orders/create'), //routes, that are allowed during any init step
            'routePrefix' => 'lily', //prefix to module uri (e.g. lily prefix means all actions of the module have uris like 'lily/<controllerId>/<actionId>)
            'relations' => array(
                'profile' => array(
                    'relation' => array(CActiveRecord::HAS_ONE, 'UserProfile', 'uid'),
                    // 'onUserMerge' => 'event',
                    'onRegister' => array('/userControl/userProfile/create'),
                ),
            ), //user table relations
//LUserIniter component properties
            'userIniter' => array(
                'showStartStep' => true, //Whether to show initial step page with common information about next actions
                'showFinishStep' => true, //Whether to show finish step page with common information about site using, registration results or etc.
                'finishRedirectUrl' => '/shop_cart/orders/initStep', //Url, to which user will be redirected after initing process (last step) step
            ),
            //            LAccountManager component properties
            'accountManager' => array(
//                'informationMailView' => null, //path to view of information letter (null - use the default content)
//                'activationMailView' => null, //path to view of activation letter (null - use the default content)
//                'restoreMailView' => null, //path to view of restore letter (null - use the default content)
//                'informationMailSubjectCallback' => null, //callback for email subject of information letter
//                'activationMailSubjectCallback' => null, //callback for email subject of activation letter
//                'restoreMailSubjectCallback' => null, //callback for email subject of restoration letter
                'registerEmail' => true, //should we register new e-mail account on the fly, or it's necessary to do it on registration page
                'loginAfterRegistration' => true, //should we automaticaly log user in after e-mail registration
//                'activate' => true, //Whether to activate new account
//                'sendMail' => true, //Whether to send mails
                // 'adminEmail' => 'serheybeloy@ukr.net', //Email to put it in mails (From field)
                'activationTimeout' => 86400, //Timeout, after that activation will be rejected, even if code is clear
            ),
        //Lily configurations
        ),
        // uncomment the following to enable the Gii tool
        'gii' => array(
            'generatorPaths' => array(
                'bootstrap.gii',
            ),
            'class' => 'system.gii.GiiModule',
            'password' => 'admin',
            // If removed, Gii defaults to localhost only. Edit carefully to taste.
            'ipFilters' => array('127.0.0.1', '::1'),
        ),
    ),
// application components
    'components' => array(
        'zip' => array(
            'class' => 'application.extensions.zip.EZip',
        ),
        'image' => array(
            'class' => 'ext.yii-image.CImageComponent',
            // GD or ImageMagick
            'driver' => 'GD',
            // ImageMagick setup path
            'params' => array('directory' => '/opt/local/bin'),
        ),
        'excel' => array(
            'class' => 'application.extensions.phpexcel.PHPExcel',
        ),
        'menu' => array(
            'class' => 'ext.menu.DMenu',
            'cache' => 0,
            'cacheId' => 'MenuAllFiles',
        ),
        'config' => array(
            'class' => 'ext.config.DConfig',
            'cache' => 0,
        ),
        'bootstrap' => array(
            'class' => 'bootstrap.components.Bootstrap',
        ),
        'user' => array(
// enable cookie-based authentication
            'class' => 'auth.components.AuthWebUser',
            'admins' => $superAdmins, // users with full access
            'allowAutoLogin' => true,
            'loginUrl' => array('/lily/user/login'),
            'autoUpdateFlash' => false,
        ),
        'db' => $db,
        'authManager' => array(
            'class' => 'CDbAuthManager',
            'connectionID' => 'db',
            'behaviors' => array(
                'auth' => array(
                    'class' => 'auth.components.AuthBehavior',
                ),
            ),
            'assignmentTable' => '{{rbac_assignment}}',
            'itemChildTable' => '{{rbac_item_child}}',
            'itemTable' => '{{rbac_item}}',
            'defaultRoles' => array('userAuthenticated'),
        ),
        'loid' => array(
            'class' => 'ext.lightopenid.loid',
        ),
        'eauth' => array(
            'class' => 'ext.eauth.EAuth',
            'popup' => true, // Use the popup window instead of redirecting.
            'cache' => false, // Cache component name or false to disable cache. Defaults to 'cache'.
            'cacheExpire' => 0, // Cache lifetime. Defaults to 0 - means unlimited.
            'services' => array(
                'email' => array(
                    'class' => 'lily.services.LEmailService',
                ),
                'onetime' => array(
                    'class' => 'lily.services.LOneTimeService', //service, required by Lily
                ),
                'google' => array(
                    'class' => 'lily.services.LGoogleService',
                ),
                'yandex' => array(
                    'class' => 'lily.services.LYandexService',
                ),
            /*
              'twitter' => array(
              'class' => 'lily.services.LTwitterService',
              'key' => '',
              'secret' => '',
              ),
              'vkontakte' => array(
              'class' => 'lily.services.LVKontakteService',
              'client_id' => '',
              'client_secret' => '',
              ),
              'mailru' => array(
              'class' => 'lily.services.LMailruService',
              'client_id' => '',
              'client_secret' => '',
              ),

             */
            ),
        ),
        'mail' => $mail,
        'errorHandler' => array(
// use 'site/error' action to display errors
            'errorAction' => 'site/error',
        ),
        'log' => array(
            'class' => 'CLogRouter',
            'routes' => array(
                array(
                    'class' => 'CFileLogRoute',
                    'levels' => 'error, warning,  info',
                ),
//                array(
//                    'class' => 'ext.yii-debug-toolbar.YiiDebugToolbarRoute',
//                    'ipFilters' => array('127.0.0.1', '213.109.92.187', '91.202.128.11'),
//                ),
// uncomment the following to show log messages on web pages
//                array(
//                    'class' => 'CWebLogRoute',
//                ),
            ),
        ),
        'cache' => array(
            'class' => 'CFileCache',
        ),
        'request' => array(
            'enableCsrfValidation' => false,
            'enableCookieValidation' => true,
        ),
    ),
    'commandMap' => array(
        'migrate' => array(
            'class' => 'system.cli.commands.MigrateCommand',
            'migrationPath' => 'application.migrations',
            'migrationTable' => 'ls_migration',
            'connectionID' => 'db',
        ),
        'lily_rbac' => array(
            'class' => 'lily.commands.LAuthInstaller'
        ),
        'FtpPriceLoad' => array(
            'class' => 'application.commands.FtpPriceLoad3'
        ),
        'MailBoxLoad' => array(
            'class' => 'application.commands.MailBoxLoad'
        ),
        'PriceExport' => array(
            'class' => 'application.commands.PriceExport'
        ),
        'ClearFiles' => array(
            'class' => 'application.commands.ClearFiles'
        ),
    	'ProcessCrossFiles' => array(
    		'class' => 'application.commands.ProcessCrossFiles'	
    	),
    	'ExchangeRates' => array(
    		'class' => 'application.commands.ExchangeRates'	
    	),
        'PriceBrands' => array(
            'class' => 'application.commands.PriceBrands' 
        ),
        'HandlerQueuePrice'=> array(
            'class' => 'application.commands.HandlerQueuePrice'
        ),
        'ApplyRules'=> array(
            'class' => 'application.commands.ApplyRules'
        ),
        'AllCatalogs' => array(
            'class' => 'application.commands.AllCatalogs'
        ),
    ),
    'params' => array(
        'MultiKoefSuplierPrice' => 327.12537,
// this is used in contact page
// 'adminEmail' => 'shutclare@gmail.com',
        'sitemap' => array(
            'prices',
            'katalogAccessories',
            'katalogVavto',
            'pages_top',
            'pages_left',
            'news',
        ),
        'ftp' => array(),
        'cronUser' => $cronUser,
        'default_language_name' => $default_language_name,
        'default_language' => $default_language,
    ),
    'sourceLanguage' => 'en',
    'language' => $default_language,
);
