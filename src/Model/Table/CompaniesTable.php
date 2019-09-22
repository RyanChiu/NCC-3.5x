<?php
// src/Model/Table/CompaniesTable.php
namespace App\Model\Table;
use Cake\ORM\Table;
use Cake\ORM\Rule\IsUnique;
use Cake\ORM\RulesChecker;
use Cake\Validation\Validator;
class CompaniesTable extends Table {
	public $payouttype = [
		'0' => 'Pay by Check',
		'1' => 'Pay by Webstern Union',
		'2' => 'Pay by Wire'
	];public $tmp = "123";
	
	public function initialize(array $config) {
		
	}
	
	public function buildRules(RulesChecker $rules) {
		$rules->add(
			$rules->isUnique(['officename']), 'uniqueOfficename',
			['message' => 'Sorry, this "Office Name" has already been taken.']
		);
		return $rules;
	}

	public function validationDefault(Validator $validator) {$this->tmp = "789";
		$validator->notBlank('man1stname');
		$validator->notBlank('manlastname');
		$validator->notBlank('mancellphone');
		$validator->notBlank('officename');
		$validator->notBlank('country');
		$validator->email('manemail');
		
		return $validator;
	}
}