<?php
class vertretungController extends AbstractController
{
	public function run()
	{
		global $XenuxDB, $app;

		if (!$app->user->isLogin())
			ErrorPage::view(401);

		$this->view();

		$this->page_name = 'Vertretungsplan';

		return true;
	}

	private function view()
	{
		global $XenuxDB;

/*
		$start			= is_numeric(@$_GET['start']) ? floor($_GET['start']) : 0;
		$amount			= (is_numeric(@$_GET['amount']) && floor(@$_GET['amount']) != 0) ? floor($_GET['amount']) : 10;
		$absolutenumber	= $XenuxDB->count('substitute_plan');*/

		$entries = $XenuxDB->getList('substitute_plan', [
			'order' => [
				'date ASC',
				'absent_class ASC',
				'hour ASC'
			],
			/*'limit' => [$start, $amount],
			'where' => [
				'status' => 'publish'
			],
			'join' => [
				'[>]files' => ['posts.thumbnail_id' => 'files.id'],
				'[>]users' => ['posts.author_id' => 'users.id']
			]*/
		]);

		if ($entries)
		{
			echo '<table>';
			foreach ($entries as $entry)
			{
				$template = new template(MAIN_PATH . '/modules/' . $this->modulename . '/layout_list.php');

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

				$template->setVar('hour', $entry->hour);
				$template->setVar('absent_teacher', $entry->absent_teacher);
				$template->setVar('substitute_teacher', $entry->substitute_teacher);
				$template->setVar('absent_subject', $entry->absent_subject);
				$template->setVar('substitute_subject', $entry->substitute_subject);
				$template->setVar('absent_room', $entry->absent_room);
				$template->setVar('substitute_room', $entry->substitute_subject);
				$template->setVar('absent_class', implode(', ', json_decode($entry->absent_class)));
				$template->setVar('substitute_class', implode(', ', json_decode($entry->substitute_class)));
				$template->setVar('date', substr($entry->date, 6, 2) . '.' .  substr($entry->date, 4, 2) . '.' .  substr($entry->date, 0, 4));
				$template->setVar('type', $types[$entry->type]);
				$template->setVar('text', $entry->text);

/*'date'               => $data[1],
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
'text'               => $data[16]*/

				echo $template->render();
			}

			#echo getMenuBarMultiSites($absolutenumber, $start, $amount);
			echo '</table>';

		}
		else
		{
			echo '<p style="margin:5px 0;">' . __('noPosts') . '!</p>';
		}
	}
}
