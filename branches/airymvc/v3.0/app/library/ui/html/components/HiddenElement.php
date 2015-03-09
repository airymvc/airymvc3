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
 * The input hidden field
 *
 * @filesource
 * @package framework\app\library\ui\html\component\HiddenElement
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 */
class HiddenElement extends FieldElement{

	/**
	 * @see framework\app\library\ui\html\component\InputType
	 * @var string $_type
	 */
    protected $_type = InputType::HIDDEN;
    
    public function __construct($id)
    {
        $this->setId($id);
        $this->setAttribute("type", InputType::HIDDEN);
    }
}


?>
