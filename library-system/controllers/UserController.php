<?php

class UserController extends Controller
{
	public function signupAction()
	{
		return $this->render(array(
			'name' => '',
			'email' => '',
			'password' => '',
			'_token' => $this->generateCsrfToken('user/signup'),
		));
	}

	public function preregistAction()
	{
		if(!$this->request->isPost()){
			return $this->redirect('/user/signup');
		}

		$token = $this->request->getPost('_token');
		if(!$this->checkCsrfToken('user/signup', $token)){
			return $this->redirect('/user/signup');
		}

		$name = $this->request->getPost('name');
		$email = $this->request->getPost('email');
		$password = $this->request->getPost('password');

		list($errors, $result, $token) = $this->db_manager->get('UnverifiedUser')->insert($name, $email, $password);
		
		if($result !== false){
			$url = 'http://' . $this->request->getHost() . '/user/regist?email=' . $email . '&token=' . $token;
			$this->mailer->setup(
				$email,
				'【CALISIS】仮登録完了メール',
				'mailsended.php',
				array(
					'__name' => $name,
					'__url' => $url
				)
			);
			$this->mailer->send();
			return $this->render(array('email' => $email), 'mailsended');
		}else{
			return $this->render(array(
				'email' => $email,
				'name' => $name,
				'password' => $password,
				'errors' => $errors,
				'_token' => $this->generateCsrfToken('user/signup')
			), 'signup');
		}
	}

	public function registAction()
	{
		$email = $this->request->getGet('email');
		$token = $this->request->getGet('token');

		$unverifiedUser = $this->db_manager->get('UnverifiedUser')->fetchByEmail($email);

		$now = strtotime('now');
		$limitTime = strtotime($unverifiedUser['created_at']) + 60 * 60;

		$error = '';
		if($unverifiedUser === false || $token !== $unverifiedUser['token']){
			$error = '不正なアクセスです。';
		}else if($now > $limitTime){
			$error = '有効期限切れです。';
		}else if($this->db_manager->get('User')->fetchByEmail($email) !== false){
			$error = '既に登録が完了しています。';
		}

		if(strlen($error) === 0){
			$this->db_manager->get('User')->insert(
				$unverifiedUser['name'],
				$unverifiedUser['email'],
				$unverifiedUser['hashed_password'],
				$unverifiedUser['salt']
			);

			return $this->render(array(), 'registcomplete');
		}else{
			return $this->render(array('error' => $error), 'registerror');
		}

	}
}
