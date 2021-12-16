<?php

namespace Elbion;

/** The base engine */
class Engine {

	/** extension of template files (default .elb) */
	protected $extension;

	/** view folder of template files */
	protected $viewDir;

	/** current view file */
	protected $view;

	/** name of view file */
	protected $viewName;

	/** cache files of compiled files */
	protected $cacheDir;

	public function __construct()
	{
		$this->extension = ".elb";
		$this->viewDir = "views/";
		$this->cacheDir = "cache/";
	}

	public function view($filename)
	{
		$this->viewName = $filename;
		$file = file_get_contents($this->viewDir . $filename . $this->extension);
		$this->view = $file;
		$this->parse();
		ob_start();
		include_once $this->cacheDir . $this->viewName;
		return ob_get_clean();
	}

	public function setViewDir($viewFolder = "views/")
	{
		$this->viewDir = $viewFolder;
		return true;
	}

	public function setCacheDir($cacheFolder = "cache/")
	{
		$this->cacheDir = $cacheFolder;
		return true;
	}

	protected function parse(): void 
	{
		$this->parseVariables();
		$this->parseIf();
		$this->parseEcho();
		$this->save();
	}

	protected function parseVariables(): void
	{
		$this->view = preg_replace("/var ([A-Za-z-_]+)(\s+)=(\s+)(.*)/", "<?php \$$1 = $4; ?>", $this->view);
	}

	protected function parseIf(): void
	{
		$this->view = preg_replace("/if\((.*)\)/", "<?php if($1): ?>", $this->view);
		$this->view = preg_replace("/else/", "<?php else: ?>", $this->view);
		$this->view = preg_replace("/endif/", "<?php endif; ?>", $this->view);
	}

	protected function parseEcho(): void
	{
		$this->view = preg_replace("/elb\.echo\(\"(.*)\"\)/", "<?php echo \"$1\"; ?>", $this->view);
		$this->view = preg_replace("/elb\.echo\((.*)\)/", "<?php echo \$$1; ?>", $this->view);
	}

	protected function save(): void
	{
		if(!is_dir($this->cacheDir))
		{
			mkdir($this->cacheDir);
		}
		$filename = $this->generateRandomFileName();
		$file = fopen($this->cacheDir . $filename, "w");
		fwrite($file, $this->view);
		fclose($file);
		$this->viewName = $filename;
	}

	protected function generateRandomFileName(): string 
	{
		$chars = "qwertyuiopasdfghjklmnbvcxzQWERTYUIOPASDFGHJKLMNBVCXZ";
		$newFile = "";
		for($i = 0; $i < 64; $i++)
		{
			$rand = rand(0, strlen($chars) - 1);
			$newFile .= substr($chars, $rand, $rand + 1);
		}
		return md5($newFile) . "." . $this->viewName . $this->extension . ".php";
	}

}