<?
function __autoload($classname) 
{ 
   include_once($classname . ".class.php"); 
}
spl_autoload_register('__autoload');
?>
