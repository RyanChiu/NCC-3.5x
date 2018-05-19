<?php
// src/Model/Table/AccountsTable.php
namespace App\Model\Table;
use Cake\ORM\Table;
use Cake\Validation\Validator;
class AccountsTable extends Table {
	public $status = array('-1' => 'Unapproved', '0' => 'Suspended', '1' => 'Activated', '-2' => 'Hidden');
	public $online = array('0' => 'offline', '1' => 'online');
	public $key = ""; public $tmp = "abc";
	
	public function initialize(array $config) {
		
	}
	
	public function validationDefault(Validator $validator) {
		$validator->notEmpty('password');
		$validator->notEmpty('username');$this->tmp = "def";
		$validator
			->add('username', 'usernameRule_2', [
				'provider' => 'table',
				'rule' => function ($check, $context) {$this->tmp = $context;
					$r = $this->find()
						->where(['lower(username)' => strtolower($check)])
						->first();
					if (isset($context['data']['id'])) {//if it's an updating operation
						if (empty($r)) return true;
						else {
							return ($r->id == $context['data']['id'] ? true : false);
						}
					} else {//otherwise, it's an inserting operation
						return empty($r);
					}
				},
				'message' => 'Sorry, the username has already been taken.(case-insensitive)'
			])
			->add('username', 'usernameRule_3', [
				'provider' => 'table',
				'rule' => function ($check, $context) {$this->tmp = $context;
					if (isset($context['data']) && $context['data']['role'] == 2) {//only if it's an agent
						/*
						 * this rule means:
						 * the first 0~4 chars should be A-Z or a-z or 0-9,
						 * and following by a _ or nothing, and there two means a prefix which
						 * is used to do the "delete an account" stuff.
						 * and the following 1~3 chars should be A-Z or a-z,
						 * and the following 0~4 chars should be 0-9,
						 * and the following 1 char should be _ or nothing,
						 * and the following 0~2 chars should be 0-9.
						 * /i means that both uppercase and lowercase should be fine.
						 */
						return preg_match('/^[A-Z0-9]{0,4}_{0,1}[A-Z]{1,3}\d{0,4}_{0,1}\d{0,2}$/i', $check);
					}
					return true;
				},
				'message' => 'Managers, please sign up your agents with the alpha (uppercase) numeric user names/rids, or the sales will not track properly.(If the last user name rid was LL22, the next agent will be LL23, and so on.)'
			])
			->add('username', 'usernameRule_4', [
				'provider' => 'table',
				'rule' => function ($check, $context) {$this->tmp = $context;
					if (isset($context['data']) && $context['data']['role'] == 2) {//only if it's an agent
						if (strtolower($check) == strtolower($context['data']['username'])) return true;
						$rs = $this->query(
							sprintf(
								'select id from agent_site_mappings where LOWER(campaignid) = "%s"',
								strtolower($check)
							)
						);
						return (empty($rs));
					}
					return true;
				},
				'message' => 'This username is saved for campaign id, please use another one.'
			]);
		
		return $validator;
	}
}