<?php
/**
 * AiryMVC Framework
 *
 * @category AiryMVC
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 * @author: Hung-Fu Aaron Chang
 */

/**
 * @see framework\app\library\ui\html\component\AbstractFormElement
 */
require_once 'AbstractFormElement.php';

/**
 * The fieldset html UI component
 *
 * @filesource
 * @package framework\app\library\ui\html\component\AbstractFormElement
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 */
class RadioElement extends AbstractFormElement{
    
	/**
	 * @var string $_text
	 */
	protected $_text;
            
    public function __construct($id)
    {
        $this->setId($id);
        $this->setAttribute("type", InputType::RADIO);
    }

    /**
     * Same as setText method for consistency (TextElement, TextAreaElement)
     * @param string $text
     */
    public function setLabel($text)
    {
    	$this->setText($text);
    }
    
    /**
     * @param string $text
     */
    public function setText($text)
    {
        $this->_text    = $text;
    }
    
    /**
     * @see AbstractFormElement::render()
     */
    public function render()
    {
        $insert = "";
        foreach ($this->_attributes as $key => $value)
        {
            $insert .= sprintf(" %s='%s'", $key, $value);
        }
        $inputText = "<input{$insert}>" . $this->_text;
        $this->_elementText = $inputText;
        
        return $this->_elementText;
    }
}

?>
