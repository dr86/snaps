<?php

class Admin_NewsController extends Zend_Controller_Action
{
    public function init()
    {
        $this->db = Zend_Registry::get( 'db' );
    }

    public function indexAction()
    {
    	$this->view->headTitle('News');
		
		$options = array(
			'show' 	=> 10,
			'range' => 3,
			'page' 	=> $this->_request->getParam('page'),
			'type'  => Model_Content::CONTENT_POST
		);
        $paginator = Model_Content::GetPagination( $this->db, $options );
		$this->view->paginator = $paginator;
		
        $data = Model_Content::GetContents( $this->db, $options );
		$this->view->data = $data;
    }

    public function addAction()
    {
    	$this->view->headTitle('Add News');
		
        // definisi model upload, parameter form
		$modelUpload = new Model_UploadImage('Admin_Form_Page');
		// ambil form
		$form = $modelUpload->getForm();
		// atribut ajax url untuk validasi
		$form->setAttrib( 'ajax-url', $this->_helper->url->simple( 'validate' ) );
		// tambah atribut "use" untk element image. set false jika tidak memerlukan ajax validasi
		$form->image->setAttrib('use', 'false');
		// set form action
		$form->setAction( $this->_helper->url->simple( 'add' ) );
		// remove element parent
		$form->removeElement('content_parent');
		// remove file
		$form->removeElement('file');
		// remove order
		$form->removeElement('content_order');
		
		// proses
		if( $this->_request->isPost() && 
			$form->isValid($this->_request->getPost()))
		{
			// nama file gambar dari nama galleri
			$modelUpload->setName( $form->getValue( 'content_name' ) );
			$modelUpload->upload();
			// data
			$data = $form->getValues();
			// array merge data image
			$data = array_merge($form->getValues(), array('content_image' => $modelUpload->getFileName()));
			// save post
			$post = new Model_Content( $this->db );
			$isSaved = $post->savePost( $data );
			if( $isSaved ){
				$this->_helper->FlashMessenger( array('success' => 'News added') );
			} else {
				$this->_helper->FlashMessenger( array('error' => 'Error occured. Please try again later') );
			}
			/* REDIRECT */
			$this->_helper->redirector->gotoSimple( $data['redirect'], 'news', 'admin' );
		}
		
		$this->view->form = $form;
    }

    public function editAction()
    {
    	$this->view->headTitle('Edit News');
		
		// ambil id
		$id = $this->_request->getParam('id');
		$post = new Model_Content( $this->db );
		// cek news. jika tidak ada redirect index dan krimi pesan error
		if( !$post->load( $id ) )
		{
			$this->_helper->FlashMessenger( array( 'error' => 'News not found' ) );
			$this->_redirect( '/admin/news/' );
		}
		
		// definisi model uopload image, parameter form
		$modelUpload = new Model_UploadImage( 'Admin_Form_Page' );
		// ambil form
		$form = $modelUpload->getForm();
		// attribut ajax url untuk ajax validasi
		$form->setAttrib( 'ajax-url', $this->_helper->url->simple( 'validate' ) );
		// tambah atribut "use" untk element image. set false jika tidak memerlukan ajax validasi
		$form->image->setAttrib('use', 'false');
		// set form action
		$form->setAction( $this->_helper->url->simple( 'edit' ) . '/id/' . $id );
		// populate form
		$form->populate( $post->toArray() );
		
		// remove element parent
		$form->removeElement('content_parent');
		// remove file
		$form->removeElement('file');
		// remove order
		$form->removeElement('content_order');
		
		// show element image untuk preview
		if($page->content_image)
		{
			$imagePreview = $form->getElement( 'image_preview' );
			$imagePreview->setAttrib( 'src', $this->view->baseUrl() . '/uploads/image/' . $page->content_image )
					 	 ->setAttrib( 'style', 'display:block; width:200px; height:auto;' );
		}
		// tambah opsi redirect edit, dan selected
		$redirect = $form->getElement( 'redirect' );
		$redirect->addMultiOption('edit', 'Edit');
		$redirect->setValue('edit');
		
		// proses
		if( $this->_request->isPost() && 
			$form->isValid($this->_request->getPost()))
		{
			$name = $form->getValue( 'content_name' );
			// set nama file gambar dari nama news
			$modelUpload->setName($name);
			$modelUpload->upload();
			// data
			$data = $form->getValues();
			// array merge data image
			if( $form->image->isUploaded() )
			{
				$data = array_merge( $form->getValues(), array( 'content_image' => $modelUpload->getFileName() ));
			}
			// save
			$isSaved = $post->savePost( $data );
			if($isSaved){
				$this->_helper->FlashMessenger(array('success' => sprintf('News "%s" successfully updated', $name)));
			} else {
				$this->_helper->FlashMessenger(array('error' => 'Error occured. Please try again later'));
			}
			
			/* REDIRECT */
			// jika edit set parameter
			$params = ( $data['redirect'] == 'edit') ? array('id' => $data['content_id']) : array();
			$this->_helper->redirector->gotoSimple( $data['redirect'], 'news', 'admin', $params );
		}
		
		$this->view->form = $form;
    }

    public function deleteAction()
    {
        $id = $this->_request->getParam( 'id' );
		$post = new Model_Content( $this->db );
		// cek post
		if( !$post->load( $id ) ){
			$this->_helper->FlashMessenger( array('error' => 'News not found') );
		} else {
			if( $post->delete() )
				$this->_helper->FlashMessenger( array('success' => 'News deleted') );
			else
				$this->_helper->FlashMessenger( array('error' => 'Error occured. Please try again') );
		}
		
		/* REDIRECT */
		$this->_helper->redirector->gotoSimple( 'index', 'news', 'admin' );
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
