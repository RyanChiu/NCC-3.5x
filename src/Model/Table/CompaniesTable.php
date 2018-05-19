<?php
// src/Model/Table/CompaniesTable.php
namespace App\Model\Table;
use Cake\ORM\Table;
use Cake\Validation\Validator;
class CompaniesTable extends Table {
	public $payouttype = [
		'0' => 'Pay by Check',
		'1' => 'Pay by Webstern Union',
		'2' => 'Pay by Wire'
	];public $tmp = "123";
	
	public function initialize(array $config) {
		
	}
	
	public function validationDefault(Validator $validator) {$this->tmp = "789";
		$validator->notEmpty('man1stname');
		$validator->notEmpty('manlastname');
		$validator->notEmpty('mancellphone');
		$validator->notEmpty('country');
		$validator
			->add('officename', 'officenameRule_1', [
				'rule' => 'notEmpty',
				'message' => 'Please do not let this field empty.'
			]);
		$validator
			->add('officename', 'officenameRule_2', [
				'rule' => 'isUnique',
				'message' => 'Sorry, this "Office Name" has already been taken.'
			]);
		$validator
			->add('manemail', 'manemail_rule', [
				'rule' => 'email',
				'message' => 'Please fill out a valid email address.'
			]);
		
		return $validator;
	}
}