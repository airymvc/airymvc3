<?php
/**
 * AiryMVC Framework
 *
 * @category AiryMVC
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 * @author: Hung-Fu Aaron Chang
 */
/**
 * The abstract class handles HTML container type elements.
 *
 * @filesource
 * @package framework\app\library\ui\html\component\DivElement
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 */
class DivElement extends AbstractFormElement{

	/**
	 * @var string $_innerHtml
	 */
    protected $_innerHtml;
    
    /**
     * @var string $_id
     */
    private $_id;
    
    public function __construct($id)
    {
        $this->setId($id);
    }
    
    /**
     * @param string $innerHtml
     */
    public function setHtmlValue($innerHtml)
    {
        $this->_innerHtml = $innerHtml;
    }
    
    /**
     * @return string
     */
    public function getHtmlValue() 
    {
        return $this->_innerHtml;	
    }
    
    /**
     * Override the method
     * @see AbstractFormElement::renderElements()
     */
    protected function renderElements()
    {
    	$insert = "";
        foreach ($this->_attributes as $key => $value)
        {
            $insert = sprintf(" %s='%s'", $key, $value);
        }
        $inputText = "<div{$insert}>";
        
        $this->_elementText = $inputText . $this->_innerHtml . '</div>';
        return $this->_elementText;
    }
    
}

