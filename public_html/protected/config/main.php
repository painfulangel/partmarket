<?php
// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');
// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
Yii::setPathOfAlias('bootstrap', dirname(__FILE__) . '/../extensions/bootstrap');

//require_once dirname(__FILE__) . '/db_main.php';
require_once dirname(__FILE__) . '/all_params.php';

define('TURNON_CITIES', $turnon_cities);

return array(
    'aliases' => array(
        'lily' => 'ext.lily',
    ),
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'name' => '',
    'controllerMap' => array(
        'gallery' => array('class' => 'ext.GalleryManager.GalleryController'),
    ),
    'preload' => array(
    	//'bootstrap',
        'log',
        'menu',
        'config',
    ),
    'theme' => 'bootstrap',
    // autoloading model and component classes
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
		//'lily.models.*',
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
        'application.modules.news.components.*',
        'application.modules.news.models.*',
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
    	'application.extensions.ImageFly.components.*',
        'application.modules.brands.models.*',
        'application.modules.cities.models.*',
        'application.modules.webPayments.models.*',
        'application.modules.katalogSeoBrands.models.*',
    	//'ext.phpexcel.Classes.*',
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
    	/*'bootstrap' => array(
    		'class' => 'application.extensions.bootstrap.components.Bootstrap',
    	),*/
        'webPayments' => array(),
        'katalogAccessories' => array(
            'enabledModule' => $enables['katalogAccessories'],
        ),
        'katalogVavto' => array(
            'enabledModule' => $enables['katalogVavto'],
        ),
        'katalogTO' => array(
            'enabledModule' => $enables['katalogTO'],
        ),
        'katalogSeoBrands' => array(
            'enabledModule' => $enables['katalogSeoBrands'],
        ),
        'cities' => array(),
        'requests',
        'userControl' => array(),
        'parsersApi' => array(),
        'shop_cart' => array(),
        'parsers' => array(),
    	
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
			//'controllerFilters'=>array(),
			// you can set accessRules that will be added to the comment controller {@see CController::accessRules()}
			//'controllerAccessRules'=>array(),
			// you can extend comment class and use your extended one, set path alias here
			//'commentModelClass'=>'comment.models.Comment',
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
        'currencies' => $currencies,
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
    	'tires' => array(
            'enabledModule' => $enables['tires'],
        ),
		'used' => array(
            'enabledModule' => $enables['used'],
        ),
    	'universal' => array(
            'enabledModule' => $enables['universal'],
        ),
    	'masla' => array(
            'enabledModule' => $enables['masla'],
        ),
    	'laximo' => array(),
    	'statistics' => array(),
        'brands' => array(),
    	'search' => array(),
        'partscatalogs',
        'katalogSeoBrands',
    	//uncomment the following to enable the Gii tool
        /*'gii' => array(
            'generatorPaths' => array(
                'bootstrap.gii',
            ),
            'class' => 'system.gii.GiiModule',
            'password' => 'admin',
            // If removed, Gii defaults to localhost only. Edit carefully to taste.
            'ipFilters' => array(),
        ),*/
    ),
    // application components
    'components' => array(
//        'clientScript' => array(
//            'class' => 'ext.ExtendedClientScript.ExtendedClientScript',
//            'combineCss' => true,
//            'compressCss' => true,
//            'combineJs' => true,
//            'compressJs' => true,
//        ),
        'zip' => array(
            'class' => 'application.extensions.zip.EZip',
        ),
//        'ftp' => array(
//            'class' => 'application.extensions.ftp.EFtpComponent',
//            'host' => '127.0.0.1',
//            'port' => 21,
//            'username' => 'yourusername',
//            'password' => 'yourpassword',
//            'ssl' => false,
//            'timeout' => 90,
//            'autoConnect' => false,
//        ),
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
        // uncomment the following to enable URLs in path-format
        'urlManager' => array(
            'urlFormat' => 'path',
            //  'class' => 'application.components.UrlManager',
            'showScriptName' => false,
            'rules' => $rules,
        	'urlSuffix' => '/',
        ),
        // uncomment the following to use a MySQL database
        'db' => $db,
        'sp' => $sp,
        'authManager' => array(
            'class' => 'CDbAuthManager',
            'connectionID' => 'db',
            'behaviors' => array(
                'auth' => array(
                    'class' => 'auth.components.AuthBehavior',
                ),
            ),
            'assignmentTable' => 'authassignment',
            'itemChildTable' => 'authitemchild',
            'itemTable' => 'authitem',
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
//                'google' => array(
//                    'class' => 'lily.services.LGoogleService',
//                ),
//                'yandex' => array(
//                    'class' => 'lily.services.LYandexService',
//                ),
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
                /*'db' => array(
                    'class' => 'CWebLogRoute',
                    'categories' => 'system.db.CDbCommand',
                    'showInFireBug' => false //Показывать в FireBug или внизу каждой страницы
                ),*/
                array(
                    'class' => 'CFileLogRoute',
                    'levels' => 'error, warning',
                    //'levels' => 'trace, error, warning,  info, vardump',
                ),

                /*array(
                    'class' => 'ext.db_profiler.DbProfileLogRoute',
                    'countLimit' => 1, // How many times the same query should be executed to be considered inefficient
                    'slowQueryMin' => 0.01, // Minimum time for the query to be slow
                ),*/
                /*array(
                    'class' => 'CWebLogRoute',
                ),*/
                /*array(
                    'class'=>'ext.yii-debug-toolbar.YiiDebugToolbarRoute',
                ),*/
            ),
        ),
        'cache' => array(
            'class' => 'CFileCache',
            //'embedExpiry' => true
        ),
        'dbCache'=>array(
            'class'=>'system.caching.CDbCache'
        ),
        'request' => array(
            'class' => 'HttpRequest',
            'noCsrfValidationRoutes' => array(
                'api/program/getCsrf',
                'api/icApi/loadToSite',
                'api/orderApi/getProducts',
                'api/orderApi/getOrdersInfo',
                'api/orderApi/makeOrder',
                'api/orderApi/refuseOrder',
                'imperavi/default/uploadImage',
                'imperavi/default/uploadFile',
                'imperavi/default/getFiles',
                'imperavi/default/deleteFile',
                'webPayments/yandex/result',
                'webPayments/yandex/check',
                'webPayments/yandex/fail',
                'webPayments/yandex/success',
                'webPayments/yandex/pay',
                'webPayments/webPaymentsRobokassa/result',
                'webPayments/webPaymentsRobokassa/check',
                'webPayments/webPaymentsRobokassa/fail',
                'webPayments/webPaymentsRobokassa/success',
                'webPayments/webPaymentsRobokassa/pay',
                'webPayments/demoYandex/result',
                'webPayments/demoYandex/check',
                'webPayments/demoYandex/fail',
                'webPayments/demoYandex/success',
                'webPayments/demoYandex/pay',
            ),
            //'csrfTokenName' => 'token',
            'enableCsrfValidation' => true,
            'enableCookieValidation' => true,
        ),
        'search' => array(
            'class' => 'application.components.DGSphinxSearch.DGSphinxSearch',
            'server' => '127.0.0.1',
            'port' => 9312,
            'maxQueryTime' => 3000,
            'enableProfiling'=>1,
            'enableResultTrace'=>1,
            'fieldWeights' => array(
                'name' => 10000,
                //'keywords' => 100,
            ),
        ),
    ),
    // application-level parameters that can be accessed
// using Yii::app()->params['paramName']
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
