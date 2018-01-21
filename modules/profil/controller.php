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
		translator::appendTranslations(MAIN_PATH . '/modules/'.$this->modulename.'/translation/');

		$this->userEdit();


		$this->page_name = __('profile');
	}

	private function userEdit()
	{
		global $XenuxDB, $app;

		$app->addJS(MAIN_URL . '/js/modules/' . $this->modulename . '/profil.js');

		$template = new template(MAIN_PATH . '/modules/' . $this->modulename . '/layout_edit.php');
		$template->setVar('form', $this->getEditForm($template));

		if (isset($_GET['savingSuccess']) && parse_bool($_GET['savingSuccess']) == true)
			$this->messages[] = '<p class="box-shadow info-message ok">'.__('savedSuccessful').'</p>';
		if (isset($_GET['savingSuccess']) && parse_bool($_GET['savingSuccess']) == false)
			$this->messages[] = '<p class="box-shadow info-message error">'.__('savingFailed').'</p>';

		$template->setVar('messages', implode("\n", $this->messages));

		echo $template->render();
	}

	private function getEditForm(&$template)
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
}
