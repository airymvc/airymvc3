<?php
/**
 * AiryMVC Framework
 *
 * @category AiryMVC
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 * @author: Hung-Fu Aaron Chang
 */
/**
 * All the html doctypes.
 *
 * @package framework\app\library\view\Doctype
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 */
class Doctype {
	
	/**
	 * DocType constants
     */
    const XHTML11             = 'XHTML11';
    const XHTML1_STRICT       = 'XHTML1_STRICT';
    const XHTML1_TRANSITIONAL = 'XHTML1_TRANSITIONAL';
    const XHTML1_FRAMESET     = 'XHTML1_FRAMESET';
    const XHTML1_RDFA         = 'XHTML1_RDFA';
    const XHTML1_RDFA11       = 'XHTML1_RDFA11';
    const XHTML_BASIC1        = 'XHTML_BASIC1';
    const XHTML5              = 'XHTML5';
    const HTML4_STRICT        = 'HTML4_STRICT';
    const HTML4_LOOSE         = 'HTML4_LOOSE';
    const HTML4_FRAMESET      = 'HTML4_FRAMESET';
    const HTML5               = 'HTML5';
    
    /**
     * Array of doctypes.
     * @var array $_doctypes
     */
    private $_doctypes = array();
    
	
    public function __construct() {
    	
    	 $this->_doctypes = array(self::XHTML11             => '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">',
                    			  self::XHTML1_STRICT       => '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">',
                    			  self::XHTML1_TRANSITIONAL => '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">',
                    			  self::XHTML1_FRAMESET     => '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Frameset//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd">',
                    		      self::XHTML1_RDFA         => '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML+RDFa 1.0//EN" "http://www.w3.org/MarkUp/DTD/xhtml-rdfa-1.dtd">',
                    		      self::XHTML1_RDFA11       => '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML+RDFa 1.1//EN" "http://www.w3.org/MarkUp/DTD/xhtml-rdfa-2.dtd">',
                    			  self::XHTML_BASIC1        => '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML Basic 1.0//EN" "http://www.w3.org/TR/xhtml-basic/xhtml-basic10.dtd">',
                    		      self::XHTML5              => '<!DOCTYPE html>',
                    			  self::HTML4_STRICT        => '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">',
                    			  self::HTML4_LOOSE         => '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">',
                    			  self::HTML4_FRAMESET      => '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd">',
                    			  self::HTML5               => '<!DOCTYPE html>'
                    		);
                
    }
    /**
     * Set the doctype.
     * @param string $newDocTypeKey
     * @param string $newDocType
     */
    public function setDoctype($newDocTypeKey, $newDocType) {
    	 $this->_doctypes[$newDocTypeKey] = $newDocType;
    }
    /**
     * Get the doctype
     * @param string $newDocTypeKey
     * @return string
     */
    public function getDoctype($newDocTypeKey = NULL) {
    	 $newDocTypeKey = is_null($newDocTypeKey) ? (self::HTML4_LOOSE) : $newDocTypeKey;
    	 return $this->_doctypes[$newDocTypeKey];
    }
	
}