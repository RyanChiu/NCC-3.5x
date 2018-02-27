<?php
namespace App\Controller;

use App\Controller\AppController;

class AccountsController extends AppController {
    
    public function login() {
    	/*
    	 * some test moves
    	 */
    	$someAccounts = $this->Accounts->find('all', [
    		'limit' => 5,
    		'order' => 'Accounts.role desc'
    	]);
    	$this->set("someAccounts", $someAccounts);
    	
    	// load the Captcha component and set its parameter
    	$this->loadComponent('CakeCaptcha.Captcha', [
    		'captchaConfig' => 'LoginCaptcha'
    	]);
    	
    	if ($this->request->is('post')) {
    		// validate the user-entered Captcha code
    		$isHuman = captcha_validate($this->request->data['CaptchaCode']);
    		
    		// clear previous user input, since each Captcha code can only be validated once
    		unset($this->request->data['CaptchaCode']);
    		
    		if ($isHuman) {
	    		$account = $this->Auth->identify();   		
	    		if ($account) {
	    			$this->Auth->setUser($account);
	    			return $this->redirect(["controller" => "Accounts", "action" => "index"]);
	    		}
	    		$this->Flash->error('Your username or password is incorrect.');
    		}
    	}
    }
    
    public function index() {
    }
    
    public function logout(){
    	return $this->redirect($this->Auth->logout());
    }
}
?>
