<?php
require(dirname(__FILE__).'/../components/common_constants.php');
// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// set the Time to India Timezone
date_default_timezone_set('Asia/Kolkata');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'Medinfi Blog',

	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
	),

	'modules'=>array(
		// uncomment the following to enable the Gii tool
		/*
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'Enter Your Password Here',
			// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'=>array('127.0.0.1','::1'),
		),
		*/
	),

	// application components
	'components'=>array(
		'user'=>array(
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
		),
		// uncomment the following to enable URLs in path-format

		'urlManager'=>array(
            'showScriptName' => false,
			'urlFormat'=>'path',
            'caseSensitive'=>false,
			'rules'=>array(
                '/'=>'blog/index',
                'page/<page_no:([0-9]+)>/'=>'blog/index',
                '<term_type:(category|tag)>/<term_slug:([a-zA-Z0-9\-]+)>/'=>'blog/index',
                '<term_type:(category|tag)>/<term_slug:([a-zA-Z0-9\-]+)>/page/<page_no:([0-9]+)>/'=>'blog/index',//%c2%ad
                '<category_slug:([a-zA-Z0-9\-]+)>/<year:[0-9]{4}>/<month:[0-9]{2}>/<day:[0-9]{2}>/<post_slug:([a-zA-Z0-9-­]+)>/'=>'blog/GetBlogPostDetails',//hidden soft hyphen character after trailing hyphen in post_slug regex
                'auto-suggestions'=>'blog/GetAutoSuggestions',
                'user-location'=>'blog/GetAddressFromLatLon',
                'addBlogComment'=>'blog/AddCommentToPost',
                'blogNotificationSubscription'=>'blog/BlogNotificationSubscription',
                'blogSubscriptionEmail'=>'blog/blogSubscriptionEmail',
                'blogNotificationunsubscription'=>'blog/BlogNotificationUnsubscription',
                'primeSurvey'=>'blog/PrimeSurvey',
                'login'=>'blog/Login',
                'logout'=>'blog/Logout',
                'notificationMessage'=>'blog/GetBrowserNotificationMessage',

                '/backend-login' => 'User/Login',
                'addblogtask' => 'BlogTask/AddBlogTask',
                'uploadblogtask' => 'BlogTask/UploadBlogTask',
                'adduser' => 'BlogTask/AddUser',

				'/ever-cookie-png'=>'EverCookie/EverCookiePng',
                '/ever-cookie-etag'=>'EverCookie/EverCookieEtag',
                '/ever-cookie-cache'=>'EverCookie/EverCookieCache',
                '/createUserSession'=>'Analytics/CreateUserSession',
                '/updateUserSession'=>'Analytics/UpdateUserSessionLog',

                'search/<search_input:(.+)>/page/<page_no:([0-9]+)>/'=>'blog/index',
                'search/<search_input:(.+)>/'=>'blog/index',
                '<controller:\w+>/<id:\d+>'=>'<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
				'<post_slug:([a-zA-Z0-9-­]+)>/page/<page_no:([0-9]+)>/'=>'blog/GetBlogPostDetails',
				'<post_slug:([a-zA-Z0-9-­]+)>/'=>'blog/GetBlogPostDetails', //url shortening project


			),
		),
		/*
		'db'=>array(
			'connectionString' => 'sqlite:'.dirname(__FILE__).'/../data/testdrive.db',
		),*/
		// uncomment the following to use a MySQL database

		'db'=>array(
			'connectionString' => 'mysql:host=localhost;dbname=medinfi-med',
			'emulatePrepare' => true,
			'username' => 'root',
			'password' => '',
			'charset' => 'utf8',
		),
        'wpDb'=>array(
			'class' => 'CDbConnection',
			'connectionString' => 'mysql:host=localhost;dbname=medinfi-hindi-wordpress',
			'emulatePrepare' => true,
			'username' => 'root',
			'password' => '',
			'charset' => 'utf8',
		),

		'analyticsDb'=>array(
        			'class' => 'CDbConnection',
        			'connectionString' => 'mysql:host=localhost;dbname=medinfi_analytics',
        			'emulatePrepare' => true,
        			'username' => 'root',
        			'password' => '',
        			'charset' => 'utf8',
        		),

		'errorHandler'=>array(
			// use 'site/error' action to display errors
			'errorAction'=>'blog/error',
		),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning, info',
				),
				// uncomment the following to show log messages on web pages
				/*
				array(
					'class'=>'CWebLogRoute',
				),
				*/
			),
		),
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		// this is used in contact page
		'adminEmail'=>'webmaster@example.com',
	),
    'onException' => function($event){
        if ($event->exception instanceof CDbException){
          // true means, mark the event as handled so that no other handler will
          // process the event (the Yii exception handler included).
          $event->handled = true;
          if(strtolower(Yii::app()->controller->id) == strtolower('Api'))
            echo json_encode(array('Status'=>500,'Message'=>'Unknown error'));
          else
          echo 'Unexpected Error! Please refer application.log for details';
        }
    }
);