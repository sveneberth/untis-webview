<?php
#TODO: translation
class profilController extends AbstractController
{
	private $messages = array();

	public function run()
	{
		global $XenuxDB, $app;

		if (!$app->user->isLogin())
			ErrorPage::view(401);

		// append translations
		translator::appendTranslations(MAIN_PATH.'/modules/'.$this->modulename.'/translation/');
		$app->addJS(MAIN_URL . '/js/modules/' . $this->modulename . '/profil.js');

		$this->template = new template(MAIN_PATH.'/modules/'.$this->modulename.'/layout.php');

		$this->userEdit();
		$this->ConfirmForm();

		echo $this->template->render();
		$this->page_name = __('profile');
	}

	private function userEdit()
	{
		global $XenuxDB, $app;

		$this->template->setVar('form', $this->getEditForm());

		if (isset($_GET['savingSuccess']) && parse_bool($_GET['savingSuccess']) == true)
			$this->messages[] = '<p class="box-shadow info-message ok">'.__('savedSuccessful').'</p>';
		if (isset($_GET['savingSuccess']) && parse_bool($_GET['savingSuccess']) == false)
			$this->messages[] = '<p class="box-shadow info-message error">'.__('savingFailed').'</p>';

		$this->template->setVar('messages', implode("\n", $this->messages));

	}

	private function getEditForm()
	{
		global $XenuxDB, $app;

		$user = $XenuxDB->getEntry('users', [
			'where' => [
				'id' => $app->user->userInfo->id
			]
		]);

		if (!@$user)
			throw new Exception("error (user 404)");


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

		#TODO: add method to reconfirm before 365 days

		$formFields = array
		(
			'firstname' => array
			(
				'type'     => 'text',
				'required' => false,
				'label'    => __('firstname'),
				'value'    => @$user->firstname,
			),
			'lastname' => array
			(
				'type'     => 'text',
				'required' => false,
				'label'    => __('lastname'),
				'value'    => @$user->lastname,
			),
			'email' => array
			(
				'type'     => 'email',
				'required' => true,
				'label'    => __('email'),
				'value'    => @$user->email,
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
				),
				'value'    => @$user->type
			),
			'class' => array
			(
				'type'     => 'select',
				'required' => false,
				'label'    => __('class'),
				'class'    => 'input-class',
				'options'  => $classesOptions,
				'value'    => @$user->class
			),
			'abbreviation' => array
			(
				'type'     => 'text',
				'required' => false,
				'label'    => __('abbreviation'),
				'class'    => 'input-abbreviation',
				'value'    => @$user->abbreviation
			),
			'password' => array
			(
				'type'  => 'password',
				'label' => __('password'),
				'info'  => __('If you dont want to change the password, leave the fields blank'),
			),
			'passwordAgain' => array
			(
				'type'  => 'password',
				'label' => __('passwordAgain'),
				'info'  => __('If you dont want to change the password, leave the fields blank')
			),
			'submit' => array
			(
				'type'  => 'submit',
				'label' => __('save'),
			),
			'clearfix' => array
			(
				'type'  => 'html',
				'value' => '<div class="clear"></div>'
			)
		);

		$form = new form($formFields);
	//	$form->disableRequiredInfo();

		if ($form->isSend() && $form->isValid())
		{
			$data = $form->getInput();

			$userFoundByEmail = $app->user->getUserByEmail($data['email']) && $data['email'] != @$user->email;

			$allowed_domains = json_decode($app->getOption('allowed_domains'));
			list(, $email_domain) = explode('@', $data['email'], 2);

			if ($userFoundByEmail)
			{
				$this->messages[] = '<p class="box-shadow info-message warning">'.__('an user with this email exist already').'</p>';
				return $form->getForm();
			}

			if (!in_array($email_domain, $allowed_domains)) // check if domain is allowed
			{
				$this->messages[] = '<p class="box-shadow info-message warning">'.__('email domain not allowed. only allowed', implode(', ', $allowed_domains)).'</p>';
				return $form->getForm();
			}


			$success = true;

			if ((isset($data['password']) && !empty($data['password'])) || (isset($data['passwordAgain']) && !empty($data['passwordAgain']) && in_array($email_domain, $allowed_domains)))
			{
				// password change
				if ($data['password'] == $data['passwordAgain'])
				{
					$return = $XenuxDB->Update('users', [
						'password' => $app->user->createPasswordHash($data['password']),
					],
					[
						'id' => $app->user->userInfo->id
					]);

					if ($return === false)
						$success = false;
				}
				else
				{
					$this->messages[] = '<p class="box-shadow info-message warning">'.__('the passwords are not equal').'</p>';
					return $form->getForm();
				}
			}

			// update it
			$return = $XenuxDB->Update('users', [
				'firstname'    => $data['firstname'],
				'lastname'     => $data['lastname'],
				'email'        => $data['email'],
				'type'         => $data['type'],
				'class'        => $data['class'],
				'abbreviation' => $data['abbreviation'],
			],
			[
				'id' => $app->user->userInfo->id
			]);

			if ($return === false)
				$success = false;

			if ($success === true)
			{
				log::debug('user saved successfull');
				$this->messages[] = '<p class="box-shadow info-message ok">'.__('savedSuccessful').'</p>';

				header('Location: ' . MAIN_URL . '/profil?savingSuccess=true');
			}
			else
			{
				log::debug('user saving failed');
				$this->messages[] = '<p class="box-shadow info-message error">'.__('savingFailed').'</p>';

				header('Location: ' . MAIN_URL . '/profil?savingSuccess=false');
			}
		}
		return $form->getForm();
	}

	private function ConfirmForm()
	{
		global $XenuxDB, $app;

		// times in unixtime
		$lastConfirmed   = mysql2date('U', $app->user->userInfo->last_confirmed);
		$confirmValidity = $app->getOption('confirmValidity') * 60 * 60 * 24;
		$today           = strtotime(date('Y-m-d'));

		$remain          = ($lastConfirmed + $confirmValidity - $today) / (60 * 60 * 24);

		$this->template->setVar('datelastConfirmed', mysql2date('d.m.Y', $app->user->userInfo->last_confirmed));
		$this->template->setVar('remainingDays', $remain);
		$this->template->setVar('confirmValidity', $app->getOption('confirmValidity'));
		$this->template->setVar('messageConfirm', '');


		if (@$_GET['task'] == 'confirmRequest')
		{
			$token = generateRandomString();

			$return = $XenuxDB->Update('users', [
				'verifykey'    => $token
			],
			[
				'id' => $app->user->userInfo->id
			]);

			if ($return !== false)
			{
				$confirmlink = MAIN_URL . '/login/?task=confirm&amp;id=' . $app->user->userInfo->id . '&amp;token=' . $token;

				$mail = new mailer;
				$mail->setSender(XENUX_MAIL);
				$mail->setReplyTo($app->getOption('admin_email'));
				$mail->addAdress($app->user->userInfo->email, $app->user->userInfo->firstname . ' ' . $app->user->userInfo->lastname);
				$mail->setSubject(__('confirm account on', $app->getOption('hp_name')));
				$mail->setMessage(
					'<p>' . __('helloUser', $app->user->userInfo->firstname) . '</p>' .
					'<p>' . __('open link to confirm account', MAIN_URL) . '<br>' .
					'<a href="' . str_replace('&amp;', '&', $confirmlink) . '">' . $confirmlink . '</a></p>' .
					'<p>' . __('not registered by self', MAIN_URL) . '</p>'
				);

				if (!$mail->send())
				{
					$msgTemplate = new template(MAIN_PATH.'/template/form/_form_error_msg.php');
					$msgTemplate->setVar('err_message', __('message couldnt sent'));
					$this->template->setVar('messageConfirm', $msgTemplate->render());
				}
				else
				{
					$msgTemplate = new template(MAIN_PATH.'/template/form/_form_success_msg.php');
					$msgTemplate->setVar('suc_message', __('please confirm account'));
					$this->template->setVar('messageConfirm', $msgTemplate->render());
				}
			}
			else
			{
				log::setPHPError('something went wrong -.-');
				ErrorPage::view(500);
			}
		}
	}
}
