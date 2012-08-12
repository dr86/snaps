<?php
/**
 * DatabaseObject
 *
 * Abstract class used to easily manipulate data in a database table
 * via simple load/save/delete methods
 */
abstract class My_Db_Object_Abstract
{
	const TYPE_TIMESTAMP = 1;
	const TYPE_BOOLEAN   = 2;

	protected static $types = array(self::TYPE_TIMESTAMP, self::TYPE_BOOLEAN);

	private $_id = null;
	private $_properties = array();


	protected $_db = null;
	protected $_table = '';
	protected $_idField = '';

	public function __construct($db, $table, $idField)
	{
		$this->_db = $db;
		$this->_table = $table;
		$this->_idField = $idField;
	}

	public function load($id, $field = null)
	{
		if (strlen($field) == 0)
			$field = $this->_idField;

		if ($field == $this->_idField) {
			$id = (int) $id;
			if ($id <= 0)
				return false;
		}

		$query = sprintf('select %s from %s where %s = ?',
						join(', ', $this->getSelectFields()),
						$this->_table,
						$field);

		$query = $this->_db->quoteInto($query, $id);

		return $this->_load($query);
	}

	protected function getSelectFields($prefix = '')
	{
		$fields = array($prefix . $this->_idField);
		foreach ($this->_properties as $k => $v)
			$fields[] = $prefix . $k;

		return $fields;
	}

	protected function _load($query)
	{
		$result = $this->_db->query($query);
		$row = $result->fetch();
		if (!$row)
			return false;

		$this->_init($row);

		$this->postLoad();

		return true;
	}

	public function _init($row)
	{
		foreach ($this->_properties as $k => $v)
		{
			$val = $row[$k];

			switch ($v['type'])
			{
				case self::TYPE_TIMESTAMP:
					if (!is_null($val))
					$val = strtotime($val);
					break;
				case self::TYPE_BOOLEAN:
					$val = (bool) $val;
					break;
			}

			$this->_properties[$k]['value'] = $val;
		}
		$this->_id = (int) $row[$this->_idField];
	}
	
	public function toArray()
	{
		if(!$this->isSaved())
			return array();
			
		foreach ($this->_properties as $k => $v){
			$row[$this->_idField] = $this->_id;
			$row[$k] = $this->_properties[$k]['value'];
		}
		
		return $row;
	}

	/**
	 * Save
	 * 
	 * Proses update Jika true nilai id (isSaved()):
	 *  nilai id didapat dari proses _load
	 *  preUpdate() -> postUpdate()
	 * Proses insert Jika false nilai id (isSaved()):
	 *  preInsert() -> postInsert()
	 */
	public function save($useTransactions = true)
	{
		$update = $this->isSaved();

		if ($useTransactions)
			$this->_db->beginTransaction();
			
		if ($update)
			$commit = $this->preUpdate();
		else
			$commit = $this->preInsert();

		if (!$commit) {
			if ($useTransactions)
				$this->_db->rollback();
			return false;
		}

		$row = array();

		foreach ($this->_properties as $k => $v) {
			if ($update && !$v['updated'])
			continue;

			switch ($v['type']) {
				case self::TYPE_TIMESTAMP:
					if (!is_null($v['value'])) {
						if ($this->_db instanceof Zend_Db_Adapter_Pdo_Pgsql)
							$v['value'] = date('Y-m-d H:i:sO', $v['value']);
						else
							$v['value'] = date('Y-m-d H:i:s', $v['value']);
					}
					break;

				case self::TYPE_BOOLEAN:
					$v['value'] = (int) ((bool) $v['value']);
					break;
			}

			$row[$k] = $v['value'];
		}

		if (count($row) > 0) {
			// perform insert/update
			if ($update) {
				$this->_db->update( $this->_table, $row, sprintf( '%s = %d', $this->_idField, $this->getId() ) );
			}
			else {
				$this->_db->insert($this->_table, $row);
				$this->_id = $this->_db->lastInsertId($this->_table, $this->_idField);
			}
		}

		// update internal id

		if ($commit) {
			if ($update)
				$commit = $this->postUpdate();
			else
				$commit = $this->postInsert();
		}

		if ($useTransactions) {
			if ($commit)
				$this->_db->commit();
			else
				$this->_db->rollback();
		}

		return $commit;
	}

	public function delete($useTransactions = true)
	{
		if (!$this->isSaved())
			return false;

		if ($useTransactions)
			$this->_db->beginTransaction();

		$commit = $this->preDelete();

		if ($commit) {
			$this->_db->delete($this->_table, sprintf('%s = %d', $this->_idField, $this->getId()));
		}
		else {
			if ($useTransactions)
			$this->_db->rollback();
			return false;
		}

		$commit = $this->postDelete();

		$this->_id = null;

		if ($useTransactions) {
			if ($commit)
				$this->_db->commit();
			else
				$this->_db->rollback();
		}

		return $commit;
	}
	/**
	 * 
	 * @return true jika id != 0
	 */
	public function isSaved()
	{
		return $this->getId() > 0;
	}
	public function getId()
	{
		return (int) $this->_id;
	}

	public function getDb()
	{
		return $this->_db;
	}
	public function clear()
	{
		return $this->_id = null;
	}
	
	/**
	 * 
	 * SETTER PHP5
	 */
	public function __set($name, $value)
	{
		if (array_key_exists($name, $this->_properties)) {
			$this->_properties[$name]['value'] = $value;
			$this->_properties[$name]['updated'] = true;
			return true;
		}

		return false;
	}
	/**
	 * 
	 * GETTER PHP5
	 */
	public function __get($name)
	{
		return array_key_exists($name, $this->_properties) ? $this->_properties[$name]['value'] : null;
	}

	protected function add($field, $default = null, $type = null)
	{
		$this->_properties[$field] = array('value'   => $default,
                                           'type'    => in_array($type, self::$types) ? $type : null,
                                           'updated' => false);
	}

	protected function preInsert()
	{
		return true;
	}

	protected function postInsert()
	{
		return true;
	}

	protected function preUpdate()
	{
		return true;
	}

	protected function postUpdate()
	{
		return true;
	}

	protected function preDelete()
	{
		return true;
	}

	protected function postDelete()
	{
		return true;
	}

	protected function postLoad()
	{
		return true;
	}
	
	protected function _generateSlug( $title )
	{
		// Lowercase the string
        $value = strtolower($title);
 
        // Generate slug by removing unwanted (other than alphanumeric and dash [-]) characters from the string
        $value = preg_replace('/[^a-z0-9-]/i', '-', $value);
        $value = preg_replace('/-[-]*/', '-', $value);
        $value = preg_replace('/-$/', '', $value);
        $value = preg_replace('/^-/', '', $value);

        // find similar slugs
        $query = sprintf("select content_slug from %s where content_slug like ?", $this->_table);
        $query = $this->_db->quoteInto($query, $value . '%');
        $result = $this->_db->fetchCol($query);

        // if no matching URLs then return the current URL
        if (count($result) == 0 || !in_array($value, $result))
        	return $value;

        // generate a unique URL
        $i = 2;
        do {
        	$_slug = $value . '-' . $i++;
        } while (
			in_array($_slug, $result)
		);

        return $_slug;
	}
	
	private function _createSlug($title)
    {
       $filter = new Zend_Filter_Word_SeparatorToDash();
	   return $filter->filter(strtolower($title));
    }

	public static function BuildMultiple($db, $class, $data)
	{
		$ret = array();

		if (!class_exists($class))
			throw new Exception('Undefined class specified: ' . $class);

		$testObj = new $class($db);

		if (!$testObj instanceof My_Db_Object_Abstract)
			throw new Exception('Class does not extend from DatabaseObject');

		foreach ($data as $row) {
			$obj = new $class($db);
			$obj->_init($row);

			$ret[$obj->getId()] = $obj;
		}

		return $ret;
	}
}
?>