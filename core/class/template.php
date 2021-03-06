<?php
class template
{
	private $templatePath;
	private $ifVars = array();
	private $vars = array();
	private $phpvars = array();

	public function __construct($templatePath=null, $phpvars=array())
	{
		$this->templatePath	= $templatePath;
		$this->phpvars		= $phpvars;
	}

	public function render($templatePath=null)
	{
		global $app, $XenuxDB;

		if (isset($templatePath))
			$this->templatePath	= $templatePath;

		foreach ($this->phpvars as $key => $value) {
			$$key = $value;
		}

		$user = $app->user;

		ob_start();
			if (file_exists($this->templatePath))
			{
				include($this->templatePath);
			}
			else
			{
				throw new Exception("<b>Error:</b> Template <i>\"{$this->templatePath}\"</i> not found ...");

				return false;
			}
		$content = ob_get_clean();

		$content = $this->replaceComments($content);
		$content = $this->replaceIfs($content);
		$content = $this->replaceVars($content);

		return $content;
	}

	private function setDefaultVars()
	{
		$this->setVar('MAIN_URL', MAIN_URL);
		$this->setVar('ADMIN_URL', ADMIN_URL);
		$this->setVar('MAIN_PATH', MAIN_PATH);
		$this->setVar('ADMIN_PATH', ADMIN_PATH);
		$this->setVar('REQUEST_URL', REQUEST_URL);
		$this->setVar('XENUX_VERSION', XENUX_VERSION);
	}

	public function setVar($name, $value)
	{
		$this->vars[$name] = $value;
	}

	public function getVar($name)
	{
		return isset($this->vars[$name]) ? $this->vars[$name] : false;
	}

	public function issetVar($name)
	{
		return isset($this->vars[$name]);
	}

	public function setIfCondition($name=null, $condition=false)
	{
		$this->ifVars[$name] = $condition;
	}

	private function replaceVars($content)
	{
		$this->setDefaultVars();

		foreach ($this->vars as $var_name => $var_value) {
			$content = str_replace("{{".$var_name."}}", $var_value, $content);
		}

		return $content;
	}

	/**
	* comments like: {# this is a comment #}
	*/
	private function replaceComments($content)
	{
		return preg_replace('/\{\#(.*?)\#\}/s', '', $content);
	}

	/**
	* if's like:
	* #if(condition):
	* <p>than</p>
	* #endif
	*/
	#TODO: allow nested ifs
	private function replaceIfs($content)
	{
	#	var_dump($this->ifVars);

		$content = preg_replace_callback('/#if\(([A-Za-z0-9_\-]*)\)\:(.*?)(?:#else\:(.*?))?#endif/is', function($match)
		{
			$match[3] = isset($match[3]) ? $match[3] : null;

			list($all, $conditionName, $than, $else) = $match;

		#	var_dump($match);

			if (isset($this->ifVars[$conditionName]) && $this->ifVars[$conditionName] == true)
			{
				return $than;
			}
			else
			{
				return $else;
			}

		}, $content);

		return $content;
	}
}
