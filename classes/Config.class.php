<?
abstract class Config
{

	const CONFIG_FILE = "config.xml";
	const ROOT_NAME = "root";
	const CONFIG_ITEM = "config";

	protected $_xml;
	protected $_configFile;
	protected $_xpath;
	protected $_rootName;

	/**
	 * __construct 
	 * 
	 * @access public
	 * @return void
	 */
	public function __construct()
	{
		$this->_configFile = dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . self::CONFIG_FILE;
		$this->init();
	}

	/**
	 * init 
	 * 
	 * @access protected
	 * @return void
	 */
	protected function init()
	{
		$this->_xml = new DomDocument();
		if(!file_exists($this->_configFile))
		{
			$this->createDefaultConfig();
		}
		$this->_xml->load($this->_configFile);
		$this->_xpath = new DOMXpath($this->_xml);
	}
	/**
	 * getConfigVar 
	 * 
	 * @param mixed $id 
	 * @access public
	 * @return string
	 */
	public function getConfigVar($id)
	{
		return $this->_xpath->query("//*[@id='$id']")->item(0)->nodeValue;
	}


	/**
	 * createConfigItem 
	 * 
	 * @param mixed $id 
	 * @param mixed $value 
	 * @access public
	 * @return void
	 */
	public function createConfigItem($id, $value)
	{
		if($this->getConfigVar($id) == NULL)
		{
			$rootNode = $this->_xml->documentElement;
			$sub = $this->_xml->createElement(self::CONFIG_ITEM);
			$attr = $this->_xml->createAttribute("id");
			$attrVal = $this->_xml->createTextNode($id);
			$attr->appendChild($attrVal);
			$sub->appendChild($attr);
			$subNode = $rootNode->appendChild($sub);
			$textNode = $this->_xml->createTextNode($value);
			$subNode->appendChild($textNode);
		}	
	}

	/**
	 * removeConfigItem 
	 * 
	 * @param mixed $id 
	 * @access public
	 * @return void
	 */
	public function removeConfigItem($id)
	{
		$xml = $this->_xml->documentElement;
		$item = $this->_xpath->query("//*[@id='$id']")->item(0);
		if($item != NULL)
		{
			$xml->removeChild($item);
		}	
	}

	/**
	 * setConfigVar 
	 * 
	 * @param mixed $id 
	 * @param mixed $value 
	 * @access public
	 * @return void
	 */
	public function setConfigVar($id, $value)
	{
		$this->_xpath->query("//*[@id='$id']")->item(0)->nodeValue = $value;
	}

	/**
	 * save 
	 * 
	 * @access public
	 * @return void
	 */
	public function save()
	{
		$this->_xml->save($this->_configFile);
	}

	/**
	 * getXPath 
	 * 
	 * @access public
	 * @return XPath
	 */
	public function getXPath()
	{
		return $this->_xpath;
	}
	/**
	 * createDefaultConfig 
	 * 
	 * @abstract
	 * @access protected
	 * @return void
	 */
	abstract protected function createDefaultConfig();
}
?>
