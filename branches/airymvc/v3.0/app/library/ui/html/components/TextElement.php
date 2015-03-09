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
 * The input text field
 *
 * @filesource
 * @package framework\app\library\ui\html\component\TextElement
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 */
class TextElement extends FieldElement{
    
	/**
	 * @see framework\app\library\ui\html\component\InputType
	 * @var string $_type
	 */
    protected $_type  = InputType::TEXT;
    
    public function __construct($id)
    {
        $this->setId($id);
        $this->setAttribute("type", $this->_type);
    }
}

?>
