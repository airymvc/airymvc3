<?php
/**
 * AiryMVC Framework
 *
 * @category AiryMVC
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 * @author: Hung-Fu Aaron Chang
 */
 namespace airymvc\app\lib;
/**
 * This deals with HTTP request.
 *
 * @package framework\app\library\http\HttpClient
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 */
class HttpClient {
    
    /**
     * Get the data from a url
     * 
     * @param string $url
     * @param int $timeOut
     * @return string 
     */
    public function get($url, $timeOut = 5) {
		$curl = curl_init();
		
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, $timeOut);

		$data = curl_exec($curl);
		curl_close($curl);
		return $data;
    }
 }
 
 
 
?>