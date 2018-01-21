<?php
class homeController extends AbstractController
{
	public function run()
	{
		global $app;

		$template = new template(MAIN_PATH . '/modules/' . $this->modulename . '/layout.php');
		$template->setVar('school_name', $app->getOption('school_name'));
		$template->setIfCondition('notLogin', !$app->user->isLogin());
		echo $template->render();

		$this->page_name = 'Home';

		return true;
	}
}
