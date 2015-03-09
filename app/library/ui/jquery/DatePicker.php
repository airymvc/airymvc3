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
 * @package framework\app\library\ui\jquery\DatePicker
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 */
class DatePicker extends JUIComponent{
    
    private $_inputElement;
    private $_number        = 1;
    private $_isChangeMonth = "false";
    private $_isChangeYear  = "false";
    private $_dateFormat    = "yyyy-mm-dd";
    
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
     * @param string $value
     */
    public function setValue($value) 
    {
        $this->_inputElement->setValue($value);   
    }
    
    /**
     * 
     * @param int $number
     */
    public function setNumberOfMonth($number)
    {
        $this->_number = $number;
    }
    
    /**
     * 
     * @param boolean $changeMonth
     */
    public function setIsChangeMonth($changeMonth) 
    {
        if ($changeMonth == true || $changeMonth == "true") {
            $this->_isChangeMonth = "true";
        }
    }
    
    /**
     * 
     * @param boolean $changeYear
     */
    public function setIsChangeYear($changeYear) 
    {
        if ($changeYear == true || $changeYear == "true") {
            $this->_isChangeYear = "true";
        }
    }
    
    /**
     * 
     * @param string $dateFormat
     */
    public function setDateFormt($dateFormat)
    {
        $this->_dateFormat = $dateFormat;
    }
    
    /**
     * @see JUIComponent::render()
     */
    public function render()
    {
        $datePickerText = $this->_inputElement->render(); 
        /**
         * Add Javascript support, follow the Jquery Tool Tabs
         */
        $datePickerText = $datePickerText . $this->appendJs($this->_id);
        $this->_elementText = $datePickerText;
		$this->attachJs();
        return $this->_elementText;
    }
    
    /**
     * 
     * @param string $id
     * @return string
     */
    protected function appendJs($id){
        
        $options = ""; //sprintf("dateFormat: '%s'", $this->_dateFormat);
        if ($this->_isChangeMonth) {
            $options .= sprintf("changeMonth: %s", $this->_isChangeMonth);
        }
        if ($this->_isChangeYear) {
            $options .= sprintf(", changeYear: %s", $this->_isChangeYear);
        }
        
        $format = "<script  type='text/javascript'> $(function(){ \$('input#%s').datepicker({ %s }); }); </script>";

        return sprintf($format, $id, $options);
    }
    
}


?>
