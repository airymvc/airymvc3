<?php
/**
 * AiryMVC Framework
 *
 * @category AiryMVC
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 * @author: Hung-Fu Aaron Chang
 */
/**
 * The fieldset html UI component
 *
 * @filesource
 * @package framework\app\library\ui\html\component\AbstractFormElement
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 */
class AbstractFormElement extends UIComponent{
    
    /**
     * @var array $_attributes
     */
    protected $_attributes = array();

    /**
     * @var string $_elementText
     */
    protected $_elementText;
    
    /**
     * @var array $_decoration
     */
    protected $_decoration = null;
   
    /**
     * @param string $id
     */
    public function setId($id)
    {
        $this->_attributes['id'] = $id;
    }
    
    /**
     * @return string
     */
    public function getId()
    {
        return $this->_attributes['id'];
    }
    
    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->_attributes['name'] = $name;
    }
    
    /**
     * @return string
     */
    public function getName()
    {
        return $this->_attributes['name'];
    }
    
    /**
     * @param string $value
     */
    public function setValue($value)
    {
        $this->_attributes['value'] = $value;
    }
    
    /**
     * @param array $decoration
     */
    public function setDecoration($decoration) {
    	$this->_decoration = $decoration;
    }

    /**
     * @return array
     */
    public function getDecoration() {
    	return $this->_decoration;
    }    

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
     * @example
     * Element Decoration:
     * array('{elementId}' => array('{openHtml}', '{closeHtml}'))
     * 
     * Example of Element Decoration:
     * array('elementId'   => array('<div class="class1">', '<div>'))
     * 
     * @return string
     */
    protected function renderElements()
    {   
        $insert = "";
        foreach ($this->_attributes as $key => $value)
        {
            $insert .= sprintf(" %s='%s'", $key, $value);
        }
        $inputText = "<input{$insert}>";
        $openHtml  = "";
        $closeHtml = "";
        if (!is_null($this->_decoration)) {
        	$decoration = $this->_decoration[$this->getId()];
        	$openHtml  = $decoration[0];
        	$closeHtml = $decoration[1];
        }
        $this->_elementText = $openHtml . $inputText . $closeHtml;
        return $this->_elementText; 
    }
    
    /**
     * @see UIComponent::render()
     */
    public function render()
    {
        $this->renderElements();
        return $this->_elementText;
    }
}

?>