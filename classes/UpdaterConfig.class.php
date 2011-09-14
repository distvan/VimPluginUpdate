<?
class UpdaterConfig extends Config
{
		/**
		 * __construct 
		 * 
		 * @access public
		 * @return void
		 */
		public function __construct()
		{
			parent::__construct();
		}

		/**
		 * createDefaultConfig 
		 * 
		 * @access protected
		 * @return void
		 */
		protected function createDefaultConfig()
		{
			$root = $this->_xml->createElement(self::ROOT_NAME);
			$rootNode = $this->_xml->appendChild($root);
			$this->_xml->save($this->_configFile);
		}

		/**
		 * listConfig 
		 * 
		 * @access public
		 * @return void
		 */
		public function listConfig()
		{
			$nodeList = $this->_xpath->query("//".Config::CONFIG_ITEM);
			if(count($nodeList) > 0)
			{
				foreach($nodeList as $node)
				{
					echo $node->nodeValue."\n";
				}
			}
		}

		/**
		 * RemoveExtension 
		 * 
		 * @param mixed $strName 
		 * @access protected
		 * @return String filename without extension
		 */
		protected function removeExtension($strName)  
		{  
			$ext = strrchr($strName, '.');
			if($ext !== false)
			{
				$strName = substr($strName, 0, -strlen($ext));
			}
			return $strName;
		}  

		/**
		 * getFileName from url path
		 * without extension
		 * 
		 * @param mixed $url 
		 * @access public
		 * @return Sting filename
		 */
		public function getFileName($url)
		{
			$parsed_url = parse_url($url);
			$path = $parsed_url['path'];
			$filename = basename($path);
			return $this->removeExtension($filename);
		}
}
?>
