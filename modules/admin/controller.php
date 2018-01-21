<?php
class adminController extends AbstractController
{
	public function run()
	{
		global $XenuxDB, $app;

		if (!$app->user->isLogin())
			ErrorPage::view(401);

		// append translations
		translator::appendTranslations(MAIN_PATH . '/modules/'.$this->modulename.'/translation/');

		$template = new template(MAIN_PATH."/modules/".$this->modulename."/layout.php");

		$template->setVar("messages", '');
		$template->setVar("form", $this->getForm($template));

		if (isset($_GET['savingSuccess']) && parse_bool($_GET['savingSuccess']) == true)
			$template->setVar("messages", '<p class="box-shadow info-message ok">'.__('savedSuccessful').'</p>');
		if (isset($_GET['savingSuccess']) && parse_bool($_GET['savingSuccess']) == false)
			$template->setVar("messages", '<p class="box-shadow info-message error">'.__('savingFailed').'</p>');


		echo $template->render();

		$this->page_name = __('options');

		return true;
	}


	private function getForm(&$template)
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
}
