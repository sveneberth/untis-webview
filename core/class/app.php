<?php
class app
{
	public $template;
	public $site;
	public $url;
	public $page_name;
	public $user;
	public $headlinePrefix;
	public $headlineSuffix;

	private $cssfiles = [];
	private $jsfiles = [];

	public $canonical_URL = REQUEST_URL;
	public $prev_URL = null;
	public $next_URL = null;

	public function __construct($url)
	{
		$this->user = new User;
		$this->url  = explode('/', strtolower($url));

		if (isset($_GET['lang']))
		{
			translator::setLanguage($_GET['lang']);
			header('Location: ' . REQUEST_URL);
		}
	}

	public function getOption($option)
	{
		global $app, $XenuxDB;

		$result = $XenuxDB->getEntry('main', [
			'where' => [
				'name' => $option
			]
		]);

		if ($result !== false && $result !== null)
		{
			return $result->value;
		}

		return false;
	}

	public function buildPage()
	{
		$this->site = empty($this->url[0]) ? 'home' : $this->url[0];

		if ($this->site == 'file')
		{
			new file(@$this->url);
		}
		elseif ($this->site == 'js')
		{
			new ressource(@$this->url, 'js');
		}
		elseif ($this->site == 'css')
		{
			new ressource(@$this->url, 'css');
		}
		else
		{
			// append translations
			translator::appendTranslations(MAIN_PATH . '/template/translation/');

			define('TEMPLATE_PATH', MAIN_PATH . '/template/');
			define('TEMPLATE_URL', MAIN_URL . '/template/');

			$template = new template(MAIN_PATH . '/template/index.php');
			$template->setVar('page_content', $this->getPageContent());

			$template->setVar('CSS-FILES', $this->getCSS());
			$template->setVar('JS-FILES', $this->getJS());

			$template->setVar('meta_author', $this->getOption('meta_author'));
			$template->setVar('meta_desc', $this->getOption('meta_desc'));
			$template->setVar('meta_keys', $this->getOption('meta_keys'));

			$template->setVar('homepage_name', $this->getOption('hp_name'));
			$template->setVar('page_title', $this->page_name);
			$template->setVar('headline', $this->page_name);
			$template->setVar('headlinePrefix', $this->headlinePrefix);
			$template->setVar('headlineSuffix', $this->headlineSuffix);

			$template->setVar('TEMPLATE_URL', TEMPLATE_URL);
			$template->setVar('canonical_URL', $this->site == 'home' ? MAIN_URL : $this->canonical_URL);
			$template->setIfCondition('prev_URL', !is_null($this->prev_URL) && $this->site != 'home');
			$template->setVar('prev_URL', $this->prev_URL);
			$template->setIfCondition('next_URL', !is_null($this->next_URL) && $this->site != 'home');
			$template->setVar('next_URL', $this->next_URL);

			echo $template->render();
		}
	}

	private function getPageContent()
	{
		try
		{
			if (inludeExists(MAIN_PATH . '/modules/' . $this->site . '/controller.php'))
			{
				$classname = $this->site . 'Controller';
				$controller = new $classname($this->url);

				ob_start();
					$controller->run();
				$page_content = ob_get_clean();

				$this->page_name = $controller->page_name;
				$this->headlinePrefix = $controller->headlinePrefix;
				$this->headlineSuffix = $controller->headlineSuffix;

				return $page_content;
			}
			else
			{
				header('HTTP/1.1 404 Not Found');
				throw new Exception('404 - controller "' . $this->site . '" not found');
			}
		}
		catch (Exception $e)
		{
			log::setPHPError($e);
			$this->page_name = 'Error';
			return '<p class="box-shadow info-message error">' . $e->getMessage() . '</p>';
		}
	}

	public function addCSS($file)
	{
		if (!in_array($file, $this->cssfiles))
			$this->cssfiles[] = $file;
	}

	private function getCSS()
	{
		$css = '';
		foreach ($this->cssfiles as $file)
		{
			$css .= '<link rel="stylesheet" href="' . $file . '">' . "\n";
		}

		return $css;
	}

	public function addJS($file)
	{
		if (!in_array($file, $this->jsfiles))
			$this->jsfiles[] = $file;
	}

	private function getJS()
	{
		$js = '';
		foreach ($this->jsfiles as $file)
		{
			$js .= '<script src="' . $file . '"></script>' . "\n";
		}

		return $js;
	}
}
