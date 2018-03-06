<?php
// src/Model/Table/AdminsTable.php
namespace App\Model\Table;
use Cake\ORM\Table;
class AdminsTable extends Table {
	public function initialize(array $config) {
		
	}
	
	public function validationUpdate($validator) {
		$validator
			->add('email', 'email', [
				'rule' => 'email',
				'message' => 'Please fill out a valid email address.'
			]);
	
		return $validator;
	}
}