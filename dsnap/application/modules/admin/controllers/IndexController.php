<?php

class Admin_IndexController extends Zend_Controller_Action
{

    public function init()
    {
        $this->db = Zend_Registry::get('db');
    }

    public function indexAction()
    {
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
					'route' => 'page',
					'title' => $p['name']
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
		echo '<h1>Pages 1</h1>';	
		//Zend_Debug::dump($pages);
			
		echo '<h1>Pages 2</h1>';	
		//Zend_Debug::dump($pages2);
		//Zend_Debug::dump(array($pages2[2]));
		
		// navigation
		$menus = Model_Option::GetMenus( $this->db );
		echo '<h1>Menus</h1>';	
		Zend_Debug::dump($menus);
		
		$option = new Model_Option( $this->db );
		$option->load( 'menu_navigation', 'option_name' );
		$navigation = (object) unserialize($option->option_value);
		echo '<h1>Navigations</h1>';	
		Zend_Debug::dump( is_object($navigation) );
		
		$container = new Zend_Navigation($pages2);
		$navigation = $this->view->navigation($container);
		echo $navigation->menu()->setMaxDepth(1)->setUlClass('nav');
		
			
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
    }


}

