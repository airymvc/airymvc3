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
 * @package framework\app\library\ui\html\container\AbstractContainer
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 */
abstract class AbstractContainer extends UIComponent{
    //put your code here
    protected $_attributes = array();
    protected $_elements = array();

    /**
     * Attributes is a key-value structure that stores all the form attribtes 
     * @param string $key
     * @param string $value
     */
    public function setAttribute($key, $value)
    {
        $this->_attributes[$key] =  $value;
    }
    
    /**
     * @param array $attributes
     */
    public function setAttributes($attributes)
    {
        $this->_attributes = $attributes;
    }
    
    /**
     * @param object $element
     * @param string $key Default value = NULL
     */
    public function setElement($element, $key = NULL)
    {
        $key = is_null($key)? count($this->_elements) : $key;
        $this->_elements[$key] =  $element;
    }
    
    /**
     * @param array $elements
     */
    public function setElements($elements)
    {
        $this->_elements = $elements;
    }
    
    /**
     * Get array of HTML UI elements
     * @return array
     */
    public function getElements()
    {
        return $this->_elements;
    }
    
    /**
     * Get the HTML UI element
     * @param string $key
     * @return object
     */
    public function getElement($key)
    {
        return $this->_elements[$key] =  $element;
    }
    
    
}

?>
