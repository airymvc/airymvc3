<?php
/**
 * AiryMVC Framework
 *
 * @category AiryMVC
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 * @author: Hung-Fu Aaron Chang
 */
/**
 * This handles the language code and translation. The translation - the different language
 * word mappings are defined in the language files in the language folder (default folder is "lang").
 *
 * @package framework\app\library\lang\Language
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 */
class Language {

    private $_config;
    private static $instance;
    
    function __construct() {
        $this->_config = Config::getInstance();
    }
    
    public static function getInstance()
    {
        if(is_null(self::$instance)) {
            self::$instance = new self();
        }    
        
        return self::$instance;
    }
    
    /**
     * Static method for user
     */
    public static function translation($word, $fromLanguageCode, $toLanguageCode){
    	$instance = self::getInstance();
        return $instance->getTranslation($word, $fromLanguageCode, $toLanguageCode);
    }
    
    
    /**
     * Get the words from each language
     * @return array 
     */
    public function getLangaugeWord(){
        $langPath = $this->_config->getLanguageFolder();
        $root = PathService::getRootDir();
        $absLangPath = $root . DIRECTORY_SEPARATOR . $langPath;
        $ignore = array('.', '..', '.svn', '.DS_Store');
        $langArray = array();
		if ($handle = opendir($absLangPath)) {
            while (false !== ($file = readdir($handle))) {
                   $absFile = $absLangPath.DIRECTORY_SEPARATOR.$file;
                   $tLangArr = array();
                   if (!in_array($file, $ignore) && !is_dir($absFile)) {
                       try {
                          if (($tLangArr = @parse_ini_file($absFile, true)) == false) { 
                              throw new Exception('Cannot Parse INI file: ' . $absFile);
                          }
                       } catch (Exception $e) {
                              error_log($e->getMessage());
                       }
                       $langArray = array_merge($langArray, $tLangArr);
                   }
            }
        }
        return $langArray;
    }
    /**
     * @param string $key
     * @param string $langCode
     * @return string 
     */
    public function getWord($key, $langCode)
    {
        $words = $this->getLangaugeWord();
        if(!isset($words[$langCode][$key]) || !isset($words[$langCode])) {
           return null;
        }
        
        return $words[$langCode][$key]; 
    }
    /**
     *
     * @param string $word
     * @param string $fromLangCode
     * @param string $toLangCode
     * @return string 
     */
    public function getTranslation($word, $fromLangCode, $toLangCode)
    {
        $words = $this->getLangaugeWord();
        if(!isset($words[$fromLangCode]) || !isset($words[$toLangCode])) {
           return null;
        }
        $fromWords = $words[$fromLangCode];
        
        $wdKey = null;
        foreach ($fromWords as $key => $wdValue) {
            if ($wdValue == $word) {
                $wdKey = $key;
            }
        }
        if (!isset($words[$toLangCode][$wdKey])) {
             return null;
        }
        
        return $words[$toLangCode][$wdKey]; 
    }
    
    /**
     * Replace the word by the key defined in the language mapping file.
     * @param string $buffer 
     */
    public function replaceWordByKey($buffer){

        preg_match_all('/(%({\w*})({\w*})%|%({\w*})%)/', $buffer, $matches);
        /**
         * @TODO: Consider two level keyword like %{A}{B}% 
         */
        foreach ($matches[0] as $idx => $rawWdKey) {
                $tmpWdKey = str_replace('%{', '', $rawWdKey);
                $wdKey = str_replace('}%', '', $tmpWdKey);
                $toReplaceWord = $this->getWord($wdKey, LangReg::getLanguageCode()); 
                if ($toReplaceWord != "" || !is_null($toReplaceWord)) {
                	$buffer = str_replace($rawWdKey, $toReplaceWord, $buffer);
                }
        }   
            
        return $buffer;
    }
    
}

?>
