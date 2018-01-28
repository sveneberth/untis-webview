<?php
class vertretungsplanController extends AbstractController
{
	private $showAll = false;
	private $userIsStudent;

	public function run()
	{
		global $XenuxDB, $app;

		if (!$app->user->isLogin())
			ErrorPage::view(401);

		if (parse_bool(@$_GET['showAll']) === true)
			$this->showAll = true;

		$this->userIsStudent = $app->user->userInfo->type == 'student';

		// append translations
		translator::appendTranslations(MAIN_PATH.'/modules/'.$this->modulename.'/translation/');

		$app->addJS(MAIN_URL . '/js/modules/' . $this->modulename . '/vertretungsplan.js');

		$this->template = new template(MAIN_PATH.'/modules/'.$this->modulename.'/layout.php');
		$this->template->setVar('main', $this->getSubstitutePlan());
		$this->template->setVar('filterSwitch', $this->getFilter());
		echo $this->template->render();

		$this->page_name = __('substituteplan');

		return true;
	}

	private function getSubstitutePlan()
	{
		global $app, $XenuxDB;

		if ($this->userIsStudent && !$this->showAll)
		{
			$where = [
				'AND' => [
					'##date[>=]' => 'NOW()',
					'OR' => [
						'absent_class[~]' => $app->user->userInfo->class,
						'substitute_class[~]' => $app->user->userInfo->class
					]
				]
			];
		}
		elseif (!$this->userIsStudent && !$this->showAll) // teacher
		{
			$where = [
				'AND' => [
					'##date[>=]' => 'NOW()',
					'OR' => [
						'absent_teacher[~]' => $app->user->userInfo->abbreviation,
						'substitute_teacher[~]' => $app->user->userInfo->abbreviation
					]
				]
			];
		}
		else
		{
			$where = [
				'##date[>=]' => 'NOW()'
			];
		}

		$dates = $XenuxDB->getList('substitute_plan', [
			'columns' => 'date',
			'order' => 'date ASC',
			'group' => 'date',
			'where' => $where
		]);

		$sections = '';
		foreach ($dates as $date)
		{
			$tableTemplate = new template(MAIN_PATH.'/modules/'.$this->modulename.'/layout_table.php');

			$tableTemplate->setVar('date', __(mysql2date('D', $date->date)) . mysql2date(', d.m.Y', $date->date));


			if ($this->userIsStudent && !$this->showAll)
			{
				$where = [
					'AND' => [
						'date' => $date->date,
						'OR' => [
							'absent_class[~]' => $app->user->userInfo->class,
							'substitute_class[~]' => $app->user->userInfo->class
						]
					]
				];
			}
			elseif (!$this->userIsStudent && !$this->showAll) // teacher
			{
				$where = [
					'AND' => [
						'date' => $date->date,
						'OR' => [
							'absent_teacher[~]' => $app->user->userInfo->abbreviation,
							'substitute_teacher[~]' => $app->user->userInfo->abbreviation
						]
					]
				];
			}
			else
			{
				$where = [
					'date' => $date->date,
				];
			}

			$entries = $XenuxDB->getList('substitute_plan', [
				'order' => [
					'date ASC',
					'absent_class ASC',
					'hour ASC'
				],
				'where' => $where
			]);

			if ($entries)
			{
				$tableBody = '';
				foreach ($entries as $entry)
				{
					$rowTemplate = new template(MAIN_PATH . '/modules/' . $this->modulename . '/layout_row.php');

					$types = [
						''  => '',
						'T' => 'verlegt',
						'F' => 'verlegt von',
						'W' => 'Tausch',
						'S' => 'Betreuung',
						'A' => 'Sondereinsatz',
						'C' => 'Entfall',
						'L' => 'Freisetzung',
						'P' => 'Teil-Vertretung',
						'R' => 'Raumvertretung',
						'B' => 'Pausenaufsichtsvertretung',
						'~' => 'Lehrertausch',
						'E' => 'Klausur'
					];

					$absent_class_readable = implode(', ', json_decode($entry->absent_class));
					$substitute_class_readable = implode(', ', json_decode($entry->substitute_class));

					$class = $entry->absent_class == $entry->substitute_class ? $absent_class_readable : '<del>'.$absent_class_readable.'</del> '.$substitute_class_readable;
					$teacher = $entry->absent_teacher == $entry->substitute_teacher ? $entry->absent_teacher : '<del>'.$entry->absent_teacher.'</del> '.$entry->substitute_teacher;
					$subject = $entry->absent_subject == $entry->substitute_subject ? $entry->absent_subject : '<del>'.$entry->absent_subject.'</del> '.$entry->substitute_subject;
					$room = $entry->absent_room == $entry->substitute_room ? $entry->absent_room : '<del>'.$entry->absent_room.'</del> '.$entry->substitute_room;

					$rowTemplate->setVar('hour', $entry->hour);
					$rowTemplate->setVar('absent_class', $absent_class_readable);
					$rowTemplate->setVar('substitute_class', $substitute_class_readable);
					$rowTemplate->setVar('class', $class);
					$rowTemplate->setVar('absent_teacher', $entry->absent_teacher);
					$rowTemplate->setVar('substitute_teacher', $entry->substitute_teacher);
					$rowTemplate->setVar('teacher', $teacher);
					$rowTemplate->setVar('absent_subject', $entry->absent_subject);
					$rowTemplate->setVar('substitute_subject', $entry->substitute_subject);
					$rowTemplate->setVar('subject', $subject);
					$rowTemplate->setVar('absent_room', $entry->absent_room);
					$rowTemplate->setVar('substitute_room', $entry->substitute_room);
					$rowTemplate->setVar('room', $room);
					$rowTemplate->setVar('date', __(mysql2date('D', $date->date)) . mysql2date(', d.m.Y', $date->date));
					$rowTemplate->setVar('type', $types[$entry->type]);
					#$rowTemplate->setVar('type', $entry->type);
					$rowTemplate->setVar('text', $entry->text);

					$tableBody .= $rowTemplate->render();
				}

				$tableTemplate->setVar('tableBody', $tableBody);

				$sections .= $tableTemplate->render();
			}
			else
			{
				$sections .= '<p style="margin:5px 0;">' . __('no representations') . '!</p>';
			}
		}

		return $sections;
	}

	private function getFilter()
	{
		global $app, $XenuxDB;

		$template = new template(MAIN_PATH . '/template/form/_form_checkbox.php');
		$template->setVar('label', __('show all substitutes'));
		$template->setVar('name', 'showAll-switch');
		$template->setVar('class', 'showAll-switch');
		$template->setVar('classGroup', 'showAll-switch-group');
		$template->setVar('style', '');
		$template->setVar('checked', $this->showAll ? 'checked="checked"' : '');
		$template->setVar('value', 'true');

		return $template->render();
	}
}
