<?php
/**
 * AiryMVC Framework
 * 
 * @category AiryMVC
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 * @author: Hung-Fu Aaron Chang
 */

/**
 * This class handles all the path variables that need to be initialized.
 *
 * @package framework\core\Initializer
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 */
class Initializer {
	
	const FILECACHE = "initialize_include_file";

    /**
     * This initialize those include path.
     * 
     */
    public static function initialize() {

    	//this is set for FileCache usage
    	$root = PathService::getRootDir();
    	$configFile = $root . DIRECTORY_SEPARATOR . "config" . DIRECTORY_SEPARATOR . "config.ini";
    	
    	if (file_exists($configFile)) {
    		set_include_path(get_include_path() . PATH_SEPARATOR . "app" . DIRECTORY_SEPARATOR . "library" . DIRECTORY_SEPARATOR . "cache");
    		if (!is_null(FileCache::get(self::FILECACHE))) {
    			set_include_path(FileCache::get(self::FILECACHE));
    			return;
    		}
    	}
        
    	set_include_path(get_include_path() . PATH_SEPARATOR . "core");
        set_include_path(get_include_path() . PATH_SEPARATOR . "config");
        set_include_path(get_include_path() . PATH_SEPARATOR . "app");
        set_include_path(get_include_path() . PATH_SEPARATOR . "app" . DIRECTORY_SEPARATOR . "library");
        set_include_path(get_include_path() . PATH_SEPARATOR . "project");
        set_include_path(get_include_path() . PATH_SEPARATOR . "project" . DIRECTORY_SEPARATOR . "share");
        set_include_path(get_include_path() . PATH_SEPARATOR . "project" . DIRECTORY_SEPARATOR . "modules");
        set_include_path(get_include_path() . PATH_SEPARATOR . "plugin");


        //set module paths
        $modulePath = $root . DIRECTORY_SEPARATOR . "project" .DIRECTORY_SEPARATOR . "modules";
        $moduleFolders = Initializer::getDirectory($modulePath, TRUE);
        foreach ($moduleFolders as $i => $mfolder)
        {
            $fd = trim($mfolder);
            $rp = trim($modulePath) . DIRECTORY_SEPARATOR;
            $f = "modules" .DIRECTORY_SEPARATOR .str_replace($rp, "", $fd);
            set_include_path(get_include_path() . PATH_SEPARATOR . $f);
        }
        
        /**
         * include folders under project, library, plug-in
         *  
         */
        $plugIn = $root . DIRECTORY_SEPARATOR . "plugin";
        $coreLib = $root . DIRECTORY_SEPARATOR . "app" . DIRECTORY_SEPARATOR . "library";
        $project = $root . DIRECTORY_SEPARATOR . "project";

        $plugInFolders = Initializer::getDirectory($plugIn, TRUE);
        foreach ($plugInFolders as $i => $folder1)
        {
            $fd = trim($folder1);
            $rp = trim($plugIn) . DIRECTORY_SEPARATOR;
            $f = "plugin" .DIRECTORY_SEPARATOR .str_replace($rp, "", $fd);           
            set_include_path(get_include_path() . PATH_SEPARATOR . $f);
        }
        
         
        $coreLibFolders = Initializer::getDirectory($coreLib, TRUE);
        foreach ($coreLibFolders as $i => $folder1)
        {
            $fd = trim($folder1);
            $rp = trim($coreLib) . DIRECTORY_SEPARATOR;
            $f = "app" . DIRECTORY_SEPARATOR . "library" .DIRECTORY_SEPARATOR .str_replace($rp, "", $fd);
            set_include_path(get_include_path() . PATH_SEPARATOR . $f);
        }
        
        $projectFolders = Initializer::getDirectory($project, TRUE);
        foreach ($projectFolders as $i => $folder1)
        {
            $fd = trim($folder1);
            $rp = trim($project) . DIRECTORY_SEPARATOR;
            $f = "project" .DIRECTORY_SEPARATOR .str_replace($rp, "", $fd);
            set_include_path(get_include_path() . PATH_SEPARATOR . $f);
        }
        if (file_exists($configFile)) {
        	FileCache::save(self::FILECACHE, get_include_path());
        }

    }
    
    /**
     * The input folder will be recursively loop throught and save the folder into an array.
     * 
     * @param string $directory the root directory.
     * @param boolean $recursive sets if the function recursively fetches the directories.
     * 
     * @return array all the directories.
     */    
    public static function getDirectory($directory, $recursive) {
		$array_items = array();
        $ignore = array('.', '..', '.svn', '.DS_Store');
		if ($handle = opendir($directory)) {
			while (false !== ($file = readdir($handle))) {
				if (!in_array($file, $ignore)) {
					if (is_dir($directory. DIRECTORY_SEPARATOR . $file)) {
						if($recursive) {
							$array_items = array_merge($array_items, Initializer::getDirectory($directory. DIRECTORY_SEPARATOR . $file, $recursive));
						}
						$file = $directory . DIRECTORY_SEPARATOR . $file;
	                                        if (DIRECTORY_SEPARATOR == "\\") {
	                                            $array_items[] = preg_replace("/\\\\/si", DIRECTORY_SEPARATOR, $file);
	                                        } else {
	                                            $array_items[] = preg_replace("/\/\//si", DIRECTORY_SEPARATOR, $file);
	                                        }

					}
				}
			}
			closedir($handle);
		}
		return $array_items;
	}

}

?>
