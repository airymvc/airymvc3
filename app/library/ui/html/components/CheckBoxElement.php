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
 * The button element
 *
 * @filesource
 * @package framework\app\library\ui\html\component\CheckBoxElement
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 */
class CheckBoxElement extends AbstractFormElement{

	/**
	 * @var string $_text
	 */
	protected $_text;
	
    public function __construct($id) {
        $this->setId($id);
        $this->setAttribute("type", InputType::CHECKBOX);
    }

    /**
     * @param string $text
     * @return string
     */
    public function setText($text) {
        $this->_text    = $text;
    }
    
    /**
     * @param string $text
     * @return string
     */
    public function getText($text) {
       return  $this->_text;
    }
    
    /**
     * @see AbstractFormElement::renderElements()
     */
    protected function renderElements() {
        $insert = "";
        foreach ($this->_attributes as $key => $value)
        {
            $insert .= sprintf(" %s='%s'", $key, $value);
        }
        $inputText = "<input{$insert}>". $this->_text;
        $this->_elementText = $inputText;      
    }
    
}

?>
