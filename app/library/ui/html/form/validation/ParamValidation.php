<?php
/**
 * AiryMVC Framework
 *
 * @category AiryMVC
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 * @author: Hung-Fu Aaron Chang
 */

/**
 * The key-value validation.
 *
 * @filesource
 * @package framework\app\library\ui\html\form\validation\ParamsValidation
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 */
class ParamsValidation {
    
    /**
     * @var array $_fields
     */
    private $_fields;
    
    /**
     * @var array $_respones
     */
    private $_respones;
    
    function __construct() {
        $this->_fields = array();
        $this->_respones = array();
    }
    
    /**
     * Set the validator
     * @param string $name
     * @param object $validator
     */
    public function setValidator($name, $validator) {
        $this->_fields[$name][] = $validator; 
    }
    
    /**
     * Validate the parameters.
     * @param array $params: key-value array as GET or POST results ($_GET or $_POST)
     * @return string: error message 
     */
    public function validate($params) {
        foreach ($params as $key => $value) {
            $vals = $this->_fields[$key];
            foreach ($vals as $validator) {
                $this->_respones[$key][] = $validator->isValid($value);
            }
        }
        return $this->_respones;
    }
}

?>
