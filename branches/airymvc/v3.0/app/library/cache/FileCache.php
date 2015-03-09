<?php
/**
 * AiryMVC Framework
 *
 * @category AiryMVC
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 * @author: Hung-Fu Aaron Chang
 */
/**
 * This is for file cache.
 *
 * @package framework\app\library\cache\FileCache
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 */
class FileCache {

	/**
	 * @var string $_cacheFolder
	 */
    private $_cacheFolder;
    
    /**
     * @var object $instance
     */
    private static $instance;
    //$lifetime is the overall cache lifetime, used when no key specific lifetime
    
    /**
     * @var int $lifetime
     */
    private static $lifetime;
    
    /**
     * @var array $cacheSpecificLifetime
     */
    private static $cacheSpecificLifetime = array();
    
    /**
     * Constructor.
     * 
     * @param string $cacheFolder
     */
    function __construct($cacheFolder = NULL) {
    	if (is_null($cacheFolder)) {
        	$config = Config::getInstance();
        	$root = PathService::getRootDir();
			$this->_cacheFolder = $root . DIRECTORY_SEPARATOR . $config->getCacheFolder();
    	} else {
    		$this->_cacheFolder = $cacheFolder;
    	}
		FileCache::$lifetime = 60*5;
    }
    
    public static function getInstance($cacheFolder = NULL)
    {
        if(is_null(self::$instance)) {
            self::$instance = new self($cacheFolder);
        }    
        
        return self::$instance;
    }    
    
    /**
     * Static method for user to save cache.
     * @param string $save
     * @param string $content
     */
    public static function save($key, $content, $cacheLifetime = null){
    	$instance = self::getInstance();
    	$key = md5($key);
    	if (!is_null($cacheLifetime)) {
    		FileCache::$cacheSpecificLifetime[$key] = $cacheLifetime;
    	}
        return $instance->saveFileData($key, $content);
    }
    
    /**
     * Static method for getting cache.
     * 
     * @see FileCache::getFileData()
     * @param string $key
     * @return string $content
     */    
    public static function get($key){
    	$instance = self::getInstance();
     	$key = md5($key);
     	$filename = $instance->_cacheFolder . DIRECTORY_SEPARATOR .$key; 
     	$cache = null;  
     	if (file_exists($filename)) {	
     		if ((time() - filemtime($filename)) < (FileCache::$lifetime)) {
     			$cache = $instance->getFileData($key);
     		} else {
     			$instance->removeFileData($key);
     		}
     	}
        return $cache;
    }
    
    /**
     * Static method for saving data into file.
     *
     * @see FileCache::saveFileData()
     * @param string $key
     * @param string $content
     */
    public static function saveFile($key, $content){
    	$instance = self::getInstance();
        return $instance->saveFileData($key, $content);
    }

    /**
     * Get the file.
     * @param string $key
     */
    public static function getFile($key){
    	$instance = self::getInstance();
        return $instance->getFileData($key);
    }

    /**
     * Remove the file.
     * @param string $key
     */
    public static function removeFile($key){
    	$instance = self::getInstance();
        return $instance->removeFileData($key);
    }

    /**
     * Set the lifetime of the cache.
     * @param int $time
     */
    public static function setLifeTime($time){
    	FileCache::$lifetime = $time;
    }

    /**
     * Get the lifetime of the cache.
     * @return int
     */
    public static function getLifeTime(){
		return FileCache::$lifetime;
    }
    
    /**
     * Save the data into a cache file.
     * @return boolean 
     */
    public function saveFileData($filename, $content){
		$filename = $this->_cacheFolder . DIRECTORY_SEPARATOR .$filename;
		file_put_contents($filename, $content);
    }
    
    /**
     * Get the data from a file
     * @param string filename
     * @return string 
     */
    public function getFileData($filename){
		$filename = $this->_cacheFolder . DIRECTORY_SEPARATOR .$filename;
		$content = null;
		if (file_exists($filename)) {
			$content = file_get_contents($filename);
		}
		return $content;
    }
    
    /**
     * Remove the file.
     * @param string filename
     */
    public function removeFileData($filename){
		$filename = $this->_cacheFolder . DIRECTORY_SEPARATOR .$filename;
		$command = "rm -rf {$filename}";
		if (file_exists($filename)) {
			exec($command);
		}
    }
    
    
}

?>
