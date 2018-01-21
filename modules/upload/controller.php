<?php
class uploadController extends AbstractController
{
	public function run()
	{
		global $app, $XenuxDB;

		if (!$app->user->isLogin())
			ErrorPage::view(401);

		// append translations
		translator::appendTranslations(MAIN_PATH . '/modules/' . $this->modulename . '/translation/');

		$app->addJS(MAIN_URL . '/js/modules/' . $this->modulename . '/upload.js');

		$this->template = new template(MAIN_PATH . '/modules/' . $this->modulename . '/layout.php');
		$this->template->setVar('messages', '');
		$this->template->setVar('upload_form', $this->getModulesUploadForm());

		if (isset($_GET['savingSuccess']) && parse_bool($_GET['savingSuccess']) == true)
			$this->template->setVar("messages", '<p class="box-shadow info-message ok">'.__('savedSuccessful').'</p>');
		if (isset($_GET['savingSuccess']) && parse_bool($_GET['savingSuccess']) == false)
			$this->template->setVar("messages", '<p class="box-shadow info-message error">'.__('savingFailed').'</p>');

		echo $this->template->render();

		$this->page_name = __('modules');

		return true;
	}

	private function getModulesUploadForm()
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
						/*echo '<pre>';
						var_dump($data);
						echo '</pre>';*/

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
}
