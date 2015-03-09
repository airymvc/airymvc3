<?php
/**
 * AiryMVC Framework
 *
 * @category AiryMVC
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 * @author: Hung-Fu Aaron Chang
 */

/**
 * This class stores all the constant values about acl.xml.
 *
 * @package framework\core\AclXmlConstant
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 */
class AclXmlConstant {
	
	const ALL_CONTROLLERS = "ALL_CONTROLLERS";
    const ALL_ACTIONS = "ALL_ACTIONS";
    
	const ACL_ACCESS_CONTROL_EXCLUSION = 'access_control_exclusion';
    const ACL_SUCCESSFUL_DISPATCH = 'successful_dispatch';
    const ACL_AUTHENTICATION = 'authentication';
    const ACL_MAPPING_TABLE  = 'mapping_table';    
    const ACL_ENCRYTION_METHOD = 'encrytion_method';
    const ACL_USE_PWD_ENCRYTION = 'use_pwd_encryption';
    const ACL_MAPPING_DB_ID = 'mapping_database_id';
    const ACL_ACCESS_RULES_AFTER_AUTHENTICATION = 'access_rules_after_authentication';
    const ACL_MODULE_TABLE_MAPPING = 'module_table_mapping';  
    const ACL_REFERRING_MAPPING_ID = 'ref_map_id';
    const ACL_MAPPING_FIELDS = 'mapping_fields';    
	const ACL_ROLE_SET = 'role_set';
    const ACL_REFERRING_ROLE = 'ref_role';
    const ACL_ALLOW = 'allow';
    const ACL_RULE = 'rule';
    const ACL_ENCRYPTION_OPTION = 'encrytion_option';
    
    //login related actions
    const ACL_OTHER_EXCLUSIVE_ACTIONS = 'other_exclusive_actions';
    //four default login action's XML tags
    const ACL_SIGN_IN_ACTION = 'sign_in_action';
    const ACL_LOGIN_ACTION   = 'login_action';
    const ACL_LOGIN_ERROR_ACTION      = 'login_error_action';
    const ACL_LOGOUT_ACTION  = 'logout_action';
    
    //Common Tags
    const MODULE = 'module';
    const NAME = 'name';
    const CONTROLLER = 'controller';
    const ACTION = 'action';

    

}

?>
