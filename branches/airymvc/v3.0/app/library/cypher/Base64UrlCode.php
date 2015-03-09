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
 * @package framework\app\library\cypher\Base64UrlCode
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 */
class Base64UrlCode {
    

    private static $secretKey = "XsdfGDS#$3C";

	/**
	 * Encrypt the text.
     * @param string $text
     * @param string $secretKey Default value = NULL.
     * @return string 
     */
    public static function  encrypt($text, $secretKey = null) {
        $secretKey = (is_null($secretKey)) ? self::$secretKey : $secretKey;
        $ctext = base64_encode($text) . $secretKey;
        $result = urlencode(base64_encode($ctext));
        return $result;
    }

	/**
	 * Decrypt the text.
     * @param string $code
     * @param string $secretKey Default value = NULL.
     * @return string 
     */
    public static function  decrypt($code, $secretKey = null) {
        $secretKey = (is_null($secretKey)) ? self::$secretKey : $secretKey;
        $data = urldecode($code);
        $ctext = base64_decode($data);
        $endpos = strlen($ctext) - strlen($secretKey);
        $text = substr($ctext, 0, $endpos);
        $result =  base64_decode($text);
        return $result;
    }
    
}

?>
