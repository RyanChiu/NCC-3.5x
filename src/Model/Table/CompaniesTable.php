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
	];
	
	public function initialize(array $config) {
		
	}
	
	public function validationDefault(Validator $validator) {
		$validator
			->notEmpty('man1stname')
			->notEmpty('manlastname')
			->notEmpty('mancellphone')
			->notEmpty('country')
			->add('officename', 'officenameRule_1', [
				'rule' => 'notEmpty',
				'message' => 'Please do not let this field empty.'
			])
			->add('officename', 'officenameRule_2', [
				'rule' => 'isUnique',
				'message' => 'Sorry, this "Office Name" has already been taken.'
			])
			->add('manemail', 'email', [
				'rule' => 'email',
				'message' => 'Please fill out a valid email address.'
			]);
		
		return $validator;
	}
}