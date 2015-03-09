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
 * @package framework\app\library\ui\html\component\AbstractFormElement
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 */
class ButtonElement extends AbstractFormElement{

	/**
	 * @see framework\app\library\ui\html\component\InputType
	 * @var string $_type
	 */
    protected $_type  = InputType::BUTTON;
    
    public function __construct($id, $label = null)
    {
        $this->setId($id);
        $this->setAttribute("type", InputType::BUTTON);
        if (!is_null($label)) {
            $this->setAttribute("value", $label);
        }
    }
}

?>
