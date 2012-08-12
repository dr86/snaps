<?php
class Model_Content extends My_Db_Object_Abstract
{
	public $profile;
	public $contentImage;
	public $images = array();
	
	protected $_content_name;
	protected $_children;
	
	const STATUS_DRAFT = 'D';
	const STATUS_LIVE  = 'L';
	
	const CONTENT_PAGE = 'page';
	const CONTENT_POST = 'post';
	const CONTENT_GALLERY = 'portfolio';
	
	public function __construct($db)
	{
		parent::__construct($db, 'snap_contents', 'content_id');
		
		$this->add('content_parent');
		$this->add('content_type');
		$this->add('content_slug');
		$this->add('content_name');
		$this->add('content_image');
		$this->add('content_description');
		$this->add('content_order');
		$this->add('content_date', time(), self::TYPE_TIMESTAMP);
		$this->add('content_status');
		
		$this->profile = new Model_Profile_Content($db);
	}
	
	/**
	 * postLoad
	 * 
	 * setelah proses load content
	 * load profile
	 * load images
	 */
	protected function postLoad()
	{
		$this->_content_name = $this->content_name;
		
		// profile
		$this->profile->setParentId($this->getId());
		$this->profile->load();
		
		// images
		$this->contentImage = new Model_ContentImage($this->_db);
		$this->images = Model_ContentImage::GetImages($this->_db, array('content_id' => $this->getId()));
	}

	/**
	 * preInsert
	 * 
	 * sebelum proses insert content
	 * buat slug dari nama
	 */
	protected function preInsert()
	{
		$this->content_slug = $this->_generateSlug($this->content_name);
		return true;
	}
	
	/**
	 * postInsert
	 * 
	 * Setelah proses insert content
	 * Set content ID untuk insert profile data.
	 */
	protected function postInsert()
	{
		// profile
		$this->profile->setParentId($this->getId());
		$this->profile->save(false);
		return true;
	}
	
	/**
	 * preUpdate
	 * 
	 * Sebelum proses update content
	 * Jika nama kontent tidak sama, buat slug baru
	 */
	protected function preUpdate()
	{
		if( $this->content_name != $this->_content_name )
			$this->content_slug = $this->_generateSlug($this->content_name);
		
		return true;
	}
	
	/**
	 * postUpdate
	 * 
	 * Setelah proses update content 
	 * Set content ID untuk update profile data.
	 * tambah index
	 */
	protected function postUpdate()
	{
		$this->profile->save(false);
		return true;
	}
	
	/**
	 * preDelete
	 * 
	 * Sebelum proses delete content, 
	 * Hapus semua profile data. 
	 * Hapus semua data images.
	 */
	protected function preDelete()
	{
		if( $this->content_parent == 0 ) 
		{
			$childs = self::GetContents($this->_db, array('parent_in' => $this->getId()));
			if( $childs )
			{
				foreach( $childs as $child )
				{
					$child->delete();
				}
			}
		} 
		
		$this->profile->delete();
		
		if( $this->images ) 
		{
			// hapus semua gambar terkait
			foreach ( $this->images as $image )
			{
				$image->delete(false);
			}
		}
		
		return true;
	}
	
	/**
	 * savePage
	 * Tambah/update data Page  
	 * 
	 * @param array data
	 * @return boolean
	 */
	public function savePage( array $data )
	 {
	 	$id = (int) $data['content_id'];
		if( $id > 0) {
			$this->load( $id );
		}
		
		$this->content_type = self::CONTENT_PAGE;
		$this->content_parent = $data['content_parent'];
		$this->content_name = $data['content_name'];
		$this->content_image = $data['content_image'];
		$this->content_description = $data['content_description'];
		$this->content_order = $data['content_order'];
		$this->content_status = $data['content_status'];
		
		return $this->save();		
	 }
	
	/**
	 * savePost
	 * Tambah/update data Post  
	 * 
	 * @param array data
	 * @return boolean
	 */
	public function savePost( array $data )
	 {
	 	$id = (int) $data['content_id'];
		if( $id > 0) {
			$this->load( $id );
		}
		
		$this->content_type = self::CONTENT_POST;
		$this->content_parent = 0;
		$this->content_name = $data['content_name'];
		$this->content_image = $data['content_image'];
		$this->content_description = $data['content_description'];
		$this->content_status = $data['content_status'];
		
		return $this->save();		
	 }
	
	/**
	 * saveGallery
	 * Tambah/update data Gallery  
	 * 
	 * @param array data
	 * @return boolean
	 */
	public function saveGallery( array $data )
	 {
	 	$id = (int) $data['content_id'];
		if( $id > 0) {
			$this->load( $id );
		}
		
		$this->content_type = self::CONTENT_GALLERY;
		$this->content_parent = $data['content_parent'];
		$this->content_name = $data['content_name'];
		
		if( isset($data['content_image']) )
			$this->content_image = $data['content_image'];
		
		$this->content_description = $data['content_description'];
		$this->content_status = $data['content_status'];
		
		return $this->save();		
	 }
	
	/**
	 * sendLive
	 * ubah status LIVE, serta ubah waktu sekarang
	 */
	public function sendLive()
	{
		if ($this->status != self::STATUS_LIVE) {
			$this->status = self::STATUS_LIVE;
			$this->date = time();
		}
	}
	
	/**
	 * isLive
	 * cek status LIVE
	 * 
	 * @return boolean
	 */
	public function isLive()
	{
		return $this->isSaved() && $this->status == self::STATUS_LIVE;
	}

	/**
	 * sendBackToDraft
	 * ubah status DRAFT
	 */
	public function sendBackToDraft()
	{
		$this->status = self::STATUS_DRAFT;
	}
	
	/**
	 * getGalleries
	 * 
	 * @return array
	 */
	public function getGalleries()
	{
		$galleries = array();
		$images = Model_ContentImage::GetImages( $this->_db, array('content_id' => $this->content_id) );
		
		if($images)
		{
			foreach($images as $image){
				$galleries[] = array(
					'id' => $image->getId(),
					'filename' => $image->filename
				);
			}
		}
		
		return $galleries;
	}

	/**
	 * hasChild
	 * 
	 * @return boolean
	 */
	public function hasChild()
	{
		$this->_children = Model_Content::GetContents( $this->_db, array(
			'parent_in' => $this->getId()) 
		);
		return count($this->_children) > 0;
	}

	/**
	 * getChild
	 * 
	 * @return array
	 */
	public function getChild()
	{
		return $this->_children;
	}

	/**
	 * isChild
	 * 
	 * @return boolean
	 */
	public function isChild()
	{
		return $this->content_parent != 0;
	}
	
	/**
	 * getParent
	 * 
	 * @return object
	 */
	public function getParent()
	{
		if( !$this->isChild() )
			return null;
		
		$page = new Model_Content( $this->_db );
		$page->load($this->content_parent);
		return $page;
	}
	
	public function isFrontPage()
	{
		$option = new Model_Option( $this->_db );
		$option->load( 'option_general', 'option_name' );
		$general = unserialize($option->option_value);
		
		$is_front_page = $general['front_page'];
		
		return $this->content_slug == $is_front_page;
	}
	
	public function getUri()
	{
		if( $this->isFrontPage() )
			$URI = '';
		else {
			if( $this->isChild() )
			{
				$parent = $this->getParent();
				$URI = $parent->content_slug . '/' . $this->content_slug;
			} 
			else
				$URI = $this->content_slug;
		}
		
		return $URI;
	}

	/**
	 * 
	 * ============================= QUERY Contents =============================
	 * 
	 */
	 
	/**
	 * GetContentsCount
	 * ambil jumlah data
	 * 
	 * @param db
	 * @param array
	 * 
	 * @return int
	 */
	public static function GetContentsCount($db, $options = array())
	{
		$select = self::_GetBaseQuery($db, $options);
		$select->from(null, 'count(*)');

		return $db->fetchOne($select);
	}
	
	/**
	 * GetContents
	 * ambil semua data content, profile, dan images
	 * 
	 * @param db
	 * @param array
	 * 
	 * @return object
	 */
	public static function GetContents($db, $options = array())
	{
		// initialize the options
		$defaults = array(
			'offset' => 0,
			'limit'  => 0,
			'order'  => 'c.content_date desc'
		);

		foreach ($defaults as $k => $v) {
			$options[$k] = array_key_exists($k, $options) ? $options[$k] : $v;
		}

		$select = self::_GetBaseQuery($db, $options);
		$select->from(null, 'c.*');
		// limit
		if ($options['limit'] > 0)
			$select->limit($options['limit'], $options['offset']);
			
		$select->order($options['order']);
		$select->group('c.content_id');
		$data = $db->fetchAll($select);
		
		// get content ids
		$contents = self::BuildMultiple($db, __CLASS__, $data);		
		return $contents;
	}

	/**
	 * GetContentsParentChild
	 * ambil kontent parent child
	 *  
	 * @param db
	 * @param array
	 */
	public static function GetContentsParentChild($db, $options = array())
	{
		$contents = self::GetContents($db, $options);
		$cnts = array();
		foreach ($contents as $content) 
		{
			if ($content->content_parent == 0) 
			{
				// create a new array for each top level content
				$cnts[$content->getId()] = array(
					'parent' 	=> $content->toArray(), 
					'children' 	=> array()
				);
			} else {
				// the child content are put int the parent content's array
				$cnts[$content->content_parent]['children'][] = $content->toArray();	
			}
		}
		
		return $cnts;
	}
	
	/**
	 * GetContentsByParent
	 * ambil kontent berdasar parent
	 *  
	 * @param db
	 * @param array
	 */
	public static function GetContentsByParent($db, $options = array())
	{
		$contents = self::GetContents($db, $options);
		$parentChild = self::GetContentsParentChild($db, array('order' => 'c.content_id'));
		
		$results = array();		
		foreach($contents as $c)
		{
			$results[$c->content_name] = array(
				'id' => $c->getId(),
				'name' => $c->content_name,
				'slug' => $c->content_slug,
				'order' => $c->content_order,
				'child' => self::array_search_recursive($parentChild, 'content_name', $c->content_name)
			);
		}
		
		return $results;
	}
	
	/**
	 * GetContentsToPopulate
	 * ambil kontent untuk populate select dengan group
	 *  
	 * @param db
	 * @param array
	 */
	public static function GetContentsToPopulate($db, $options = array())
	{
		// initialize the options
		$defaults = array(
			'type' => 'page', 
			'parent_in' => 0, 
			'order' => 'content_id'
		);

		foreach ($defaults as $k => $v) {
			$options[$k] = array_key_exists($k, $options) ? $options[$k] : $v;
		}
		
		// get contents by parent
		$pages = Model_Content::GetContentsByParent( $db, $options );
		foreach ( $pages as $index => $p ) 
		{
			$populate[] = $p['name'];
			if($p['child'])
			{
				foreach( $p['child'] as $child )
				{
					$populate['Sub '.$p['name']][$child['content_id']] = $child['content_name'];
				}
			} 
			
		}
		
		return $populate;
	}

	/**
	 * GetContentEntries
	 * ambil data entri untuk navigation, breadcrumbs, sitemap
	 * 
	 * @param db
	 * @param array
	 * 
	 * @return array
	 */
	public static function GetContentEntries($db, $options)
	{
		$data = self::GetContents($db, $options);
		$entries = array();
		foreach($data as $content){
			$entries[$content->getId()] = array(
				'label'  		=> $content->content_name,
				'params'  		=> array('title' => $content->content_slug)
			);
		}
		
		return $entries;
	}
	
	/**
	 * GetPaginationContents
	 * ambil data content, profile, dan images untuk Paginator
	 * 
	 * @param db
	 * @param array
	 * 
	 * @return object Paginator
	 */
	public static function GetPagination($db, $options = array())
	{
		// initialize the options
		$defaults = array(
			'page' 	 => 1,
			'show' 	 => 10,
			'range'  => 3
		);

		foreach ($defaults as $k => $v) {
			$options[$k] = array_key_exists($k, $options) ? $options[$k] : $v;
		}
		
		$data = self::GetContents($db, $options);
		$contents = array();
		foreach($data as $content){
			$contents[] = array(
				'id'  			=> $content->getId(),
				'name'  		=> $content->content_name,
				'slug'  		=> $content->content_slug,
				'image'  		=> $content->content_image,
				'gallery'  		=> Model_ContentImage::GetImages( $db, array('content_id' => $content->getId()) ),
				'description'  	=> $content->content_description,
				'status'  		=> $content->content_status,
				'date'  		=> $content->content_date,
				'profile' 		=> $content->profile,
			);
		}
		// paginator
		$paginator = Zend_Paginator::factory($contents);
		$paginator->setItemCountPerPage($options['show'])
				  ->setCurrentPageNumber($options['page'])
				  ->setPageRange($options['range']);	
				  
		return $paginator;
	}
	
	/**
	 * _GetBaseQuery
	 * Basis query select
	 * 
	 * @param db
	 * @param array
	 * 
	 * @return string select
	 */
	private static function _GetBaseQuery($db, $options)
	{
		// initialize the options
		$defaults = array(
			'parent_in' 	=> array(),	
			'parent_not_in' => 0,	
			'in' 			=> array(),			
			'not_in' 		=> 0,			
			'type'			=> array(),		
			'type_not'		=> array(),
			'order'      	=> 'c.content_id DESC',
			'status'		=> self::STATUS_LIVE,
		);

		foreach ($defaults as $k => $v) {
			$options[$k] = array_key_exists($k, $options) ? $options[$k] : $v;
		}

		$select = $db->select();
		$select->from(array('c' => 'snap_contents'), array());
		
		// in
		if (count($options['in'])){
			$select->where('c.content_id in (?)', $options['in']);
		}
		// not in
		if($options['not_in'] > 0){
			$select->where('c.content_id != ?', $options['not_in']);
		}
		// parent
		if (count($options['parent_in'])){
			$select->where('c.content_parent in (?)', $options['parent_in']);
		}
		// parent not in
		if ($options['parent_not_in'] > 0){
			$select->where('c.content_parent != ?', $options['parent_not_in']);
		}
		// content type
		if(count($options['type'])){
			$select->where('c.content_type in (?)', $options['type']);
		}
		// content type not
		if(count($options['type_not'])){
			$select->where('c.content_type not in (?)', $options['type_not']);
		}
		
		// status	
		if(!is_null($options['status'])){
			$select->where('c.content_status = ?', $options['status']);
		}
		// order
		$select->order($options['order']);
		
		return $select;
	}
	
	public static function array_search_recursive($array, $key, $value)
	{
		$results = array();
		if (is_array($array))
		{
			if (isset($array['parent'][$key]) && $array['parent'][$key] == $value)
		    	$results = array_values($array['children']);
		
		 	foreach ($array as $subarray)
		       	$results = array_merge($results, self::array_search_recursive($subarray, $key, $value));
		}
		
		return $results;
	}
}