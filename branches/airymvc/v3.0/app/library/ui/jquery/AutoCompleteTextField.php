<?php
/**
 * AiryMVC Framework
 *
 * @category AiryMVC
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 * @author: Hung-Fu Aaron Chang
 */
/**
 * The auto complete text field component.
 *
 * @filesource
 * @package framework\app\library\ui\query\AutoCompleteTextField
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 */
class AutoCompleteTextField extends JUIComponent{
    
	/**
	 * 
	 * @var object $_inputElement
	 */
    private $_inputElement;
    
    /**
     * 
     * @var unknown
     */
    private $_selections;

    
    public function __construct($id, $class = null) {
        $this->_id = $id;
        $this->setAttribute('id', $id);
        if (!is_null($class)) {
            $this->setAttribute('class', $class);
        }
        $this->_inputElement = new TextElement($id);
    }
    
    /**
     * 
     * @param string $label_id
     * @param string $label
     * @param string $label_css
     */
    public function setLabel($label_id, $label, $label_css = null)
    {
        $this->_inputElement->setLabel($label_id, $label, $label_css);
    }
    
    /**
     * 
     * @param string                                                                                      $value
     */
    public function setValue($value) 
    {
        $this->_inputElement->setValue($value);   
    }
    
    /**
     * 
     * @param array $selections
     */
    public function setSelections($selections)
    {
        $this->_selections = $selections;
    }
    
    /**
     * @see JUIComponent::render()
     */
    public function render()
    {
        $autoCompleteElement = $this->_inputElement->render(); 
        /**
         * Add Javascript support, follow the Jquery Tool Tabs
         */
        $autoCompleteElement = $autoCompleteElement . $this->appendJs($this->_id);
        $this->_elementText = $autoCompleteElement;
		$this->attachJs();
        return $this->_elementText;
    }
    
    /**
     * 
     * @param string $id
     * @return string
     */
    protected function appendJs($id){
    	$sourceStr = "var availableSelections = [";
    	$cn = 0;
    	foreach ($this->_selections as $word) {
    		if ($cn != count($this->_selections) - 1) {
    			$sourceStr .= "\"{$word}\",";
    		} else {
    			$sourceStr .= "\"{$word}\"";
    		}
    		$cn++;
    	}
        $sourceStr .= "];"; 
        $format = "<script type='text/javascript'> $(function(){ %s \$('input#%s').autocomplete({ source: availableSelections }); }); </script>";

        return sprintf($format, $sourceStr, $id);
    }
    
}

?>