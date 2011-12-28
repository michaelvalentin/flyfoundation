<?php
class Flyf_Abstract_Model_DataAccessObject {
	protected $queryBuilder;
	
	protected $table;
	protected $fields;
	
	public function __construct($meta = false) {
		$this->queryBuilder = Flyf::registry('flyf_database_querybuilder');

		$this->table = strtolower(str_replace('_DataAccessObject', '', get_class($this)));
		$this->fields = array_keys(get_class_vars(str_replace('_DataAccessObject', '_ValueObject', get_class($this))));

		if ($meta) {
			$metaValueObjectCustom = str_replace('_DataAccessObject', '_Meta_ValueObject', get_class($this));
			$metaValueObjectBase = 'Flyf_Model_Meta_ValueObject';

			$meta_fields = array_keys(get_class_vars(class_exists($metaValueObjectCustom) ? $metaValueObjectCustom : $metaValueObjectBase));

			$this->fields = array_unique(array_merge($this->fields, $meta_fields));
		}
	}

	public function load($data) {
		if (is_array($data)) {
			$this->queryBuilder->setType('select');
			$this->queryBuilder->setTable($this->table);
			$this->queryBuilder->setFields($this->fields);
			
			$this->queryBuilder->setLimit(1);
			
			foreach ($data as $key => $value) {
				$this->queryBuilder->addCondition("`".$key."` = '".$value."'");
			}

			if (count($result = $this->queryBuilder->execute())) {
				return $result[0];
			} else {
				return array();	
			}
		} else {
			return $this->load(array('id' => $data));
		}
	}

	public function save($data) {
		if (isset($data['id']) && $data['id']) {
			$this->queryBuilder->setType('update');
			$this->queryBuilder->addCondition('id = '.$data['id']);

			if (array_key_exists('date_modified', $data)) {
				$data['date_modified'] = date('Y-m-d H:i:s');
			}
		} else {
			$this->queryBuilder->setType('insert');

			if (array_key_exists('date_created', $data)) {
				$data['date_created'] = date('Y-m-d H:i:s');
			}
		}
		
		$this->queryBuilder->setTable($this->table);

		$this->queryBuilder->setFields(array_keys($data));
		$this->queryBuilder->setValues(array_values($data));
		
		$this->queryBuilder->setLimit(1);

		if (($id = $this->queryBuilder->execute()) !== null) {
			$data['id'] = isset($data['id']) && $data['id'] ? $data['id'] : $id;
		}

		return $data;
	}

	public function delete($id) {
		$this->queryBuilder->setType('delete');
		$this->queryBuilder->setTable($this->table);
		
		$this->queryBuilder->addCondition('id = '.$id);
		$this->queryBuilder->setLimit(1);

		$this->queryBuilder->execute();
	}

	public function trash($id) {
		$data = array(
			'date_trashed' => date('Y-m-d H:i:s')
		);
		
		$this->queryBuilder->setType('update');
		$this->queryBuilder->setTable($this->table);
		$this->queryBuilder->setFields(array_keys($data));
		$this->queryBuilder->setValues(array_values($data));
		$this->queryBuilder->addCondition('id = '.$id);		
		$this->queryBuilder->setLimit(1);

		$this->queryBuilder->execute();

		return $data;
	}

	public function untrash($id) {
		$data = array(
			'date_trashed' => 0
		);
		
		$this->queryBuilder->setType('update');
		$this->queryBuilder->setTable($this->table);
		$this->queryBuilder->setFields(array_keys($data));
		$this->queryBuilder->setValues(array_values($data));
		$this->queryBuilder->addCondition('id = '.$id);		
		$this->queryBuilder->setLimit(1);

		$this->queryBuilder->execute();

		return $data;
	}
}
?>
