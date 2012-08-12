<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
	// inisialisasi autoloader
	protected function _initAutoLoad()
	{
		$autoLoader = Zend_Loader_Autoloader::getInstance();
		$resoureceLoader = new Zend_Application_Module_Autoloader( array(
			'basePath' => APPLICATION_PATH . '/modules/default',
			'namespace' => '',
			'resourceType' => array( 
				'form' => array(
					'path' => 'forms',
					'namespace' => 'Form'
				),
				'model' => array(
					'path' => 'models',
					'namespace' => 'Model'
				)
			)
		));
		
		$autoLoader->pushAutoLoader( $resoureceLoader );
		// autoload PhpThumb
		$autoLoader->pushAutoloader( new My_Loader_PhpThumb() );
	}
	
	// inisialisasi registry
	protected function _initRegistry()
	{
		// registry db
		$this->db = $this->bootstrap('db')->getResource('db');
		Zend_Registry::set( 'db', $this->db );
		
		// registry upload
		$upload = new My_Loader_Upload();
		Zend_Registry::set( 'upload', $upload );
		
		$config = new Zend_Config_Ini( APPLICATION_PATH . '/configs/application.ini', APPLICATION_ENV );
		// registry google 
		$google = $config->google->toArray();
		Zend_Registry::set( 'google', $google );
	}
	
	// inisialisasi controller plugin
	protected function _initPlugin()
	{
		$frontController = Zend_Controller_Front::getInstance();
		
		// register plugin Module
		$frontController->registerPlugin( new My_Layout_Plugin_Module() );
		// register plugin ACL
		// $frontController->registerPlugin( new My_Controller_Plugin_Acl() );
	}
	
	// inisialisasi controller helper	
	protected function _initHelper()
	{		
		Zend_Controller_Action_HelperBroker::addPath(APPLICATION_PATH .'/helpers/controller');
		Zend_Controller_Action_HelperBroker::getStaticHelper('Hooks');
	}
	
	// inisialisasi controller router
	protected function _initRoute()
	{
		$frontController = Zend_Controller_Front::getInstance();
		
		$about = new Zend_Controller_Router_Route('about-us/:title', array(
								'module' 		=> 'default',
								'controller' 	=> 'index',
						  		'action' 		=> 'index',
						  		'title' 		=> 'about-us')
		);
		$frontController->getRouter()->addRoute('about-us', $about);
		
		$service = new Zend_Controller_Router_Route('services/:title', array(
								'module' 		=> 'default',
								'controller' 	=> 'index',
						  		'action' 		=> 'index',
						  		'title' 		=> '')
		);
		$frontController->getRouter()->addRoute('services', $service);
	}
	
	protected function _initCache()
	{
		$frontend = array(
	    	'lifetime' => 7200,
	    	'automatic_serialization' => true
	    );
	    $backend = array(
	    	'cache_dir' => APPLICATION_PATH . "/caches",
	    );
	
	    $cache = Zend_Cache::factory('Core',
	    	'File',
	    	$frontend,
	    	$backend
	    );
	
	    Zend_Registry::set('cache',$cache);
	}
	
	protected function _initNavigation()
	{
		// navigation
		$menus = Model_Option::GetMenus( $this->db );
		// get option navigation
		$option = new Model_Option( $this->db );
		$option->load( 'navigation', 'option_name' );
		$nav = $option->option_value;
		
		if( $menus[$nav] )
			$container = (object) $menus[$nav]['menus'];
		else {
			$container = Model_Content::GetContents($this->db, array(
				'type' => 'page', 
				'parent_in' => 0
			));
		}
		// bulid navigation
		$menu = new My_Navigation_Menu( $container );
		$navigation = $menu->getNavigation();
		
		$view = $this->bootstrap('view')->getResource('view');
	    $view->navigation($navigation);
	}
	
	// inisialisasi view
	protected function _initView()
	{
		$view = new Zend_View();
		//$view->docType('XHTML1_STRICT');
		$view->headMeta()->appendHttpEquiv('Content-type', 'text/html;charset=utf-8');		
		$view->headTitle()->setSeparator(' | ')->headTitle('D Snap');
		
		// set helper path view
		$view->setHelperPath(APPLICATION_PATH.'/helpers/view');
		// tambah helper ZendX (library jQuery untuk Zend)
		ZendX_JQuery::enableView($view);
		// tambah helper view buatan
		$view->addHelperPath('My/Form/View/Helper', 'My_Form_View_Helper');
		
		$viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
		$viewRenderer->setView($view);
		
		return $view;
	}
}

