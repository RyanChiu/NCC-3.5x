<?php
namespace App\Controller;

use App\Controller\AppController;

class AccountsController extends AppController {
	
	public function initialize()
	{
		parent::initialize();
		$this->loadModel("Bulletins");
		$this->loadModel("Top10s");
	}
    
    public function login() {
    	/*
    	 * some test moves
    	 */
    	$someBulletins = $this->Bulletins->find('all', [
    		'limit' => 5
    	]);
    	$this->set("someBulletins", $someBulletins);
    	
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
	    			return $this->redirect(["controller" => "accounts", "action" => "index"]);
	    		}
	    		$this->Flash->error('Your username or password is incorrect.');
    		}
    	}
    }
    
    public function index($id = null) {
		/*try to archive the bulletin*/
		if ($id == -1 && $this->Auth->user('Account.role') == 0) {
			$this->Bulletin->updateAll(
				array('archdate' => "'" . date('Y-m-d h:i:s') . "'"),
				array('archdate' => null)
			);
			if ($this->Bulletin->getAffectedRows() > 0) {
				$this->Session->setFlash("Bulletin archived.");
			} else {
				$this->Session->setFlash("No current bulletin exists.");
			}
		}
		/*prepare the historical bulletins*/
		/*
		$archdata = $this->Bulletins->find('all',
			array(
				'fields' => array('id', 'title', 'archdate'),
				'conditions' => array('archdate not' => null),
				'order' => array('archdate desc')
			)
		);*/
		$archdata = $this->Bulletins->find()
			->select(['id', 'title', 'archdate'])
			->where(['archdate is not' => 'null'])
			->order(['archdate' => 'desc'])
			->all();
		$this->set(compact('archdata'));
		/*prepare the ALERTS for the current logged-in user*/
		$info = array();
		if ($id == null) {
            /*
			$info = $this->Bulletins->find('first',
				array(
					'fields' => array('info'),
					'conditions' => array('archdate' => null)
				)
			);*/
            $info = $this->Bulletins->find()
                ->select(['info'])
                ->where(['archdate' => 'null'])
                ->first();
		} else {
            /*
			$info = $this->Bulletin->find('first',
				array(
					'fields' => array('info'),
					'conditions' => array('id' => $id)
				)
			);*/
            $info = $this->Bulletins->find()
                ->select(['info'])
                ->where(['id' => $id])
                ->first();
		}
		$this->set('topnotes',  empty($info) ? '...' : $info['Bulletin']['info']);
		if ($this->Auth->user('Account.role') == 0) {//means an administrator
			$this->set('notes', '');//set the additional notes here
		} else if ($this->Auth->user('Account.role') == 1) {//means a company
			$cominfo = $this->Company->find('first',
				array(
					'fields' => array('agentnotes'),
					'conditions' => array('id' => $this->Auth->user('Account.id'))
				)
			);
			$this->set('notes', '');//set the additional notes here
		} else {//means an agent
			$aginfo = $this->Agent->find('first',
				array(
					'fields' => array('companyid'),
					'conditions' => array('id' => $this->Auth->user('Account.id'))
				)
			);
			$cominfo = $this->Company->find('first',
				array(
					'fileds' => array('agentnotes'),
					'conditions' => array('id' => $aginfo['Agent']['companyid'])
				)
			);
			$this->set('notes', '<font size="3"><b>Office news&nbsp;&nbsp;</b></font>' . $cominfo['Company']['agentnotes']);
		}
		
		/*
		 * for the "selling contestants" stuff
		 */
		//avoid those data which are not in types
		$conds['startdate'] = '0000-00-00';
		$conds['enddate'] = date('Y-m-d');
		/*
        $rs = $this->Top10->find('all',
			array(
				'conditions' => array('flag' => 0),
				'order' => 'sales desc'
			)
		);*/
        $rs = $this->Top10s->find()
            ->where(['flag' => 0])
            ->order(['sales' => 'desc'])
            ->all();
		$this->set(compact('rs'));
		$weekend = date("Y-m-d", strtotime(date('Y-m-d') . " Saturday"));
		$weekstart = date("Y-m-d", strtotime($weekend . " - 6 days"));
		$curbiweek = __getCurBiweek();
		$curbiweekse = explode(",", $curbiweek);
		$biweekstart = $curbiweekse[0];
		$biweekend = $curbiweekse[1];
		$conds['startdate'] = $weekstart;
		$conds['enddate'] = $weekend;
		$weekrs = $this->Top10s->find('all',
			array(
				'conditions' => array('flag' => 1),
				'order' => 'sales desc'
			)
		);
		$conds['startdate'] = $biweekstart;
		$conds['enddate'] = $biweekend;
		$biweekrs = $this->Top10->find('all',
			array(
				'conditions' => array('flag' => 3),
				'order' => 'sales desc'
			)
		);
		$biweekrs0 = $this->Top10->find('all',
			array(
				'conditions' => array('flag' => 4),
				'order' => 'sales desc'
			)
		);
		$this->set(compact('weekrs'));
		$this->set(compact('weekstart'));
		$this->set(compact('weekend'));
		$this->set(compact('biweekrs'));
		$this->set(compact('biweekrs0'));
		$this->set(compact('biweekstart'));
		$this->set(compact('biweekend'));
		
		/*
		 * trials and bonus top 10 for new style (2 steps) sales
		 */
		$trbors = $this->TrboTop10->find('all',
			array(
				'conditions' => array('flag' => 0),
				'order' => 'sales desc'
			)
		);
		$trboweekrs = $this->TrboTop10->find('all',
			array(
				'conditions' => array('flag' => 1),
				'order' => 'sales desc'
			)
		);
		$this->set(compact('trbors'));
		$this->set(compact('trboweekrs'));
    }
    
    public function logout(){
    	return $this->redirect($this->Auth->logout());
    }
}
?>
