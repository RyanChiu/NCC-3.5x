<?php
// src/Model/Table/AgentsTable.php
namespace App\Model\Table;
use Cake\ORM\Table;
class AgentsTable extends Table {
	public function initialize(array $config) {
		
	}
	
	public function validationDefault(Validator $validator) {
		$validator
			->notEmpty('ag1stname')
			->notEmpty('aglastname')
			->notEmpty('country')
			->notEmpty('im')
			->notEmpty('cellphone');
	
		return $validator;
	}
	
	public function validationUpdate($validator) {
		$validator
			->add('companyid', 'validComID', [
				'rule' => function($data, $provider) {
					if ($data > 0) return true;
					return "Please choose an office.";
				}
			])
			->add('email', 'email', [
					'rule' => 'email',
					'message' => 'Please fill out a valid email address.'
			]);
	
		return $validator;
	}
}