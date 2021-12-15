<?php

namespace Elbion;

/** The base engine */
class Engine {

	/** extension of template files (default .elb) */
	protected $extension;

	/** view folder of template files */
	protected $viewDir;

	/** cache files of compiled files */
	protected $cacheDir;

	public function loadFile($filename)
	{
		ob_start();
		require_once $this->viewDir . $filename . $this->extension;
		$file = ob_get_clean();
		echo($file);
		return true;
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

	public function setExt($ext = ".elb")
	{
		$this->extension = $ext;
		return true;
	}

}