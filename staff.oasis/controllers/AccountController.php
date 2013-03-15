<?php

class AccountController extends Controller
{

	protected $auth_action = array('signout');

	public function signinAction()
	{
		if($this->session->isAuthenticated()){
			return $this->redirect('/book/search');
		}

		$variables = array();
		$variables['_token'] = $this->generateCsrfToken('account/signin');

		return $this->render($variables, 'signin');
	}

	public function authenticateAction()
	{
		if($this->session->isAuthenticated()){
			return $this->redirect('/book/search');
		}
		
		if(!$this->request->isPost()){
			return $this->redirect('/account/signin');
		}

		$token = $this->request->getPost('_token');
		if(!$this->checkCsrfToken('account/signin', $token)){
			return $this->redirect('/account/signin');
		}

		$variables = array();
		$variables['error'] = '';
		$variables['_token'] = $this->generateCsrfToken('account/signin');

		$password = $this->request->getPost('password');
		if(strlen($password) === 0){
			$variables['error'] = 'パスワードを入力してください。';
			return $this->render($variables, 'signin');
		}


		require dirname(__FILE__) . '/../../oasis/config/password.php';

		if($password === $loginPassword){
			$this->session->setAuthenticated(true);
			
			return $this->redirect('/');
		}else{
			$variables['error'] = 'パスワードが一致しません。';
			return $this->render($variables, 'signin');
		}
	}

	public function signoutAction(){
		$this->session->clear();
		$this->session->setAuthenticated(false);

		return $this->redirect('/account/signin');
	}
}
