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
 * The field element
 *
 * @filesource
 * @package framework\app\library\ui\html\component\FieldElement
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 */
class FieldElement extends AbstractFormElement{

	/**
	 * @var string $_label
	 */
    protected $_label;
    
    /**
     * @var string $_label_id
     */
    protected $_label_id;
    
    /**
     * @var string $_label_css
     */
    protected $_label_css;
    
    /**
     * Set the label of the form element
     * @param string $label_id
     * @param string $label
     * @param string $label_css
     */
    public function setLabel($label_id, $label, $label_css = null)
    {
        $this->_label     = $label;
        $this->_label_id  = $label_id;
        $this->_label_css = $label_css;
    }
    
    /*
     * @return string
     */
    protected function renderElements()
    {
        $insert = "";
        foreach ($this->_attributes as $key => $value)
        {
            $insert .= sprintf(" %s='%s'", $key, $value);
        }
        $inputText = "<div id='{$this->_label_id}' class='{$this->_label_css}'>{$this->_label}</div><input{$insert}>";
        $this->_elementText = $inputText;      
    }
    
}

?>
