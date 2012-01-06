<?php
namespace Flyf\Models\Abstracts;

class DataAccessObject {
	protected $QueryBuilder;
	
	protected $Table;
	protected $Fields;

	protected $TableTranslation;
	protected $FieldsTranslation;
	
	
	public function __construct() {
		$this->QueryBuilder = new \Flyf\Database\QueryBuilder();
	}

	public function SetTable($table) {
		$this->Table = $table;
	}
	
	public function SetFields($fields) {
		$this->Fields = $fields;
	}

	public function SetTableTranslation($tableTranslation) {
		$this->TableTranslation = $tableTranslation;
	}
	
	public function SetFieldsTranslation($fieldsTranslation) {
		$this->FieldsTranslation = $fieldsTranslation;
	}

	public function Load($data, $language = null) {
		if (is_array($data)) {
			$this->QueryBuilder->SetType('select');
			$this->QueryBuilder->SetTable($this->Table);
			$this->QueryBuilder->SetFields($this->Fields);
			
			$this->QueryBuilder->SetLimit(1);

			foreach ($data as $key => $value) {
				$this->QueryBuilder->AddCondition('`'.$key.'` = :'.$key);
				$this->QueryBuilder->BindParam($key, $value);
			}

			if (count($result = $this->QueryBuilder->Execute())) {
				$result = $result[0];
				
				if ($language != null) {
					$fuck = array(
						'model_id' => $result['id']	,
						'language' => $language
					);
					$translation = $this->LoadLanguage($fuck);

					$result = array_merge($result, $translation);
				}

				return $result;
			} else {
				return array();	
			}
		} else {
			return $this->Load(array('id' => $data), $language);
		}
	}

	private function LoadLanguage($data) {
		$this->QueryBuilder->SetType('select');
		$this->QueryBuilder->SetTable($this->TableTranslation);
		$this->QueryBuilder->setFields($this->FieldsTranslation);
			
		$this->QueryBuilder->SetLimit(1);

		foreach ($data as $key => $value) {
			$this->QueryBuilder->AddCondition('`'.$key.'` = :'.$key);
			$this->QueryBuilder->BindParam($key, $value);
		}
		
		if (count($result = $this->QueryBuilder->Execute())) {
			return $result[0];
		} else {
			return array();	
		}
	}

	public function Save($data) {
		if (isset($data['id']) && $data['id']) {
			$this->QueryBuilder->SetType('update');
			$this->QueryBuilder->AddCondition('id = :id');
			$this->QueryBuilder->BindParam('id', $data['id']);
		
			if (array_key_exists('date_modified', $data)) {
				$data['date_modified'] = date('Y-m-d H:i:s');
			}
		} else {
			$this->QueryBuilder->SetType('insert');

			if (array_key_exists('date_created', $data)) {
				$data['date_created'] = date('Y-m-d H:i:s');
			}
		}
		
		$this->QueryBuilder->SetTable($this->Table);

		$this->QueryBuilder->SetFields(array_keys($data));
		$this->QueryBuilder->SetValues(array_values($data));
		
		$this->QueryBuilder->SetLimit(1);

		if (($id = $this->QueryBuilder->Execute()) !== null) {
			$data['id'] = isset($data['id']) && $data['id'] ? $data['id'] : $id;
		}

		return $data;
	}

	public function Delete($id) {
		$this->QueryBuilder->SetType('delete');
		$this->QueryBuilder->SetTable($this->Table);
		
		$this->QueryBuilder->AddCondition('id = :id');
		$this->QueryBuilder->BindParam('id', $id);
		$this->QueryBuilder->SetLimit(1);

		$this->QueryBuilder->Execute();
	}

	public function Trash($id) {
		$data = array(
			'date_trashed' => date('Y-m-d H:i:s')
		);
		
		$this->QueryBuilder->SetType('update');
		$this->QueryBuilder->SetTable($this->Table);
		$this->QueryBuilder->SetFields(array_keys($data));
		$this->QueryBuilder->SetValues(array_values($data));
		$this->QueryBuilder->AddCondition('id = :id');
		$this->QueryBuilder->BindParam('id', $id);
		$this->QueryBuilder->SetLimit(1);
		
		$this->QueryBuilder->Execute();

		return $data;
	}

	public function Untrash($id) {
		$data = array(
			'date_trashed' => 0
		);
		
		$this->QueryBuilder->SetType('update');
		$this->QueryBuilder->SetTable($this->Table);
		$this->QueryBuilder->SetFields(array_keys($data));
		$this->QueryBuilder->SetValues(array_values($data));
		$this->QueryBuilder->AddCondition('id = :id');
		$this->QueryBuilder->BindParam('id', $id);	
		$this->QueryBuilder->SetLimit(1);

		$this->QueryBuilder->Execute();

		return $data;
	}
}
?>
