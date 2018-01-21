<?php
class loginController extends AbstractController
{
	public function run()
	{
		global $XenuxDB, $app;

		// append translations
		translator::appendTranslations(MAIN_PATH . '/modules/' . $this->modulename . '/translation/');

		$task = isset($_GET['task']) ? $_GET['task'] : '';

		$action = ($task == 'logout' || $task == 'login' || empty($task) ||
			!in_array($task, ['register', 'forgotpassword', 'resetpassword', 'setpassword', 'confirm'])
			) ? 'login' : $task;


		$template = new template(MAIN_PATH . '/modules/' . $this->modulename . '/login.php', ['action'=>$action]);

		$template->setVar('TEMPLATE_URL', MAIN_URL . '/template');
		$template->setVar('homepage_name', $app->getOption('hp_name'));
		$template->setVar('message', '');
		$template->setVar('form', '');

		switch ($action) {
			case 'register':
				$app->addJS(MAIN_URL . '/js/modules/' . $this->modulename . '/register.js');
				$this->registerAction($template);
				$template->setVar('page_name', __('register'));
				break;
			case 'forgotpassword':
				$this->forgotpasswordAction($template);
				$template->setVar('page_name', __('forgotPassword'));
				break;
			case 'resetpassword':
				$this->resetpasswordAction($template);
				$template->setVar('page_name', __('resetpassword'));
				break;
			case 'setpassword':
				$this->setPasswordAction($template);
				$template->setVar('page_name', __('setpassword'));
				break;
			case 'confirm':
				$this->confirmAction($template);
				$template->setVar('page_name', __('confirm'));
				break;
			case 'login':
			default:
				$template->setVar('page_name', __('login'));
				$this->loginAction($template, $task);
				break;
		};


		echo $template->render();
		return true;
	}

	private function loginAction(&$template, $task)
	{
		global $app, $XenuxDB;

		if ($app->user->isLogin() && $task != 'logout')
		{
			header('Location: ' . MAIN_URL, true, 303);
		}

		if ($task == 'logout')
		{
			$app->user->setLogout();
			$template->setIfCondition('logout', true);
		}

		$formFields = array
		(
			'email' => array
			(
				'type' => 'email',
				'required' => true,
				'label' => __('email'),
				#'class'    => 'input'
			),
			'password' => array
			(
				'type' => 'password',
				'required' => true,
				'label' => __('password'),
				'min_length' => 0,
				#'class'    => 'input'
			),
			'submit' => array
			(
				'type' => 'submit',
				'label' => __('login')
			)
		);

		$loginform = new form($formFields);
		$loginform->disableRequiredInfo();

		if ($loginform->isSend() && $loginform->isValid())
		{
			$data = $loginform->getInput();

			$userFound = $app->user->getUserByEmail($data['email']);

			if ($userFound)
			{
				if ($app->user->checkPassword($data['password']))
				{
					$userInfo = $app->user->userInfo;

					if (is_null($userInfo->last_confirmed))
					{
						$template->setVar('message', '<p>' . __('not confirmed') . '</p>');
						return false;
					}

					if (is_null($userInfo->lastlogin_date) && is_null($userInfo->lastlogin_ip) && is_null($userInfo->session_fingerprint))
					{
						// first login
						$token = generateRandomString();

						$XenuxDB->Update('users', [
							'verifykey' => $token,
						],
						[
							'id' => $userInfo->id
						]);

						header('Location: ' . MAIN_URL . '/login?task=firstLogin&id=' . $userInfo->id . '&token=' . $token . (isset($_GET['redirectTo']) ? '&redirectTo=' . $_GET['redirectTo'] : ''));
						return false;
					}

					$app->user->setLogin();

					if (isset($_GET['redirectTo']) && !empty($_GET['redirectTo'])):
						header('Location: ' . MAIN_URL . '/' . $_GET['redirectTo']);
					else:
						header('Location: ' . MAIN_URL);
					endif;
				}
				else
				{
					$loginform->setErrorMsg(__('password wrong'));
					$loginform->setFieldInvalid('password');
				}
			}
			else
			{
				$loginform->setErrorMsg(__('email wrong'));
				$loginform->setFieldInvalid('email');
			}
		}

		$template->setVar('form', $loginform->getForm());
	}

	private function registerAction(&$template)
	{
		global $app, $XenuxDB;

		if (!parse_bool($app->getOption('users_can_register')))
		{
			$template->setVar('message', '<p class="info">' . __('registrationClosed') . '</p>');
			return false;
		}

		$classes = array #TODO: not static
		(
			'5A', '5B', '5C', '5D', '5E', '5F',
			'6A', '6B', '6C', '6D', '6E', '6F',
			'7A', '7B', '7C', '7D', '7E', '7F',
			'8A', '8B', '8C', '8D', '8E', '8F',
			'9A', '9B', '9C', '9D', '9E', '9F',
			'10A', '10B', '10C', '10D', '10E', '10F',
			'11.1', '11.2', '11.3', '11.4', '11.5',
			'12/Q1',
			'13/Q2'
		);

		$classesOptions = array();
		foreach ($classes as $class)
		{
			$classesOptions[] = array
			(
				'value' => $class,
				'label' => $class
			);
		};

		$formFields = array
		(
			'firstname' => array
			(
				'type'     => 'text',
				'required' => true,
				'label'    => __('firstname'),
				'class'    => 'input'
			),
			'lastname' => array
			(
				'type'     => 'text',
				'required' => true,
				'label'    => __('lastname')
			),
			'email' => array
			(
				'type'     => 'email',
				'required' => true,
				'label'    => __('email')
			),
			'type' => array
			(
				'type'     => 'select',
				'required' => true,
				'label'    => __('type'),
				'options'  => array
				(
					array
					(
						'value' => 'student',
						'label' => __('student')
					),
					array
					(
						'value' => 'teacher',
						'label' => __('teacher')
					)
				)
			),
			'class' => array
			(
				'type'     => 'select',
				'required' => false,
				'label'    => __('class'),
				'class'    => 'input-class',
				'options'  => $classesOptions
			),
			'abbreviation' => array
			(
				'type'     => 'text',
				'required' => false,
				'label'    => __('abbreviation'),
				'class'    => 'input-abbreviation'
			),
			'password' => array
			(
				'type'     => 'password',
				'required' => true,
				'label'    => __('password')
			),
			'passwordAgain' => array
			(
				'type'     => 'password',
				'required' => true,
				'label'    => __('passwordAgain')
			),
			'submit' => array #TODO: add captcha
			(
				'type'  => 'submit',
				'label' => __('register')
			)
		);

		$registerform = new form($formFields);
		$registerform->disableRequiredInfo();

		if ($registerform->isSend() && $registerform->isValid())
		{
			$data = $registerform->getInput();

			$userFoundByEmail		= $app->user->getUserByEmail($data['email']);

			$allowed_domains = json_decode($app->getOption('allowed_domains'));
			list(, $email_domain) = explode('@', $data['email'], 2);

			if (!$userFoundByEmail) // check if email used
			{
				if (in_array($email_domain, $allowed_domains)) // check if domain is allowed
				{
					if ($data['password'] == $data['passwordAgain'])
					{
						$token = generateRandomString();

						$return = $XenuxDB->Insert('users', [
							'firstname'    => $data['firstname'],
							'lastname'     => $data['lastname'],
							'email'        => $data['email'],
							'type'         => $data['type'],
							'class'        => $data['class'],
							'abbreviation' => $data['abbreviation'],
							'password'     => $app->user->createPasswordHash($data['password']),
							'verifykey'    => $token
						]);
						if ($return !== false)
						{
							// user added successfull
							$confirmlink = MAIN_URL . '/login/?task=confirm&amp;id=' . $return . '&amp;token=' . $token;

							$mail = new mailer;
							$mail->setSender(XENUX_MAIL);
							$mail->setReplyTo($app->getOption('admin_email'));
							$mail->addAdress($data['email'], $data['firstname'] . ' ' . $data['lastname']);
							$mail->setSubject(__('confirm registration on', $app->getOption('hp_name')));
							$mail->setMessage(
								'<p>' . __('helloUser', $data['firstname']) . '!</p>' .
								'<p>' . __('open link to confirm registration', MAIN_URL) . '<br>' .
								'<a href="' . str_replace('&amp;', '&', $confirmlink) . '">' . $confirmlink . '</a></p>' .
								'<p>' . __('not registered by self', MAIN_URL) . '</p>'
							);

							if (!$mail->send())
							{
	/*							$msgTemplate = new template($this->getFormTemplateURL('_form_error_msg.php'));
								$msgTemplate->setVar('err_message', $message);
								$messages .= $msgTemplate->render();*/
								$template->setVar('message', '<p>' . __('message couldnt sent') . '</p>');
							}
							else
							{
								$template->setVar('message', '<p>' . __('please confirm registration') . '</p>');
							}

							return false;
						}
						else
						{
							log::setPHPError('something went wrong -.-');
							ErrorPage::view(500);
						}

					}
					else
					{
						$registerform->setErrorMsg(__('passwords not equal'));
						$registerform->setFieldInvalid('passwordAgain');
					}
				}
				else
				{
					$registerform->setErrorMsg(__('email domain not allowed. only allowed', implode(', ', $allowed_domains)));
					$registerform->setFieldInvalid('email');
				}
			}
			else
			{
				$registerform->setErrorMsg(__('email exists'));
				$registerform->setFieldInvalid('email');
			}
		}

		$template->setVar('form',  $registerform->getForm());
	}

	private function forgotpasswordAction(&$template)
	{
		global $app, $XenuxDB;

		$formFields = array
		(
			'email' => array
			(
				'type'     => 'text',
				'required' => true,
				'label'    => __('email')
			),
			'submit' => array
			(
				'type'  => 'submit',
				'label' => __('resetPassword')
			)
		);

		$forgotpasswordform = new form($formFields);
		$forgotpasswordform->disableRequiredInfo();

		if ($forgotpasswordform->isSend() && $forgotpasswordform->isValid())
		{
			$data = $forgotpasswordform->getInput();

			$userFoundByEmail = $app->user->getUserByEmail($data['email']);

			if ($userFoundByEmail) // check if user exists
			{
				$userinfo = $app->user->userInfo;
				if (empty($userinfo->password)) // user has not set his password
				{
					$template->setVar('message', __('please set your first password'));
					return false;
				}
				$token = generateRandomString();

				$result = $XenuxDB->Update('users', [
					'verifykey' => $token
				],
				[
					'id' => $userinfo->id
				]);
				if (!$result)
				{
					log::setPHPError('something went wrong -.-');
					ErrorPage::view(500);
					return false;
				}

				$url = MAIN_URL . '/login?task=resetpassword&amp;id=' . $userinfo->id . '&amp;token=' . $token;

				$mail = new mailer;
				$mail->setSender(XENUX_MAIL);
				$mail->setReplyTo($app->getOption('admin_email'));
				$mail->addAdress($userinfo->email, $userinfo->firstname . ' ' . $userinfo->lastname);
				$mail->setSubject(__('forgotPassword'));
				$mail->setMessage(
					'<p>' . __('helloUser', $userinfo->firstname) . '</p>' .
					'<p>' . __('your requested forgotPassword', date('d.m.Y'), date('H:i'), $_SERVER['REMOTE_ADDR']) .
					' ' . __('password reset url', str_replace('&amp;', '&', $url), $url) . '</p>
					<p>' . __('ignore forgotPassword mail') . '</p>'
				);

				if (!$mail->send())
				{
					$template->setVar('message', '<p>' . __('message couldnt sent') . '</p>');
				}
				else
				{
					$template->setVar('message', '<p>' . __('password reset sent to mail') . '</p>');
				}
			}
			else
			{
				$template->setVar('message', '<p>' . __('clouldnt match account with email', $data['email']) . '</p>');
			}
		}

		$template->setVar('form',  $forgotpasswordform->getForm());
	}

	private function resetpasswordAction(&$template)
	{
		global $app, $XenuxDB;

		if (!isset($_GET['id']) || !isset($_GET['token']))
		{
			ErrorPage::view(405, __('error occurred, please review validity of link'));
			return false;
		}

		$userfound = $XenuxDB->getEntry('users', [
			'columns'=> [
				'id'
			],
			'where'=> [
				'id' => $_GET['id'],
				'verifykey' => $_GET['token']
			]
		]);
		if ($userfound)
		{
			$userinfo = $app->user->getUserInfo($userfound->id);

			$formFields = array
			(
				'password' => array
				(
					'type'       => 'password',
					'required'   => true,
					'label'      => __('password')
				),
				'passwordAgain' => array
				(
					'type'       => 'password',
					'required'   => true,
					'label'      => __('passwordAgain')
				),
				'submit' => array
				(
					'type'  => 'submit',
					'label' => __('resetPassword')
				)
			);

			$forgotpasswordform = new form($formFields);
			$forgotpasswordform->disableRequiredInfo();

			if ($forgotpasswordform->isSend() && $forgotpasswordform->isValid())
			{
				$data = $forgotpasswordform->getInput();

				if ($data['password'] == $data['passwordAgain'])
				{
					$return = $XenuxDB->Update('users', [
						'verifykey' => NULL,
						'password' => $app->user->createPasswordHash($data['password'])
					],
					[
						'id' => $userinfo->id
					]);

					if ($return)
					{
						$template->setVar('message', '<p>' . __('passsword reset successful') . '</p>');
						$template->setVar('form', '');
						return false;
					}
					else
					{
						log::setPHPError('something went wrong -.-');
						ErrorPage::view(500);
					}
				}
				else
				{
					$template->setVar('message', '<p>' . __('entered passwords not equal') . '<p>');
				}
			}

			$template->setVar('form',  $forgotpasswordform->getForm());
		}
		else
		{
			ErrorPage::view(405, __('error occurred, please review validity of link'));
		}
	}

	private function setPasswordAction(&$template)
	{
		global $app, $XenuxDB;

		if (!isset($_GET['id']) || !isset($_GET['token']))
		{
			ErrorPage::view(405, __('error occurred, please review validity of link'));
			return false;
		}

		$userfound = $XenuxDB->getEntry('users', [
			'columns'=> [
				'id'
			],
			'where'=> [
				'id' => $_GET['id'],
				'verifykey' => $_GET['token']
			]
		]);
		if ($userfound)
		{
			$userinfo = $app->user->getUserInfo($userfound->id);

			$formFields = array
			(
				'password' => array
				(
					'type'     => 'password',
					'required' => true,
					'label'    => __('password'),
				),
				'passwordAgain' => array
				(
					'type'     => 'password',
					'required' => true,
					'label'    => __('passwordAgain'),
				),
				'submit' => array
				(
					'type'  => 'submit',
					'label' => __('resetPassword')
				)
			);

			$forgotpasswordform = new form($formFields);
			$forgotpasswordform->disableRequiredInfo();

			if ($forgotpasswordform->isSend() && $forgotpasswordform->isValid())
			{
				$data = $forgotpasswordform->getInput();

				if ($data['password'] == $data['passwordAgain'])
				{
					$return = $XenuxDB->Update('users', [
						'verifykey' => NULL,
						'password' => $app->user->createPasswordHash($data['password'])
					],
					[
						'id' => $userinfo->id
					]);

					if ($return)
					{
						$mail = new mailer;
						$mail->setSender(XENUX_MAIL);
						$mail->setReplyTo($app->getOption('admin_email'));
						$mail->addAdress($userinfo->email, $userinfo->firstname . ' ' . $userinfo->lastname);
						$mail->setSubject(__('saved password'));
						$mail->setMessage(
							'<p>' . __('helloUser', $userinfo->firstname) . '!</p>' .
							'<p>' . __('saved password msg mail', MAIN_URL) . '</p>');
						$mail->send();

						$template->setVar('message', '<p>' . __('saved password msg') . '</p>');
						$app->user->setLogin();

						header('Location: ' . MAIN_URL . (isset($_GET['redirectTo']) ? $_GET['redirectTo'] : ''));
					}
					else
					{
						log::setPHPError('something went wrong -.-');
						ErrorPage::view(500);
					}
				}
				else
				{
					$template->setVar('message', '<p>' . __('entered passwords not equal') . '<p>');
				}
			}

			$template->setVar('form',  $forgotpasswordform->getForm());
		}
		else
		{
			ErrorPage::view(405, __('error occurred, please review validity of link'));
		}
	}

	private function confirmAction(&$template)
	{
		global $app, $XenuxDB;

		if (!isset($_GET['id']) || !isset($_GET['token']))
		{
			ErrorPage::view(405, __('error occurred, please review validity of link'));
			return false;
		}

		$user = $XenuxDB->getEntry('users', [
			'columns'=> [
				'id'
			],
			'where'=> [
				'id' => $_GET['id'],
				'verifykey' => $_GET['token']
			]
		]);

		if ($user)
		{
			@$app->user->userInfo->id = $user->id;

			$return = $XenuxDB->Update('users', [
				'verifykey' => NULL,
				'last_confirmed' => date('Y-m-d H:i:s')
			],
			[
				'id' => $user->id
			]);

			if ($return)
			{
				$template->setIfCondition('confirmSuccessful', true);
				$app->user->setLogin();
				header('Refresh:5; url=' . MAIN_URL, true, 303);
			}
		}
		else
		{
			ErrorPage::view(405, __('error occurred, please review validity of link'));
		}
	}
}
