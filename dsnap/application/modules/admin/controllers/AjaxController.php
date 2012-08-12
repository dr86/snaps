<?php
// 
//  AjaxController.php
//  thunder
//
//  AJAX HANDLER
//  
//  Created by Dani on 2012-05-06.
//  Copyright 2012 Dani. All rights reserved.
// 

class Admin_AjaxController extends Zend_Controller_Action
{

    public function init()
    {
       $this->db = Zend_Registry::get( 'db' );
    }
	
	/**
	 * Analytics
	 * tampil graph google analytics
	 */
	public function analyticsAction()
	{
		$analytics = $this->_helper->hooks->getOption( 'option_analytics' );
		
		try{
			$ga = new My_Google_Analytics(
				$analytics['g_mail'], // email
				$this->_helper->hooks->getDecrypt( $analytics['g_password'] ) // passsword
			);
			// profileID
			$ga->setProfile( 'ga:'.$analytics['ga_profile_id'] ); 
			
			// date range
			$ga->setDateRange(
				date('Y-m-d',strtotime( '-30 days' )) , 
				date('Y-m-d',strtotime('now')) 
			);
			
			// visit graph
			$visitsgraph = $ga->getReportArray(
				array(
					'dimensions' => urlencode('ga:date'),
					'metrics' => urlencode('ga:visits')
				)
			);
			
			// referrer
			$referrers = $ga->getReportArray(
				array(
					'dimensions' => urlencode('ga:city'),
					'metrics' => urlencode('ga:visits'),
					'sort' => urlencode('-ga:visits'),
					'max-results' => 5
				)
			);
			
			$this->_helper->json(array(
				'visits' => $visitsgraph, 
				'referrers' => $referrers
			));
			
		} catch(exception $e){
			$this->_helper->json( array( 'exception' => $e->getMessage()) );
		}
		
	}
	
	/**
	 * Gallery manage
	 * aksi mengatur gallery sortable
	 * untuk tampil home gallery
	 * 
	 * @param PARAM
	 * @return JSON
	 */
	public function galleryAction()
	{
		// aksi
		$act = $this->_request->getParam( 'act' );
		
		// update widget gallery
		$option = new Model_Option($this->db);	
		if( $act == 'add' ){
			// pisahkan (explode) koma, return array
			$gallery = explode( ',', $this->_request->getParam( 'dropped' ));
			$isSaved = $option->updateGallery( $gallery );
		// delete widget gallery
		} else if( $act == 'delete' ){
			$gallery = $this->_request->getParam( 'gallery' );
			$isSaved = $option->removeGallery( $gallery );
		}
		
		// response
		$status = ( $isSaved ) ? 'success' : 'error';
		$message = ( $isSaved ) ? 'Saved Succesfully' : 'Error occured. Please try again later';
		$this->_helper->json( array('status' => $status, 'message' => $message) );
	}

	/**
	 * option
	 * update value option general
	 * update value option social
	 * update value option profile
	 */
	public function optionAction()
	{
		// ambil type
		$type = $this->_request->getParam( 'type' );
		// model action
		$option = new Model_Option( $this->db );
		// ajax request
		if( $this->_request->isXmlHttpRequest() )
		{
			// inisialisasi form sesuai type
			switch ( $type ) {
				case 'option_general':
					$form = new Admin_Form_OptionGeneral();
					break;
				case 'option_contact':
					$form = new Admin_Form_OptionContact();
					break;
				case 'option_social':
					$form = new Admin_Form_OptionSocial();
					break;
				case 'option_profile':
					$form = new Admin_Form_OptionProfile();
					break;
				case 'option_analytics':
					$form = new Admin_Form_OptionAnalytics();
					break;
				default:
			}
			
			if( $form->isValid( $this->_request->getPost() ))
			{
				// ambil data input
				$data = $form->getValues();
				// simpan option
				$isSaved = $option->saveOption( $data );
				// kirim response
				$status = ( $isSaved ) ? 'success' : 'error';
				$message = ( $isSaved ) ? 'Saved Succesfully' : 'Error occured. Please try again later';
				$this->_helper->json( array('status' => $status, 'message' => $message) );
				
			} else {
				$this->_helper->json( array('status' => 'error', 'message' => 'Data invalid') );
			}
			
		} else {
			$this->_helper->json( array('status' => 'error', 'message' => 'Not AJAX Requested') );
		}
			
	}
	
	/**
	 * validate
	 */
	public function validateAction()
	{
		// ambil type
		$type = $this->_request->getParam( 'type' );
		// model action
		$option = new Model_Option( $this->db );
		// ajax request
		if( $this->_request->isXmlHttpRequest() )
		{
			// inisialisasi form sesuai type
			switch ( $type ) {
				case 'option_general':
					$form = new Admin_Form_OptionGeneral();
					break;
				case 'option_contact':
					$form = new Admin_Form_OptionContact();
					break;
				case 'option_social':
					$form = new Admin_Form_OptionSocial();
					break;
				case 'option_profile':
					$form = new Admin_Form_OptionProfile();
					break;
				case 'option_analytics':
					$form = new Admin_Form_OptionAnalytics();
					break;
			}
			
			$form->isValid( $this->_request->getPost() );
			// get messages form validasi
			$response = $form->getMessages();
			$response = array('messages' => $response) ;
			// buat validasi untuk geocoding maps dari alamat
			$maps = $this->_request->getPost('maps');
			
			if( isset($maps) && '' !== $maps )
			{
				// proses geocoding
				$geo = $this->_helper->hooks->doGeocoder( $maps );
				// jika gagal geocoding
				if( !$geo )
				{
					// tambahkan kesalahan validasi maps 
					$message = 'geocoding can not be parsed, please verify your address';
					$response = array_merge_recursive( $response, array(
													'messages' => array(
														'maps' => array(
															'message' => $message
														)
													)
					));
				} else {
					// berhasil geocoding, tambahkan response geo untuk latitude dan longtitude
					$response = array_merge_recursive( $response, array('geo' => array(
							'lat' => $geo['lat'],
							'long' => $geo['lon']
						)) 
					);
				}
			}
			// kirim output response dengan json
			$this->_helper->json( $response );
		}
	}

    public function officeAction()
    {
        $id = $this->_getParam('id');
 
		$element1 = new Zend_Form_Element_Text("office_address");
		$element1->setRequired(true)->setLabel('Address')
				 ->setAttrib('class', 'large')
				 ->setRequired(TRUE)
				 ->addFilters(array('StringTrim'))
				 ->addValidator('NotEmpty');
		$element1->setBelongsTo('other['.$id.']');
		
 		$element2 = new Zend_Form_Element_Text("office_phone");
		$element2->setRequired(true)->setLabel('Phone')
				 ->setAttrib('class', 'medium')
			     ->setRequired(TRUE)
				 ->addFilters(array('StringTrim'))
				 ->addValidator('NotEmpty');
		$element2->setBelongsTo('other['.$id.']');
 
		$element3 = new Zend_Form_Element_Text("office_fax");
		$element3->setRequired(true)
				 ->setLabel('Fax')->setAttrib('class', 'small')
			     ->setRequired(TRUE)
				 ->addFilters(array('StringTrim'))
				 ->addValidator('NotEmpty');
		$element3->setBelongsTo('other['.$id.']');
 
		$element4 = new Zend_Form_Element_Text("office_email");
		$element4->setRequired(true)->setLabel('Email')
				 ->setAttrib('class', 'small')
			   	 ->setRequired(TRUE)
				 ->addFilters(array('StringTrim'))
				 ->addValidator('NotEmpty');
		$element4->setBelongsTo('other['.$id.']');
		 
		$this->view->id = $id;
		$this->view->fields = array($element1, $element2, $element3, $element4);
		$this->_helper->layout->disableLayout();
    }

	/**
	 * Option Contact
	 * tambah value option office
	 * update value option office
	 * remove value option office
	 * 
	 * @param POST
	 * @return JSON
	 */
	public function contactAction()
	{
		// action
		$act = $this->_request->getParam('act');
		// ambil element id
		$fId = $this->_request->getParam( 'elementId' );
		// ambil index
		list($element, $value) = explode('-', $fId);
		$index = (int) str_replace('office_', '', $value);
		
		// ambil model option
		$option = new Model_Option( $this->db );
		// aksi
		switch ( $act ) 
		{
			case 'office':
				return $this->_forward('office');
				break;
				
			case 'add':
				$offices = $this->_request->getParam( 'other' );
				if ( isset($offices) ) {
					// kirim response error, jika PARAM other NOT ARRAY || kosong
					if( !is_array( $offices ) ){
						$this->_helper->json( array('status' => 'attention', 'message' => 'Data is empty') );
					}
					
					$values = array();
					// looping dan set ke dalam array
					foreach($offices as $office)
					{
						$values[] = array(
							'city' => $office['office_city'],
							'address' => $office['office_address'],
							'phone' => $office['office_phone'],
							'fax' => $office['office_fax'],
							'email' => $office['office_email']
						);
					}
					// add option_office
					$isSaved = $option->addOffice( $values );
				}
				
				break;
				
			case 'update':
				// ambil post data
				$data = $this->_request->getParam( 'data' );
				// buat array untuk replace
				$contact = array(
					'city' => $data['office_city_'.$index],
					'address' => $data['office_address_'.$index],
					'phone' => $data['office_phone_'.$index],
					'email' => $data['office_email_'.$index],
					'fax' => $data['office_fax_'.$index]
				);
				
				$isSaved = $option->replaceOffice( $index, $contact );
				break;
			
			case 'remove':
				$isSaved = $option->removeOffice( $index );
				break;
				
			default:
				$this->_helper->json( array('status' => 'error', 'message' => 'Invalid request !!!') );
		}
		
		$status = ( $isSaved ) ? 'success' : 'error';
		$message = ( $isSaved ) ? 'Saved Succesfully' : 'Error occured. Please try again later';
		$this->_helper->json( array('ok' => $isSaved, 'status' => $status, 'message' => $message) );
	}

	/**
	 * Widget
	 * sort widget
	 * save widget
	 */
	public function widgetAction()
	{
		$act = $this->_request->getParam('act');
		
		$widget = new Model_Option($this->db);
		
		switch ($act) 
		{
			case 'sort':
				$widget_name = $this->_request->getParam('widget');
				$items 	= $this->_request->getParam('data');
				
				// buat array items
				if( $items )
					$items = explode(',', $items);	
				
				// sorting
				$response = $widget->sortWidget( $items, $widget_name );
				break;
			
			case 'save':
				$action = $this->_request->getParam('act_widget');
				$widget_id = $this->_request->getParam('id');
				$data = array(
					'title' => $this->_request->getParam('title'),
					'count' => (int) $this->_request->getParam('count'),
					'page' => (int) $this->_request->getParam('page'),
					'menu' => $this->_request->getParam('menu')
				);
				switch($action)
				{
					case 'add-widget':
						$isSaved = $widget->saveWidget( $widget_id, 'add', $data );
						$response = array( 'ok' => $isSaved );
						break;
						
					case 'update-widget':
						$isSaved = $widget->saveWidget( $widget_id, 'update', $data  );
						$response = array(
							'ok' => $isSaved, 
							'message' => ($isSaved) ? 'Saved successfully' : 'Error occured'
						);
						break;
						
					case 'delete-widget':
						$isSaved = $widget->saveWidget( $widget_id, 'delete' );
						$response = array( 'ok' => $isSaved );
						break;
				}
				break;
		}
				
		$this->_helper->json( $response );
	}

	/**
	 * Custom Menu
	 * 
	 * tambah page link
	 * tambah page content
	 * set navigation
	 * save menu
	 * update menu
	 * remove menu
	 * 
	 */
	public function menuAction()
	{
		$this->_helper->layout->disableLayout();
		
		$action = $this->_request->getParam('act');
		
		switch ( $action ) {
			/* tambah page link */
			case 'add-page-link':
				$link_id = $this->_request->getParam('id');
				$link_label = $this->_request->getParam('label');
				$link_url = $this->_request->getParam('url');
				
				$links = array(
					'content_id' => $link_id,
					'content_name' => $link_label,
					'content_url' => $link_url,
					'content_parent' => 0,
					'content_type' => 'link'
				);
				
				$this->view->links = $links;
				break;
				
			/* tambah page */
			case 'add-page':
				$page = $this->_request->getParam('page');
				// ambil page sesuai id
				$pages = Model_Content::GetContents( $this->db, array('in' => $page));
				$this->view->pages = $pages;
				break;
				
			/* set navigation */
			case 'navigation':
				$menu = $this->_request->getParam('select-menu');
				
				$option = new Model_Option( $this->db );
				$option->load( 'navigation', 'option_name' );
				$option->option_value = $menu;
				$response = $option->save();
					
				$this->_helper->json( $response );
				break;
				
			/* save menu */
			case 'save':
				
				// parameter
				$menu = $this->_request->getParam('menu');	 // string
				$label = $this->_request->getParam('label'); // nilai array
		        $title = $this->_request->getParam('title'); // nilai array
		        $pages = $this->_request->getParam('page');  // nilai array
		        $url = $this->_request->getParam('url'); 	 // nilai array
				
				// ambil kontent sesuai id
				// kontent id diambil dari ARRAY KEY pages (parameter)
				$contents = Model_Content::GetContentEntries( $this->db, array('in' => array_keys($pages)));
				
				/* BUAT ARRAY UNTUK NAVIGATION */
				foreach( $pages as $id => $val )
				{
					// data label navigation 
					// isi sesuai id parameter label
					$data = array( 
						'label' => $label[$id],
						'title' => $title[$id]
					);
					
					if( $url[$id] )
					{
						// tambah item page dari "Custom Links"
						// parameter url sesuai id
						// isi array hanya uri
						$uri = array( 'uri' => $url[$id] );
					
					} else {
						// tambah item page dari "Pages"
						// isi array adalah controller, params, route
						$uri = array(
							'controller' => 'index',
							'params' => $contents[$id]['params'],
							'route' => 'page'
						);
					}
						
					// tambahkan array data navigation uri
					$data = array_merge_recursive($data, $uri);
						
					/* PISAHKAN ITEM PAGE SESUAI PARENT DAN CHILD */
						
					// parent adalah array value nilai "root"  
					if( $val == 'root' )
					{
						$menus[$id] = $data;
					/* 
					 * children adalah array value bukan root/bernilai id
					 * children dalam navigation adalah "pages" 
					 */
					} else {
						// nilai id parent adalah variable val ($val)  
						// nilai content_slug pada contents adalah $contents[$val][params][title]
						// gunakan array_replace untuk ubah/replace nilai route children dengan content_slug parentnya
						if( array_key_exists($val, $menus )){
							// item depth level 2
							$menus[$val]['pages'][$id] = Model_Option::_array_replace(
								$data, array( 'route' => $contents[$val]['params']['title'] )
							);
							$parent = $val; // simpan value parent
						} elseif( array_key_exists($val, $menus[$parent]['pages'] )) {
							// jika key menu tidak ditemukan, maka item depth level 3
							$menus[$parent]['pages'][$val]['pages'][$id] = Model_Option::_array_replace( 
								$data, array( 'route' => $contents[$parent]['params']['title'] )
							);
						}
					}
				}
				// end looping
					
				// buat nama menu
				list($key, $menu_name) = explode('_', $menu);
				// buat data menu	
				$data_menu = array(
					'name' => $menu_name,
					'menus' => $menus
				);
				
				/* SAVE */	
				$option = new Model_Option( $this->db );
				// ambil menu utk update data
				$option->load($menu, 'option_name');
				$option->option_value = serialize($data_menu);
				$response = $option->save();
					
				$this->_helper->json( $response );
				break;
				
			/* update menu */
			case 'update':
				$menu = $this->_request->getParam('menu');
				$name = $this->_request->getParam('name');
				
				// nama menu
				$name = strtolower($name);
				
				$option = new Model_Option( $this->db );
				$option->load( $menu, 'option_name' );
				// ubah value name dgn ARRAY_REPLACE
				$value = unserialize($option->option_value);
				$value = Model_Option::_array_replace( $value, array( 'name' => $name ));
				
				/* SAVE */
				$option->option_value = serialize($value);
				$isSaved = $option->save();
				
				$response = array(
					'ok' 	=> $isSaved,
					'name' 	=> ucwords($name)
				);
				
				$this->_helper->json( $response );
				break;
				
			/* remove menu */
			case 'remove':
				$menu = $this->_request->getParam('menu');
				
				$option = new Model_Option( $this->db );
				$option->load( $menu, 'option_name' );
				$response = $option->delete();
				
				$this->_helper->json( $response );
				break;
		}
		
	}
	
}

