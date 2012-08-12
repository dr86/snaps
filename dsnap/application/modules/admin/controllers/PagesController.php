<?php

class Admin_PagesController extends Zend_Controller_Action
{

    public function init()
    {
        $this->db = Zend_Registry::get( 'db' );
    }

    public function indexAction()
    {
    	$this->view->headTitle('Pages');
		
		// ambil semua kontent tipe page
		$data = Model_Content::GetContentsParentChild( $this->db, array(
																	'type' => Model_Content::CONTENT_PAGE, 
																	'order' => 'content_id',
																	'status' => null
																) 
													);
		$this->view->data = $data;
    }

    public function addAction()
    {
    	$this->view->headTitle('Add Page');
		
        // definisi model upload, parameter form
		$modelUpload = new Model_UploadImage('Admin_Form_Page');
		// ambil form
		$form = $modelUpload->getForm();
		// atribut ajax url untuk validasi
		$form->setAttrib( 'ajax-url', $this->_helper->url->simple( 'validate' ) );
		// set form action
		$form->setAction( $this->_helper->url->simple( 'add' ) );
		
		// remove image
		$form->removeElement('image');
		// remove image_preview
		$form->removeElement('image_preview');
		// remove file
		$form->removeElement('file');
		
		// proses
		if( $this->_request->isPost() && 
			$form->isValid($this->_request->getPost()))
		{
			// nama file gambar dari nama galleri
			//$modelUpload->setName( $form->getValue( 'content_name' ) );
			//$modelUpload->upload();
			// data
			$data = $form->getValues();
			// array merge data image
			// $data = array_merge($form->getValues(), array('content_image' => $modelUpload->getFileName()));
			// save
			$content = new Model_Content( $this->db );
			$isSaved = $content->savePage( $data );
			if( $isSaved ){
				$this->_helper->FlashMessenger( array('success' => 'Page added') );
			} else {
				$this->_helper->FlashMessenger( array('error' => 'Error occured. Please try again later') );
			}
			
			/* REDIRECT */
			$this->_helper->redirector->gotoSimple( $data['redirect'], 'pages', 'admin' );
		}
		
		$this->view->form = $form;
    }

    public function editAction()
    {
    	$this->view->headTitle('Edit Page');
		
		// ambil id
		$id = $this->_request->getParam('id');
		$page = new Model_Content( $this->db );
		// cek page. jika tidak ada redirect index dan krimi pesan error
		if( !$page->load( $id ) )
		{
			//$this->getResponse()->setHttpResponseCode(404);
			//throw new Zend_Controller_Action_Exception('This page dont exist',404);
			$this->_helper->FlashMessenger( array( 'error' => 'Page not found' ) );
			$this->_redirect( '/admin/pages/' );
		}
		// definisi model uopload image, parameter form
		$modelUpload = new Model_UploadImage( 'Admin_Form_Page' );
		// ambil form
		$form = $modelUpload->getForm();
		// attribut ajax url untuk ajax validasi
		$form->setAttrib( 'ajax-url', $this->_helper->url->simple( 'validate' ) );
		// set form action
		$form->setAction( $this->_helper->url->simple( 'edit' ) . '/id/' . $id );
		// populate form
		$form->populate( $page->toArray() );
		
		// remove image
		$form->removeElement('image');
		// remove image_preview
		$form->removeElement('image_preview');
		// show element image untuk preview
		/*if($page->content_image)
		{
			$imagePreview = $form->getElement( 'image_preview' );
			$imagePreview->setAttrib( 'src', $this->view->baseUrl() . '/uploads/image/' . $page->content_image )
					 ->setAttrib( 'style', 'display:block; width:200px; height:auto;' );
		}*/
		// tambah opsi redirect edit, dan selected
		$redirect = $form->getElement( 'redirect' );
		$redirect->addMultiOption('edit', 'Edit');
		$redirect->setValue('edit');
		// remove file
		$form->removeElement('file');
		
		// proses
		if( $this->_request->isPost() && 
			$form->isValid($this->_request->getPost()))
		{
			$name = $form->getValue( 'content_name' );
			// set nama file gambar dari nama page
			//$modelUpload->setName($name);
			//$modelUpload->upload();
			// data
			$data = $form->getValues();
			// array merge data image
			/*if( $form->image->isUploaded() )
			{
				$data = array_merge( $form->getValues(), array( 'content_image' => $modelUpload->getFileName() ));
			}*/
			// save
			$isSaved = $page->savePage( $data );
			if($isSaved){
				$this->_helper->FlashMessenger(array('success' => sprintf('Page "%s" successfully updated', $name)));
			} else {
				$this->_helper->FlashMessenger(array('error' => 'Error occured. Please try again later'));
			}
			
			/* REDIRECT */
			// jika edit set parameter
			$params = ( $data['redirect'] == 'edit') ? array('id' => $data['content_id']) : array();
			$this->_helper->redirector->gotoSimple( $data['redirect'], 'pages', 'admin', $params );
		}
		
		$this->view->form = $form;
    }

    public function deleteAction()
    {
        $id = $this->_request->getParam( 'id' );
		$page = new Model_Content( $this->db );
		// cek evnt
		if( !$page->load( $id ) ){
			$this->_helper->FlashMessenger( array('error' => 'Page not found') );
		} else {
			if( $page->delete() )
				$this->_helper->FlashMessenger( array('success' => 'Page deleted') );
			else
				$this->_helper->FlashMessenger( array('error' => 'Error occured. Please try again') );
		}
		
		/* REDIRECT */
		$this->_helper->redirector->gotoSimple( 'index', 'pages', 'admin' );
    }

	public function validateAction()
	{
		$form = new Admin_Form_Page();
		$form->isValid( $this->_request->getPost() );
		// get messages form validasi
		$response = $form->getMessages();
		// kirim output response dengan json
		$this->_helper->json( $response );
	}


}







