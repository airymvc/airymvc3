<?php
/**
 * AiryMVC Framework
 *
 * @category AiryMVC
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 * @author: Hung-Fu Aaron Chang
 */
/**
 * The date picker component.
 *
 * @filesource
 * @package framework\app\library\ui\jquery\Dialog
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 */
class Dialog extends JUIComponent{
    
	/**
	 * 
	 * @var string $_title
	 */
	private $_title;
	
	/**
	 * 
	 * @var string $_message
	 */
	private $_message;
	
    /**
     * 
     * @param string $id
     * @param string $class
     */
    public function __construct($id, $class = null) {
        $this->_id = $id;
        $this->setAttribute('id', $id);
        if (!is_null($class)) {
            $this->setAttribute('class', $class);
        }
    }
    
    /**
     * 
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->_title = $title;
        $this->setAttribute('title', $title);
    }
    
    /**
     * 
     * @param string $message
     */
    public function setMessage($message) 
    {
        $this->_message= $message;   
    }
    
    
    /**
     * @see JUIComponent::render()
     */
    public function render()
    {
        $dialogDiv = $this->composeDiv(); 
        $dialogText = $dialogDiv . $this->appendJs($this->_id);
        $this->_elementText = $dialogText;
        $this->attachJs();
        return $this->_elementText;
    }
    
    /**
     * Make a div element
     */
    protected function composeDiv() {
    	$keyValue = "";
    	foreach ($this->_attributes as $key => $value) {
    		$keyValue .= " {$key}=\"{$value}\"";
    	}
      
    	$divText = '<div {$keyValue}>'
    	         . $this->_message
    	         . '</div>';
    }
    
    /**
     * 
     * @param string $id
     * @return string
     */
    protected function appendJs($id){  
    	   
        $format = "<script  type='text/javascript'> $(function(){ \$('#%s').dialog(); }); </script>";
        return sprintf($format, $id);
    }
    
}


?>
