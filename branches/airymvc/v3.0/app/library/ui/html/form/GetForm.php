<?php
/**
 * AiryMVC Framework
 *
 * @category AiryMVC
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 * @author: Hung-Fu Aaron Chang
 */

/**
 * @see framework/app/library/ui/html/form/AbstractForm
 */
require_once ('AbstractForm.php');

/**
 * This abstract class handles UI component.
 *
 * @filesource
 * @package framework\app\library\ui\html\form\GetForm
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 */
class GetForm extends AbstractForm{
    //put your code here
        public function __construct($id) {
        $this->setAttribute('id', $id);
        $this->setAttribute('method', 'get');
    }
}

?>
