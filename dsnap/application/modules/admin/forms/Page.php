<?php

class Admin_Form_Page extends My_Form
{

    public function init()
    {
		// set form atribut 
        $this->setAttrib('enctype', 'multipart/form-data');
		
		// id : input hidden
		$id = $this->createElement('hidden', 'content_id');
		// type : input hidden
		$type = $this->createElement('hidden', 'content_type');
		
		// parent : select
		$parent = $this->createElement('select', 'content_parent')
							->setLabel('Parent')
							->setDecorators($this->decorators)
							->addMultiOption(0, 'None');
		/* Ambil kontent parent */
		$db = Zend_Registry::get( 'db' );
		$parents = Model_Content::GetContents( $db, array('type' => 'page', 'parent_in' => 0, 'order' => 'content_id') );
		if($parents)
		{
			foreach($parents as $p)
			{
				// tambahkan option
				$parent->addMultiOption($p->getId(), ucwords($p->content_name));
			}
		}
		
		// name : input text => class small
		$name = $this->createElement('text', 'content_name')
					 ->setLabel('Title')
			 		 ->setAttrib('class', 'small')
					 ->setRequired(TRUE)
					 ->addFilters(array('StringTrim'))
					 ->addValidator('NotEmpty')
					 ->setDecorators($this->decorators);
		// gambar : input file => class fileupload
		$image = $this->createElement('file', 'image')
					  ->setLabel('Image')
					  ->setDescription('Choose a feature image')
			 		  ->setAttrib('class', 'fileupload')
					  ->addValidator('Count', false, 2)
					  ->addValidator('Size', false, '1MB')
					  ->addValidator('Extension', false, 'jpeg, jpg, png, gif')
					  ->setDestination(APPLICATION_PATH . '/../public/uploads/image')
					  ->setDecorators($this->decorators_file);
		// preview
		$preview = new My_Form_Element_Img( 'image_preview' );
		$preview->setAttrib( 'style', 'display:none; width:200px; height:auto;' )
				->setDecorators( $this->decorators );
				
		// attach : input file => class fileupload			
		$file = $this->createElement('file', 'file')
					  ->setLabel('Attach a file')
					  ->setDescription('You can attach a document file pdf, docx, doc. Max file size 2MB')
			 		  ->setAttrib('class', 'fileupload')
					  ->addValidator('Size', false, '2MB')
					  ->addValidator('Extension', false, 'pdf, docx, doc')
					  ->setDestination(APPLICATION_PATH . '/../public/uploads/file')
					  ->setDecorators($this->decorators_file);
					  
		// twitter_timeline_information => html
		$attach_file = new My_Form_Element_Html('attach_file');
		$attach_file->setAttrib('style', 'display:none;');
			
		// description : fckeditor
		$description = new My_Form_Element_FCKEditor('content_description');
		$description->setAttrib('width', 700)
				    ->setAttrib('toolbar', 'ZF')
				    ->setDecorators($this->decorators_jquery)
				    ->setLabel('Description');
		
		// status : select
		$status = $this->createElement('select', 'content_status')
							->setLabel('Status')
							->setDecorators($this->decorators)
							->addMultiOption('L', 'Publish')
							->addMultiOption('D', 'Draft');
		// Show Portfolios : select
		$order = $this->createElement('text', 'content_order')
					  ->addValidator('Int')
			 		  ->setAttrib('style', 'width:20px')
					  ->setLabel('Order')
					  ->setValue(0)
					  ->setDecorators($this->decorators);
		
		// redirect : select
		$redirect = $this->createElement('select', 'redirect')
				       ->setLabel('Direct')
					   ->addMultiOptions(array(
					   		'add' => 'Add new',
					   		'index' => 'Back'
					   ))
					   ->setDecorators($this->decorators);
		
		// submit : button
		$submit = $this->createElement('submit', 'submit')
					   ->setAttrib('class', 'button')
					   ->setDecorators(array('ViewHelper'));
		
		// add Elements
		$this->addElements(array(
			//$type,
			$parent,
			$name,
			$image,
			$preview,
			$file,
			$attach_file,
			$description,
			$id,
			$order,
			$status,
			$redirect,
			$submit
		));
		
		// add group content
		$this->addDisplayGroup(array(
			//'content_type',
			'content_parent',
			'content_name',
			'image',
			'image_preview',
			'file',
			'attach_file',
			'content_description',
			'content_id',
			'content_type'
		), 
		'content', 
		array( 
			'legend' => 'Content',
		 	'decorators' => array(
				'FormElements',
				'Fieldset'
			)
		));
		
		// add group redirect
		$this->addDisplayGroup(array(
			'content_order',
			'content_status',
			'redirect',
		),
		'action', 
		array( 
			'legend' => 'Choose one of these action',
		 	'decorators' => array(
				'FormElements',
				'Fieldset'
			)
		)); 
		
		// add group submit
		$this->addDisplayGroup(array(
			'submit',
		), 'button')
		->getDisplayGroup('button')
		->setDecorators(array('FormElements'));	
    }

}


