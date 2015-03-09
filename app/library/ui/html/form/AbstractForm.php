<?php
/**
 * AiryMVC Framework
 *
 * @category AiryMVC
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 * @author: Hung-Fu Aaron Chang
 */

/**
 * @see framework/app/library/ui/html/UIComponent
 */
require_once dirname(__FILE__) . '/../UIComponent.php';

/**
 * This abstract class handles UI component.
 *
 * @filesource
 * @package framework\app\library\ui\html\form\AbstractForm
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 */
class AbstractForm extends UIComponent{

	/**
	 * @var The HTML form attributes
	 */
    protected $_attributes = array();
    
	/**
	 * @var The elements that the form contains.
	 */
    protected $_elements = array();
    
    /**
     * @var The HTML form text
     */
    protected $_formText;
    
    /**
     * @var The HTML form decoration.
     */
    protected $_formDecoration;
    
    /**
     * Attributes is a key-value structure that stores all the form attribtes
     * @param string $key
     * @param mixed $value
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
     * @return AbstractForm
     */
    public function setElement($element)
    {
        if ($element instanceof HtmlScript) {
            if (is_null($element->getId())) {
                $this->_elements[] =  $element;
            } else {
                $this->_elements[$element->getId()] =  $element;
            }
        } else {
            $this->_elements[$element->getId()] =  $element;
        }
        return $this;
    }
    
    /**
     * @param string $id
     * @return mixed|NULL
     */
    public function getElementById($id)
    {
        foreach ($this->_elements as $key => $element) {
            if ($element instanceof AbstractFormElement || $element instanceof FieldElement) {
                if ($element->getId() == $id) {
                    return $this->_elements[$key];
                }
            }
        }    
        return NULL;
    }
    
    /**
     * @param string $name
     * @return mixed|NULL
     */
    public function getElementByName($name)
    {
        foreach ($this->_elements as $key => $element) {
            if ($element instanceof AbstractFormElement || $element instanceof FieldElement) {
                if ($element->getName() == $name) {
                    return $this->_elements[$key];
                }
            }
        }    
        return null;       
    }   

    /**
     * @param array $formDecoration
     */
    public function setDecoration($formDecoration) {
    	$this->_formDecoration = $formDecoration;
    }
    
    /**
     * @return array
     */
    public function getDecoration() {
    	return $this->_formDecoration;
    }
    
    /**
     * @param array $elements
     */
    public function setElements($elements)
    {
        $this->_elements = $elements;
    }
    
    /**
     * @example
     * Form Decoration:
     * 
     * array(formId      => array('<div class="class_selector">', '</div>'),
     *       elementId1  => array('<div class="elememtClass1">', '</div>'),
     *       elementId2  => array('<div class="elememtClass2">', '</div>'),
     *       ...
     *       {elementId} => array('{open_html}, {close_html})
     *      );
     *      
     * This render the form
     * 
     * @return string
     */
    public function render()
    {
    	$formId = null;
    	$insert = "";
        foreach ($this->_attributes as $key => $value)
        {
            $insert .= sprintf(" %s='%s'", $key, $value);
            if ($key == 'id') {
            	$formId = $value;
            }
        }
        $formOpenText = "<form{$insert}>";
    	
        
        /**
         * Render the form elements here 
         */
        $elementTexts = array();
        $elementHtml = "";
        foreach ($this->_elements as $key => $element)
        {
            $elementTexts[$element->getId()] = $element->render();
            $elementHtml .= $element->render();
        }
        $formCloseText = "</form>";
        
        $openHtml  = $formOpenText;
        $closeHtml = $formCloseText;
        
        //Insert into formDecoration
        if (!is_null($this->_formDecoration)) {
        	 //reset the elementHtml here
        	 $elementHtml = "";
        	 $formPrefix = (isset($this->_formDecoration[$formId]) && isset($this->_formDecoration[$formId][0])) ? $this->_formDecoration[$formId][0] : "";
        	 $formPostfix = (isset($this->_formDecoration[$formId]) && isset($this->_formDecoration[$formId][1])) ? $this->_formDecoration[$formId][1] : "";
        	 $openHtml  = $formPrefix . $formOpenText;
    	     $closeHtml = $formCloseText . $formPostfix;
    	     
    		 //prepare for elements inside the form
    		 foreach ($elementTexts as $elementId => $elementText) {
    		 	      $elementOpenHtml  = (isset($this->_formDecoration[$elementId][0])) ? $this->_formDecoration[$elementId][0] : "";
    		 	      $elementCloseHtml = (isset($this->_formDecoration[$elementId][1])) ? $this->_formDecoration[$elementId][1] : "";
    				  $elementHtml .= $elementOpenHtml 
    			    	           . $elementText
    			        	       . $elementCloseHtml;
    		 }
    	} 
        
        $this->_formText = $openHtml . $elementHtml . $closeHtml;
        
        return $this->_formText;
    }    
}

?>
