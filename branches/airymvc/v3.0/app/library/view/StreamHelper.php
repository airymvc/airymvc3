<?php
/**
 * AiryMVC Framework
 *
 * @category AiryMVC
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 * @author: Hung-Fu Aaron Chang
 */
/**
 * The stream helper is to write out (render) the view using stream
 *
 * @package framework\app\library\view\StreamHelper
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 */
Class StreamHelper {
    /**
     * current stream position
     * 
     * @var int 
     */
    protected $_position = 0;
        
    /**
     * streaming statistics
     * 
     * @var array 
     */
    private $_stat;
    
    private $_viewVariableName;
    
    function stream_open($path, $mode, $options, &$opened_path)
    {
        /**
         *  ex: $path = "xyz://abc" 
         *  host = abc
         */
        $url = parse_url($path);
        $this->_viewVariableName = $url["host"];
        $this->_position = 0;

        return true;
    }

    function stream_read($count)
    {
        $ret = substr($GLOBALS[$this->_viewVariableName], $this->_position, $count);
        $this->_position += strlen($ret);
        
        return $ret;
    }

    function stream_write($data)
    {
        //initialize the element in the array
        if (!isset($GLOBALS[$this->_viewVariableName])) {
            $GLOBALS[$this->_viewVariableName] = "";
        }
        
        $left  = substr($GLOBALS[$this->_viewVariableName], 0, $this->_position);
        $right = substr($GLOBALS[$this->_viewVariableName], $this->_position + strlen($data));
        
        $GLOBALS[$this->_viewVariableName] = $left . $data . $right;
        $this->_position += strlen($data);
        
        return strlen($data);
    }

    function stream_tell()
    {
        return $this->_position;
    }

    function stream_eof()
    {
        return $this->_position >= strlen($GLOBALS[$this->_viewVariableName]);
    }

    function stream_seek($offset, $whence)
    {
        switch ($whence) {
            case SEEK_SET:
                if ($offset < strlen($GLOBALS[$this->_viewVariableName]) && $offset >= 0) {
                     $this->_position = $offset;
                     return true;
                } else {
                     return false;
                }
                break;

            case SEEK_CUR:
                if ($offset >= 0) {
                     $this->_position += $offset;
                     return true;
                } else {
                     return false;
                }
                break;

            case SEEK_END:
                if (strlen($GLOBALS[$this->_viewVariableName]) + $offset >= 0) {
                     $this->_position = strlen($GLOBALS[$this->_viewVariableName]) + $offset;
                     return true;
                } else {
                     return false;
                }
                break;

            default:
                return false;
        }
    }

    /**
    * Stream statistics.
    */
    public function stream_stat()
    {
        $this->_stat = array('size' => strlen($GLOBALS[$this->_viewVariableName] )
                            );
        return $this->_stat;
    }
    
    /**
     * @return array
     */
    public function url_stat()
    {
        return $this->_stat;
    }
    
/**
 * Below implementation is kept for better using the static method and array to keep the data 
 * rather than using $GLOBAL. However, currently, PHP 5.3.8 does not support using static array
 * to save the data 
 */    
    
//    function stream_open($path, $mode, $options, &$opened_path)
//    {
//        /**
//         *  ex: $path = "xyz://abc" 
//         *  host = abc
//         */
//        $url = parse_url($path);
//        $this->_viewVariableName = $url["host"];
//        $this->_position = 0;
//
//        return true;
//    }
//
//    function stream_read($count)
//    {
//        $ret = substr(\Storage::$_varArray[$this->_viewVariableName], $this->_position, $count);
//        $this->_position += strlen($ret);
//        
//        return $ret;
//    }
//
//    function stream_write($data)
//    {
//        //initialize the element in the array
//        if (!isset(\Storage::$_varArray[$this->_viewVariableName])) {
//            \Storage::$_varArray[$this->_viewVariableName] = "";
//        }
//        
//        $left  = substr(\Storage::$_varArray[$this->_viewVariableName], 0, $this->_position);
//        $right = substr(\Storage::$_varArray[$this->_viewVariableName], $this->_position + strlen($data));
//        
//        \Storage::$_varArray[$this->_viewVariableName] = $left . $data . $right;
//        $this->_position += strlen($data);
//        
//        return strlen($data);
//    }
//
//    function stream_tell()
//    {
//        return $this->_position;
//    }
//
//    function stream_eof()
//    {
//        return $this->_position >= strlen(\Storage::$_varArray[$this->_viewVariableName]);
//    }
//
//    function stream_seek($offset, $whence)
//    {
//        switch ($whence) {
//            case SEEK_SET:
//                if ($offset < strlen(\Storage::$_varArray[$this->_viewVariableName]) && $offset >= 0) {
//                     $this->_position = $offset;
//                     return true;
//                } else {
//                     return false;
//                }
//                break;
//
//            case SEEK_CUR:
//                if ($offset >= 0) {
//                     $this->_position += $offset;
//                     return true;
//                } else {
//                     return false;
//                }
//                break;
//
//            case SEEK_END:
//                if (strlen(\Storage::$_varArray[$this->_viewVariableName]) + $offset >= 0) {
//                     $this->_position = strlen(\Storage::$_varArray[$this->_viewVariableName]) + $offset;
//                     return true;
//                } else {
//                     return false;
//                }
//                break;
//
//            default:
//                return false;
//        }
//    }
//
//    /**
//    * Stream statistics.
//    */
//    public function stream_stat()
//    {
//        //$this->_stat = array('size' => strlen( Storage::$_varArray[$this->_viewVariableName] )
//       //                     );
//        return $this->_stat;
//    }
//    
//    public function url_stat()
//    {
//        return $this->_stat;
//    }

}

?>
