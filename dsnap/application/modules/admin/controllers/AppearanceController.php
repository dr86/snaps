<?php

class Admin_AppearanceController extends Zend_Controller_Action
{

    public function init()
    {
        $this->db = Zend_Registry::get('db');
    }

    public function indexAction()
    {
        // action body
    }

    public function widgetsAction()
    {
    	$this->view->headTitle('Widgets');
		
        // available widgets
        $availablewidgets = Model_Option::GetWidgets($this->db, array( 'key' => 'widget' ));
		$this->view->availablewidgets = $availablewidgets;
		
    	// ambil register_widgets
		$option = new Model_Option( $this->db );
		$option->load('register_widgets', 'option_name');
		$widgets = unserialize($option->option_value);	

		$this->view->widgets = $widgets;
		
		// pages
		$pages = Model_Content::GetContents( $this->db, array(
				'parent_in' => 0,
				'type' => 'page',
				'order' => 'content_order'
			) 
		);
		$this->view->pages = $pages;
		
		// menus
		$menus = Model_Option::GetMenus( $this->db );
		$this->view->menus = $menus;
    }
	
    public function menusAction()
    {
    	$this->view->headTitle('Menus');
		
		// navigation
		$option = new Model_Option( $this->db );
		$option->load( 'navigation', 'option_name' );
		$nav = $option->option_value;
		$this->view->current_navigation = $nav;
		
		// pages
		$pages = Model_Content::GetContents( $this->db, array(
			'type_not' => array( 
				Model_Content::CONTENT_POST, 
				Model_Content::CONTENT_GALLERY,
				'people'
			),
			'order' => 'content_type'
		));
		$this->view->pages = $pages;
		
		// save add menu
		if( $this->_request->isPost() )
		{
			$menu_name = strtolower($this->_request->getPost('menu-name'));
			$menu_value = array(
				'name' => $menu_name,
				'menus' => array()
			);
			$menu = new Model_Option( $this->db );
			$menu->option_name = 'menu_'.$menu_name;
			$menu->option_value = serialize($menu_value);
			$menu->save();
			
			$this->_helper->redirector( 'menus', 'appearance', 'admin', array( 'menu' => $menu->option_name ) );
		}
		
		// menus
		$menus = Model_Option::GetMenus( $this->db );
		$this->view->menus = $menus;
		
		
    }
	
	/** 
	 * TEST ACTION
	 * untuk debugging dan testing
	 */
	public function testwidgetAction()
	{
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		/*
		echo '<h1>REGISTER_WIDGETS</h1>';
		$sw = array(
				'widget_sidebar_left' => array(),
				'widget_sidebar_right' => array(),
				'widget_footer_left_1' => array(),
				'widget_footer_left_2' => array(),
				'widget_footer_right_1' => array(),
				'widget_footer_right_2' => array()
		);	
		echo serialize($sw);
		*/
		echo '<h1>WIDGETS</h1>';
		$availablewidgets = Model_Option::GetWidgets($this->db, array( 'key' => 'widget' ));
		Zend_Debug::dump($availablewidgets);
		
		echo '<h1>REGISTER_WIDGETS</h1>';
		$option = new Model_Option( $this->db );
		$option->load('register_widgets', 'option_name');
		$widgets = unserialize($option->option_value);	
		Zend_Debug::dump($widgets);
		
		// update/replace
		echo '<h1>WIDGET DATA ADD</h1>';
		echo '<h3>add new</h3>';
		$replacements = array(
			'data' => array(
				1 => array(
					'title' => 'test title 1',
					'count' => 5
				)
			)
		);
		$widgets = Model_Option::_array_replace( $availablewidgets['widget_menus'], $replacements );
		Zend_Debug::dump($widgets);
		 
		echo '<h3>add multi</h3>';
		// update/replace
		$replacements = array(
			'data' => array(
				array(
					'title' => 'test title 2',
					'count' => 5
				)
			)
		);
		$widgets = array_merge_recursive( $widgets, $replacements );
		Zend_Debug::dump($widgets);
		
		echo '<h1>WIDGET DATA UPDATE / REPLACE</h1>';
		// update/replace data widget
		if( $widgets )
		{
			$index = 2;
			$s = $widgets;
			if( $s['data'][$index] )
			{
				echo '<h3>update</h3>';
				$replacements = array(
					$index => array(
						'title' => 'test title 8',
						'count' => 10
					)
				);
				$replace = Model_Option::_array_replace( $s['data'], $replacements );
				Zend_Debug::dump($replace);
				
				echo '<h3>replace</h3>';
				$replacements = array(
					'data' => $replace
				);
				$widgets = Model_Option::_array_replace( $s, $replacements );
				Zend_Debug::dump($widgets);
			}
		}
		
		echo '<h1>DELETE</h1>';
		if( $widgets )
		{
			$index = 2;
			$d = $widgets;
			if( $d['data'][$index] )
			{
				$count = count($d['data']);
				if( $count == 1 )
					unset($d['data']);
				else 
					unset($d['data'][$index]);
				
				Zend_Debug::dump($d);
			}
		}
	}

	public function testmenuAction()
	{
		$menus = Model_Option::GetMenus( $this->db );
		
		// navigation
		$option = new Model_Option( $this->db );
		$option->load( 'navigation', 'option_name' );
		$navigation = $option->option_value;
		
		echo '<h1>NAVIGATION</h1>';
		Zend_Debug::dump($menus[$navigation]);
		echo '<h1>MENUS</h1>';
		Zend_Debug::dump($menus);
		
		$page = Model_Content::GetContentsByParent($this->db, array(
				'type' => Model_Content::CONTENT_PAGE, 
				'parent_in' => 0,
				'order' => 'content_order'
			)
		);
		foreach ($page AS $index => $p) 
		{
			$pages[$p['id']] = array(
				'label' => $p['name'],
				'controller' => 'index',
				'params' => array(
					'title' => $p['slug']
				),
				'route' => 'page'
			);
			
			if( $p['child'] ){
				foreach($p['child'] as $child )
			 	{
			 		$pages[$p['id']]['pages'][] = array(
						'label' => $child['content_name'],
						'controller' => 'index',
						'params' => array(
							'title' => $child['content_slug']
						),
						'route' => $p['slug']
					);
			 	}
			}
        }
		echo '<h1>MENUS 2</h1>';
		Zend_Debug::dump($pages);
		
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
	}
	
	/** 
	 * REGISTER ACTION
	 * register widgets
	 */
	public function registerAction()
	{
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		
		$option = new Model_Option($this->db);
		$widget_data = array(
			'widget_page' => array(
				'title'	  	 => 'Page',
				'description'=> 'Use this widget to add one of your pages as a widget.',
				'action' 	 => 'about',
				'use_count'  => false,
				'use_page'	 => true
			),
			'widget_menus' => array(
				'title'	  	 => 'Custom Menu',
				'description'=> 'Use this widget to add one of your custom menus as a widget.',
				'action' 	 => 'menu',
				'use_count'  => false,
				'use_menu'	 => true
			),
			'widget_search' => array(
				'title'	  	 => 'Search Site',
				'description'=> 'A search form for your site',
				'action' 	 => 'search',
				'use_count'  => false
			),
			'widget_feed' => array(
				'title'	  	 => 'Feeds',
				'description'=> 'Entries from any RSS or Atom feed ',
				'action' 	 => 'feed',
				'use_count'  => false
			),
			'widget_category' => array(
				'title'	  	 => 'Category',
				'description'=> 'A list of categories',
				'action' 	 => 'category',
				'use_count'  => false
			),
			'widget_post' => array(
				'title'	  	 => 'Recent Posts',
				'description'=> 'The most recent posts on your site',
				'action' 	 => 'post',
				'use_count'  => true
			),
			'widget_social' => array(
				'title'	  	 => 'Social',
				'description'=> 'Use this widget to add social as a widget.',
				'action' 	 => 'social',
				'use_count'  => false
			),
			'register_widgets' => array(
				'widget_sidebar_left' => array(),
				'widget_sidebar_right' => array(),
				'widget_footer_left_1' => array(),
				'widget_footer_left_2' => array(),
				'widget_footer_right_1' => array(),
				'widget_footer_right_2' => array()
			)		
		);
		
		foreach($widget_data as $key => $value)
		{
			$row['option_name'] = $key;
			$row['option_value'] = serialize($value);
			//$this->db->insert('snap_options', $row);
		}

		/*
		INSERT INTO `snap_options` (`option_name`, `option_value`) VALUES 
		('option_general', 'a:11:{s:5:"email";s:14:"admin@snap.com";s:9:"show_news";s:1:"5";s:15:"show_portfolios";s:1:"9";s:10:"front_page";s:4:"home";s:9:"news_page";s:4:"news";s:15:"portfolios_page";s:9:"portfolio";s:6:"g_mail";s:20:"dani.gojay@gmail.com";s:6:"g_pass";s:9:"123456789";s:13:"ga_profile_id";i:54153050;s:9:"rss_title";s:15:"Snaps Indonesia";s:3:"rss";a:2:{i:0;s:4:"post";i:1;s:9:"portfolio";}}'),
		('option_social', 'a:3:{s:8:"facebook";s:28:"http://www.facebook.com/snap";s:7:"twitter";s:28:"http://www.facebook.com/snap";s:8:"linkedin";s:31:"http://www.linkedin.com/in/snap";}'),
		('option_office', 'a:1:{i:0;a:4:{s:7:"address";s:53:"Jl. Mendawai I No. 40A Kebayoran Baru Jakarta Selatan";s:5:"phone";s:13:"021 - 7222337";s:3:"fax";s:13:"021 - 7253055";s:5:"email";s:0:"";}}'),
		('option_analytics', 'a:3:{s:6:"g_mail";s:20:"dani.gojay@gmail.com";s:10:"g_password";s:44:"5LBWxGSWxR96II85qeVvjUAMd0ip+NyCyvdV8W2i6+I=";s:13:"ga_profile_id";i:54153050;}'),
		('gallery_slide', 'a:3:{i:0;s:15:"slide_slide.png";i:1;s:17:"slide_1_slide.png";i:2;s:17:"slide_1_slide.png";}'),
		('widget_page', 'a:5:{s:5:"title";s:4:"Page";s:11:"description";s:53:"Use this widget to add one of your pages as a widget.";s:6:"action";s:5:"about";s:9:"use_count";b:0;s:8:"use_page";b:1;}'),
		('register_widgets', 'a:6:{s:19:"widget_sidebar_left";a:0:{}s:20:"widget_sidebar_right";a:0:{}s:20:"widget_footer_left_1";a:0:{}s:20:"widget_footer_left_2";a:0:{}s:21:"widget_footer_right_1";a:0:{}s:21:"widget_footer_right_2";a:0:{}}'),
		('widget_menus', 'a:5:{s:5:"title";s:11:"Custom Menu";s:11:"description";s:60:"Use this widget to add one of your custom menus as a widget.";s:6:"action";s:4:"menu";s:9:"use_count";b:0;s:8:"use_menu";b:1;}'),
		('widget_search', 'a:4:{s:5:"title";s:11:"Search Site";s:11:"description";s:27:"A search form for your site";s:6:"action";s:6:"search";s:9:"use_count";b:0;}'),
		('widget_feed', 'a:4:{s:5:"title";s:5:"Feeds";s:11:"description";s:34:"Entries from any RSS or Atom feed ";s:6:"action";s:4:"feed";s:9:"use_count";b:0;}'),
		('widget_category', 'a:4:{s:5:"title";s:8:"Category";s:11:"description";s:20:"A list of categories";s:6:"action";s:8:"category";s:9:"use_count";b:0;}'),
		('widget_post', 'a:4:{s:5:"title";s:12:"Recent Posts";s:11:"description";s:34:"The most recent posts on your site";s:6:"action";s:4:"post";s:9:"use_count";b:1;}'),
		('widget_social', 'a:4:{s:5:"title";s:6:"Social";s:11:"description";s:42:"Use this widget to add social as a widget.";s:6:"action";s:6:"social";s:9:"use_count";b:0;}'),
		('navigation', ''),
		('menu_navigation', 'a:2:{s:4:"name";s:10:"navigation";s:5:"menus";a:6:{i:18;a:5:{s:5:"label";s:4:"Home";s:5:"title";s:4:"Home";s:10:"controller";s:5:"index";s:6:"params";a:1:{s:5:"title";s:4:"home";}s:5:"route";s:4:"page";}i:1;a:6:{s:5:"label";s:8:"About Us";s:5:"title";s:8:"About Us";s:10:"controller";s:5:"index";s:6:"params";a:1:{s:5:"title";s:8:"about-us";}s:5:"route";s:4:"page";s:5:"pages";a:6:{i:2;a:5:{s:5:"label";s:7:"History";s:5:"title";s:7:"History";s:10:"controller";s:5:"index";s:6:"params";a:1:{s:5:"title";s:7:"history";}s:5:"route";s:8:"about-us";}i:3;a:5:{s:5:"label";s:10:"Phylosophy";s:5:"title";s:10:"Phylosophy";s:10:"controller";s:5:"index";s:6:"params";a:1:{s:5:"title";s:10:"phylosophy";}s:5:"route";s:8:"about-us";}i:4;a:5:{s:5:"label";s:7:"Mission";s:5:"title";s:7:"Mission";s:10:"controller";s:5:"index";s:6:"params";a:1:{s:5:"title";s:7:"mission";}s:5:"route";s:8:"about-us";}i:5;a:5:{s:5:"label";s:6:"Values";s:5:"title";s:6:"Values";s:10:"controller";s:5:"index";s:6:"params";a:1:{s:5:"title";s:6:"values";}s:5:"route";s:8:"about-us";}i:6;a:5:{s:5:"label";s:10:"Our People";s:5:"title";s:6:"Values";s:10:"controller";s:5:"index";s:6:"params";a:1:{s:5:"title";s:10:"our-people";}s:5:"route";s:8:"about-us";}i:7;a:5:{s:5:"label";s:17:"Strategic Partner";s:5:"title";s:17:"Strategic Partner";s:10:"controller";s:5:"index";s:6:"params";a:1:{s:5:"title";s:17:"strategic-partner";}s:5:"route";s:8:"about-us";}}}i:9;a:6:{s:5:"label";s:8:"Services";s:5:"title";s:8:"Services";s:10:"controller";s:5:"index";s:6:"params";a:1:{s:5:"title";s:8:"services";}s:5:"route";s:4:"page";s:5:"pages";a:4:{i:10;a:5:{s:5:"label";s:15:"Email Marketing";s:5:"title";s:15:"Email Marketing";s:10:"controller";s:5:"index";s:6:"params";a:1:{s:5:"title";s:15:"email-marketing";}s:5:"route";s:8:"services";}i:22;a:5:{s:5:"label";s:16:"Direct to Client";s:5:"title";s:16:"Direct to Client";s:10:"controller";s:5:"index";s:6:"params";a:1:{s:5:"title";s:16:"direct-to-client";}s:5:"route";s:8:"services";}i:24;a:5:{s:5:"label";s:21:"Sub Contractor to BTL";s:5:"title";s:21:"Sub Contractor to BTL";s:10:"controller";s:5:"index";s:6:"params";a:1:{s:5:"title";s:21:"sub-contractor-to-btl";}s:5:"route";s:8:"services";}i:23;a:5:{s:5:"label";s:18:"In-Store Marketing";s:5:"title";s:18:"In-Store Marketing";s:10:"controller";s:5:"index";s:6:"params";a:1:{s:5:"title";s:18:"in-store-marketing";}s:5:"route";s:8:"services";}}}i:19;a:5:{s:5:"label";s:4:"News";s:5:"title";s:4:"News";s:10:"controller";s:5:"index";s:6:"params";a:1:{s:5:"title";s:4:"news";}s:5:"route";s:4:"page";}i:13;a:5:{s:5:"label";s:9:"Portfolio";s:5:"title";s:9:"Portfolio";s:10:"controller";s:5:"index";s:6:"params";a:1:{s:5:"title";s:9:"portfolio";}s:5:"route";s:4:"page";}i:20;a:5:{s:5:"label";s:7:"Contact";s:5:"title";s:7:"Contact";s:10:"controller";s:5:"index";s:6:"params";a:1:{s:5:"title";s:7:"contact";}s:5:"route";s:4:"page";}}}');
		*/
	}
}





