<?php
/**
 * AiryMVC Framework
 *
 * @category AiryMVC
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 * @author: Hung-Fu Aaron Chang
 */

/**
 * @see framework\app\library\ui\html\component\FieldElement
 */
require_once 'FieldElement.php';

/**
 * @see framework\app\library\ui\html\component\InputType
 */
require_once 'InputType.php';

/**
 * The text area
 *
 * @filesource
 * @package framework\app\library\ui\html\component\TextElement
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 */
class TextAreaElement extends FieldElement{
    
	/**
	 * @see framework\app\library\ui\html\component\InputType
	 * @var string $_type
	 */
    protected $_type  = InputType::TEXTAREA;
    
    public function __construct($id) {
        $this->setId($id);
    }
    
    /**
     * @see FieldElement::renderElements()
     */
    protected function renderElements() {
        $insert = "";
        foreach ($this->_attributes as $key => $value)
        {   
            if ($key != "value"){
                $insert .= sprintf(" %s='%s'", $key, $value);
            }
        }
        $textValue = isset($this->_attributes['value'])? $this->_attributes['value'] : "";
        
        $inputText = "<div id='{$this->_label_id}' class='{$this->_label_css}'>{$this->_label}</div><textarea {$insert}>{$textValue}</textarea>";
        $this->_elementText = $inputText;     
    }
    
}

?>
