<?php
/**
 * AiryMVC Framework
 * 
 * @category AiryMVC
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 * @author: Hung-Fu Aaron Chang
 */

/**
 * This is the controller class that is used for intializing the instance and set variables.
 *
 * @package framework\app\AppController
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 */
class AppController extends AbstractController
{
	   /**
	    * This method sets a acl xml - the default is "acl.xml" in config folder.
	    *
	    * @param array $params url parameters
	    * @param array $viewVariables default = null, the view related variables
	    *
	    * @return void
	    */
        public function initial($params, $viewVariables = null)
        {
            parent::initial($params, $viewVariables);

            
        }

}
?>
