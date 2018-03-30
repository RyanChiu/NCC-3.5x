<?php
// src/Model/Table/SitesTable.php
namespace App\Model\Table;
use Cake\ORM\Table;
use Cake\Validation\Validator;
class SitesTable extends Table {
	public function initialize(array $config) {
		
	}
	
	public function validationDefault(Validator $validator) {
		$validator
			->notEmpty('sitename')
			->notEmpty('contactname')
			->notEmpty('phonenums')
			->notEmpty('type');
		
		return $validator;
	}
	
	public function validationUpdate($validator) {
		$validator
			->add('hostname', 'hostnameRule_1', [
				'rule' => 'notEmpty',
				'message' => 'Please do not let this field empty.'
			])
			->add('hostname', 'hostnameRule_2', [
				'rule' => 'isUnique',
				'message' => 'Sorry, this host name has already been taken.'
			])
			->add('abbr', 'abbrRule_1', [
				'rule' => ['lengthBetween', 3, 5],
				'message' => 'Abbreviation must between 3 and 5 characters long.'
			])
			->add('abbr', 'abbreRule_2', [
				'rule' => 'isUnique',
				'message' => 'Sorry, this abbreviation has already been taken.'
			])
			->add('email', 'email', [
				'rule' => 'email',
				'message' => 'Please fill out a valid email address.'
			])
			->add('url', 'url', [
				'rule' => 'url',
				'message' => 'Please fill out a valid URL.'
			])
			->add('payouts', 'money', [
				'rule' => 'money',
				'message' => 'Please fill out 2 digital numbers after the decimal point.'
			]);
		
		return $validator;
	}
}