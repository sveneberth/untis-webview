<?php
class ueberController extends AbstractController
{
	public function run()
	{
		global $app;

		$template = new template(MAIN_PATH . '/modules/' . $this->modulename . '/layout.php');
		echo $template->render();

		$this->page_name = 'Ueber';

		return true;
	}
}
