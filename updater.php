#!/usr/bin/php
<?
include('classes/autoload.php');

/**
 * Updater PHP CLI Class
 * Update folders from different kind of git resource
 *
 * @author Istvan Dobrentei <info@dobrenteiistvan.hu> 
 */
class Updater
{
	private $_args;
	private $_cfg;
	private $_pluginPath;

	/**
	 * __construct 
	 * 
	 * @access public
	 * @return void
	 */
	public function __construct()
	{
		$this->_pluginPath = getenv("HOME")."/.vim/bundle/";
		$this->_cfg = new UpdaterConfig();
		$this->_args = $_SERVER['argv'];
		$cmd = $_SERVER['argv'][1];
		$this->$cmd();
	}

	/**
	 * remove Config value
	 * 
	 * @access protected
	 * @return void
	 */
	protected function remove()
	{
		$id = $this->_args[2];
		$this->_cfg->removeConfigItem($id);
		$this->_cfg->save();
	}
	
	/**
	 * add Config value
	 * 
	 * @access protected
	 * @return void
	 */
	protected function add()
	{
		$id = $this->_args[2];
		$val = $this->_args[3];
		$this->_cfg->createConfigItem($id, $val);
		$this->_cfg->save();
	}

	/**
	 * list Config
	 * 
	 * @access protected
	 * @return void
	 */
	protected function listconfig()
	{
		$this->_cfg->listConfig();
	}
	/**
	 * update process
	 * 
	 * @access protected
	 * @return void
	 */
	protected function update()
	{
		$submodule = $this->_args[2];
		$url = $this->_cfg->getConfigVar($submodule);
		$fileName = $this->_cfg->getFileName($url);
		chdir($this->_pluginPath . $fileName);
		exec("git pull");
	}

	/**
	 * updateall 
	 * 
	 * @access protected
	 * @return void
	 */
	protected function updateall()
	{
		$xpath = $this->_cfg->getXPath();
		$items = $xpath->query("//".Config::CONFIG_ITEM);
		if(count($items) > 0)
		{
			foreach($items as $item)
			{
				$this->_args[2] = $item->attributes->getNamedItem("id")->nodeValue;
				$this->update();
			}
		}
	}

	/**
	 * get 
	 * 
	 * @param mixed $id 
	 * @access protected
	 * @return void
	 */
	protected function get()
	{
		$id = $this->_args[2];
		$url = $this->_cfg->getConfigVar($id);
		chdir($this->_pluginPath);
		$cmd = "git clone " . $url;
		exec($cmd);
	}

	/**
	 * getall 
	 * 
	 * @access protected
	 * @return void
	 */
	protected function getall()
	{
		$xpath = $this->_cfg->getXPath();
		$items = $xpath->query("//".Config::CONFIG_ITEM);
		if(count($items) > 0)
		{
			foreach($items as $item)
			{
				$url = $item->nodeValue;
				$id = $item->attributes->getNamedItem("id")->nodeValue;
				$cmd = "git clone " . $url;
				exec($cmd);
			}
		}
	}
}

$u = new Updater();
?>
