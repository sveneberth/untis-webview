<?php
class vertretungsplanController extends AbstractController
{
	public function run()
	{
		global $XenuxDB, $app;

		if (!$app->user->isLogin())
			ErrorPage::view(401);

		$this->template = new template(MAIN_PATH.'/modules/'.$this->modulename.'/layout.php');

		$this->template->setVar('main', $this->getSubstitutePlan());

		echo $this->template->render();

		$this->page_name = __('substituteplan');

		return true;
	}

	private function getSubstitutePlan()
	{
		global $XenuxDB;

		$dates = $XenuxDB->getList('substitute_plan', [
			'columns' => 'date',
			'order' => 'date ASC',
			'group' => 'date',
			'where' => [
				'##date[>=]' => 'NOW()'
			]
		]);

		$sections = '';
		foreach ($dates as $date)
		{
			$tableTemplate = new template(MAIN_PATH.'/modules/'.$this->modulename.'/layout_table.php');

			$tableTemplate->setVar('date', __(mysql2date('D', $date->date)) . mysql2date(', d.m.Y', $date->date));

			$entries = $XenuxDB->getList('substitute_plan', [
				'order' => [
					'date ASC',
					'absent_class ASC',
					'hour ASC'
				],
				'where' => [
					'date' => $date->date
				]
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

					$rowTemplate->setVar('hour', $entry->hour);
					$rowTemplate->setVar('absent_teacher', $entry->absent_teacher);
					$rowTemplate->setVar('substitute_teacher', $entry->substitute_teacher);
					$rowTemplate->setVar('absent_subject', $entry->absent_subject);
					$rowTemplate->setVar('substitute_subject', $entry->substitute_subject);
					$rowTemplate->setVar('absent_room', $entry->absent_room);
					$rowTemplate->setVar('substitute_room', $entry->substitute_subject);
					$rowTemplate->setVar('absent_class', implode(', ', json_decode($entry->absent_class)));
					$rowTemplate->setVar('substitute_class', implode(', ', json_decode($entry->substitute_class)));
					$rowTemplate->setVar('date', __(mysql2date('D', $date->date)) . mysql2date(', d.m.Y', $date->date));
					$rowTemplate->setVar('type', $types[$entry->type]);
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
}
