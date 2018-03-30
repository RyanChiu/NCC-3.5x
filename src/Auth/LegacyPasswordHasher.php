<?php 
namespace App\Auth;

use Cake\Auth\AbstractPasswordHasher;

class LegacyPasswordHasher extends AbstractPasswordHasher {
	protected $key = "";
	public function hash($password) {
		return md5($password . $this->key);
	}
	public function check($password, $hashedPassword) {
		return md5($password . $this->key) === $hashedPassword;
	}
}
?>