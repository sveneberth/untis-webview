<?php
class adminController extends AbstractController
{
	private $editUserID;
	private $messages = array();

	public function __construct($url)
	{
		parent::__construct($url);

		if (!isset($this->url[1]) || empty($this->url[1]))
			header('Location: '.MAIN_URL.'/'.$this->modulename.'/upload');
	}

	public function run()
	{
		global $XenuxDB, $app;

		if (!$app->user->isLogin())
			ErrorPage::view(401);

		// append translations
		translator::appendTranslations(MAIN_PATH.'/modules/'.$this->modulename.'/translation/');

		$template = new template(MAIN_PATH.'/modules/'.$this->modulename.'/layout.php', [
			'modulename' => $this->modulename,
			'method' => $this->url[1]
		]);

		$template->setVar('messages', '');
		$template->setVar('text', '');

		if ($this->url[1] == 'upload')
		{
			$template->setVar('form', $this->getUploadForm($template));
			$template->setVar('text', '<p>' . __('upload a export file to update substitute plan') . '</p>');

			$app->addJS(MAIN_URL . '/js/modules/' . $this->modulename . '/upload.js');
		}
		elseif ($this->url[1] == 'settings')
		{
			$template->setVar('form', $this->getSettingsForm($template));
		}
		elseif ($this->url[1] == 'users')
		{
			if (!isset($this->url[2]) || empty($this->url[2]))
				header('Location: '.MAIN_URL.'/'.$this->modulename.'/users/home');

			if (@$this->url[2] == 'home')
			{
				$template->setVar('form', $this->getUserHome($template));
				#$this->userHome();
			}
			elseif (@$this->url[2] == 'edit')
			{
				if (isset($this->url[3]) && is_numeric($this->url[3]) && !empty($this->url[3]))
				{
					$this->editUserID = $this->url[3];
					$template->setVar('form', $this->getUserEdit());
					$app->addJS(MAIN_URL . '/js/modules/' . $this->modulename . '/user_edit.js');
				}
				else
				{
					throw new Exception(__('isWrong', 'users ID'));
				}
			}
			elseif (@$this->url[2] == 'profile')
			{
				$this->editUserID = $app->user->userInfo->id;
				$template->setVar('form', $this->getUserEdit());
				$app->addJS(MAIN_URL . '/js/modules/' . $this->modulename . '/user_edit.js');

				$this->page_name = __('profile');
			}
			elseif (@$this->url[2] == 'new')
			{
				$template->setVar('form', $this->getUserEdit(true));
				$app->addJS(MAIN_URL . '/js/modules/' . $this->modulename . '/user_edit.js');
			}
			else
			{
				throw new Exception("404 - $this->modulename template not found");
			}

			#TODO: build it
			//$template->setVar('form', $this->getUsersForm($template));
		}
		else
		{
			throw new Exception("404 - $this->modulename method <i>{$this->url[1]}</i> not found");
		}

		if (isset($_GET['savingSuccess']) && parse_bool($_GET['savingSuccess']) == true)
			$template->setVar("messages", '<p class="box-shadow info-message ok">'.__('savedSuccessful').'</p>');
		if (isset($_GET['savingSuccess']) && parse_bool($_GET['savingSuccess']) == false)
			$template->setVar("messages", '<p class="box-shadow info-message error">'.__('savingFailed').'</p>');


		echo $template->render();

		$this->page_name = __('options');

		return true;
	}


	private function getUploadForm()
	{
		global $app, $XenuxDB;

		$formFields = array
		(
			'file' => array
			(
				'type'     => 'file',
				'required' => true,
				'multiple' => false,
				'label'    => __('upload file')
			),
			'submit' => array
			(
				'type'  => 'submit',
				'label' => __('upload')
			)
		);

		$form = new form($formFields);
		$form->disableRequiredInfo();


		if ($form->isSend() && $form->isValid())
		{
			$ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);

			if (strtolower($ext) == 'txt')
			{
				$hp_offline = $app->getOption('homepage_offline');
				if ($hp_offline == false)
					$XenuxDB->Update('main', [ // set homepage in maintenance
						'value' => true
					],
					[
						'name' => 'homepage_offline'
					]);


				if (($handle = fopen($_FILES['file']['tmp_name'], 'r')) !== false) {
					$XenuxDB->clearTable('substitute_plan'); // clear old data

					while (($data = fgetcsv($handle)) !== false)
					{
						$XenuxDB->Insert('substitute_plan', [
							'date'               => $data[1],
							'hour'               => $data[2],
							'absent_teacher'     => $data[5],
							'substitute_teacher' => $data[6],
							'absent_subject'     => $data[7],
							'substitute_subject' => $data[9],
							'absent_room'        => $data[11],
							'substitute_room'    => $data[12],
							'absent_class'       => json_encode(explode('~', $data[14])),
							'substitute_class'   => json_encode(explode('~', $data[18])),
							'reason'             => $data[15],
							'kind'               => $data[17],
							'type'               => $data[19],
							'text'               => $data[16]
						]);
					}
					fclose($handle);
				}

				$this->template->setVar("messages", '<p class="box-shadow info-message ok">'.__('data upload successful').'</p>');

				if ($hp_offline == false)
					$XenuxDB->Update('main', [ // set homepage out of maintenance
						'value' => false
					],
					[
						'name' => 'homepage_offline'
					]);
			}
			else
			{
				// not a txt-file
				$this->template->setVar("messages", '<p class="box-shadow info-message warning">'.__('Please upload a txt-file (csv/dif-format).').'</p>');
			}
		}

		return $form->getForm();
	}


	private function getSettingsForm(&$template)
	{
		global $XenuxDB, $app;

		$formFields = array
		(
/*			'meta_author' => array
			(
				'type'     => 'text',
				'required' => true,
				'label'    => __('meta_author'),
				'value'    => $app->getOption('meta_author')
			),*/
			'school_name' => array
			(
				'type'     => 'text',
				'required' => true,
				'label'    => __('school_name'),
				'value'    => $app->getOption('school_name')
			),
/*			'meta_desc' => array
			(
				'type'  => 'textarea',
				'label' => __('meta_desc'),
				'value' => $app->getOption('meta_desc'),
				'info'  => __('description of the homepage for meta tags')
			),
			'meta_keys' => array
			(
				'type'     => 'textarea',
				'required' => true,
				'label'    => __('meta_keys'),
				'value'    => $app->getOption('meta_keys'),
				'info'     => __('keywords of the homepage for meta tags')
			),*/
			'admin_email' => array
			(
				'type'     => 'email',
				'required' => true,
				'label'    => __('admin_email'),
				'value'    => $app->getOption('admin_email')
			),
			'users_can_register' => array
			(
				'type'     => 'bool_radio',
				'required' => true,
				'label'    => __('users_can_register'),
				'value'    => parse_bool($app->getOption('users_can_register')),
			),
			'homepage_offline' => array
			(
				'type'     => 'bool_radio',
				'required' => true,
				'label'    => __('homepage_offline'),
				'value'    => parse_bool($app->getOption('homepage_offline')),
			),
			'allowed_domains' => array
			(
				'type'     => 'textarea',
				'required' => true,
				'label'    => __('allowed_domains'),
				'value'    => implode("\r\n", json_decode($app->getOption('allowed_domains')))
			),
			'submit' => array
			(
				'type'  => 'submit',
				'label' => __('save')
			)
		);

		$form = new form($formFields);
		$form->disableRequiredInfo();

		if ($form->isSend() && $form->isValid())
		{
			$data = $form->getInput();

			$success = true;
			foreach ($formFields as $name => $props)
			{
				if ($name == 'allowed_domains')
				{
					var_dump($data[$name]);
					$data[$name] = json_encode(explode("\r\n", $data[$name]));
				}

				$return = $XenuxDB->Update('main', [
					'value' => $data[$name]
				],
				[
					'name' => $name
				]);
				if ($return === false)
					$success = false;
			}

			if ($success)
			{
				header('Location: ' . MAIN_URL . '/admin/?savingSuccess=true');
			}
			else
			{
				header('Location: ' . MAIN_URL . '/admin/?savingSuccess=false');
			}

		}
		return $form->getForm();
	}




	private function getUserHome()
	{
		global $app, $XenuxDB;

		$template = new template(MAIN_PATH.'/modules/'.$this->modulename.'/layout_home.php');

		#FIXME: removed user remains as author in pages/post/etc. This sucks
		if (isset($_GET['remove']) && is_numeric($_GET['remove']) && !empty($_GET['remove']))
		{
			$XenuxDB->delete('users', [
				'where' => [
					'id' => $_GET['remove']
				]
			]);
			$this->messages[] = '<p class="box-shadow info-message ok">'.__('removedSuccessful').'</p>';
		}

		if (isset($_GET['action']) && in_array($_GET['action'], ['remove'])
			&& isset($_GET['item']) && is_array($_GET['item']))
		{
			foreach ($_GET['item'] as $item) {
				if (is_numeric($item)) {
					switch ($_GET['action']) {
						case 'remove':
							$XenuxDB->delete('users', [
								'where' => [
									'id' => $item
								]
							]);
							break;
					}
					$template->setVar('messages',
						'<p class="box-shadow info-message ok">' . __('batch processing successful') . '</p>');
				}
			}
		}

		$template->setVar('users', $this->getUserTable());
		$template->setVar('amount', $XenuxDB->count('users'));

		if (isset($_GET['savingSuccess']) && parse_bool($_GET['savingSuccess']) == true)
			$this->messages[] = '<p class="box-shadow info-message ok">'.__('savedSuccessful').'</p>';
		if (isset($_GET['savingSuccess']) && parse_bool($_GET['savingSuccess']) == false)
			$this->messages[] = '<p class="box-shadow info-message error">'.__('savingFailed').'</p>';


		$template->setVar('messages', implode("\n", $this->messages));

		$app->addJS(TEMPLATE_URL . '/static/js/jquery.tablesorter.min.js');
		$app->addJS(MAIN_URL . '/modules/' . $this->modulename . '/user.js');
		$this->page_name = __('home');
		$this->headlineSuffix = '<a class="btn btn-vEdit" href="{{MAIN_URL}}/admin/users/new">' . __('new') . '</a>';

		return $template->render();
	}

	private function getUserTable()
	{
		global $XenuxDB;

		$return = '';

		$users = $XenuxDB->getList('users', [
			'order' => 'username ASC'
		]);
		if ($users)
		{
			foreach ($users as $user)
			{
				$return .= '
<tr>
	<td class="column-select"><input type="checkbox" name="item[]" value="' . $user->id . '"></td>
	<td class="column-id">' . $user->id . '</td>
	<td class="column-text">
		<a class="edit" href="{{MAIN_URL}}/admin/users/edit/' . $user->id . '" title="' . __('click to edit user') . '">' . $user->email . '</a>
	</td>
	<td class="column-text">' . $user->firstname . '</td>
	<td class="column-text">' . $user->lastname . '</td>
	<td class="column-actions">
		<a href="{{MAIN_URL}}/admin/users/home/?remove=' . $user->id . '" title="' . __('delete') . '" class="remove-btn">
			' . embedSVG(TEMPLATE_PATH.'/static/img/trash.svg') . '
		</a>
	</td>
</tr>';
			}
		}

		return $return;
	}


	private function getUserEdit($new=false)
	{
		$template = new template(MAIN_PATH.'/modules/'.$this->modulename.'/layout_edit.php', [
			'profileEdit' => @$this->url[1] == 'profile'
		]);

		$template->setVar('form', $this->getUserEditForm($template, $new));

		$template->setIfCondition('new', $new);
		$template->setIfCondition('profileEdit', @$this->url[1] == 'profile');

		if (isset($_GET['savingSuccess']) && parse_bool($_GET['savingSuccess']) == true)
			$this->messages[] = '<p class="box-shadow info-message ok">'.__('savedSuccessful').'</p>';
		if (isset($_GET['savingSuccess']) && parse_bool($_GET['savingSuccess']) == false)
			$this->messages[] = '<p class="box-shadow info-message error">'.__('savingFailed').'</p>';

		$template->setVar('messages', implode("\n", $this->messages));

		$this->page_name = $new ? __('new') : __('edit');

		return $template->render();
	}

	private function getUserEditForm(&$template, $new=false)
	{
		global $XenuxDB, $app;

		if (!$new)
			$user = $XenuxDB->getEntry('users', [
				'where' => [
					'id' => $this->editUserID
				]
			]);

		if (!@$user && !$new)
			throw new Exception('error (user 404)');

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

		if ($new) {
			// unset, not needed -> user get email
			unset($formFields['password']);
			unset($formFields['passwordAgain']);
		}

		$form = new form($formFields);
	//	$form->disableRequiredInfo();

		if ($form->isSend() && isset($form->getInput()['cancel']))
		{
			header('Location: '.MAIN_URL.'/admin/users/home');
			return false;
		}

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

			if ($userFoundByEmail || !in_array($email_domain, $allowed_domains))
			{
				return $form->getForm();
			}

			$success = true;

			if ($new)
			{
				$token = generateRandomString();

				$user = $XenuxDB->Insert('users', [
					'firstname'    => $data['firstname'],
					'lastname'     => $data['lastname'],
					'email'        => $data['email'],
					'type'         => $data['type'],
					'class'        => $data['class'],
					'abbreviation' => $data['abbreviation'],
					'password'     => '',
					'verifykey'    => $token,
					'last_confirmed' => date('Y-m-d H:i:s')
				]);


				if (is_numeric($user) && $user != 0)
				{
					$url = MAIN_URL . '/login?task=setpassword&amp;id=' . $user . '&amp;token=' . $token;

					$mail = new mailer;
					$mail->setSender(XENUX_MAIL);
					$mail->setReplyTo($app->getOption('admin_email'));
					$mail->addAdress($data['email'], $data['firstname'] . $data['lastname']);
					$mail->setSubject('Benutzeraccount erstellt');
					$mail->setMessage('Hallo!<br>
<p>Es wurde für dich auf <a href="' . MAIN_URL . '">' . MAIN_URL . '</a> ein Benutzeraccount angelegt.</p>
<p>Benutzername: ' .  $username . '</p>
<p>Unter der folgenden Adresse kannst du dein Passwort festlegen:<br>
<a href="' . $url . '">' . $url . '</a></p>');

					if (!$mail->send())
					{
						$this->messages[] = '<p class="box-shadow info-message warning">Die Nachricht konnte nicht versendet werden.</p>';
						$template->setVar('message', '<p>Die Nachricht konnte nicht versendet werden.</p>');
						$success = false;
					}
					else
					{
						$this->messages[] = '<p class="box-shadow info-message ok">Mail an den Nutzer erfolgreich versand.</p>';
						$this->editUserID = $user;
					}
				}
				else
				{
					$success = false;
				}
			}
			else
			{
				if ((isset($data['password']) && !empty($data['password'])) || (isset($data['passwordAgain']) && !empty($data['passwordAgain']) && in_array($email_domain, $allowed_domains)))
				{
					// password change
					if ($data['password'] == $data['passwordAgain'])
					{
						$return = $XenuxDB->Update('users', [
							'password' => $app->user->createPasswordHash($data['password']),
						],
						[
							'id' => $this->editUserID
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
					'id' => $this->editUserID
				]);

				if ($return === false)
					$success = false;
			}

			if ($success === true)
			{
				log::debug('user saved successfull');
				$this->messages[] = '<p class="box-shadow info-message ok">'.__('savedSuccessful').'</p>';

				if (isset($data['submit_close']))
				{
					header('Location: '.MAIN_URL.'/admin/users/home?savingSuccess=true');
					return false;
				}

				header('Location: '.MAIN_URL.'/admin/users/edit/'.$this->editUserID.'?savingSuccess=true');
			}
			else
			{
				log::debug('user saving failed');
				$this->messages[] = '<p class="box-shadow info-message error">'.__('savingFailed').'</p>';

				if (isset($data['submit_close']) || $new)
				{
					header('Location: '.MAIN_URL.'/admin/users/home?savingSuccess=false');
					return false;
				}

				header('Location: '.MAIN_URL.'/admin/users/edit/'.$this->editUserID.'?savingSuccess=false');
			}
		}
		return $form->getForm();
	}
}
