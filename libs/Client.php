<?php

/**
 * RESTful Client.
 * Makes RESTful HTTP Requests to Web Sevices.
 *
 * Supports Various Adapters -
 * 1. PHP Streams (stream_context_create)
 * 2. PHP Sockets (fsockopen)
 * 3. cURL        (curl_init)
 *
 * Usage -
 * <code>
 *  $ob = new Client('socket');
 *  $response = $ob->delete('http://localhost/request.php', array('foo' => 'bar', 'baz' => 'bak'));
 *  var_dump($response);
 * </code>
 *
 */

class Client {

  /**
   * Last Request's Method
   *
   * @var string HEAD, GET, POST, PUT, DELETE
   */
  public $request_method;
  
  /**
   * Adapter to be Used.
   *
   * @var string curl, stream, socket
   */
  public $adapter;
  
  /**
   * Adapter Used in the Last Request.
   *
   * @var string curl, stream, socket
   */
  public $adapter_used;

  public $user_agent = 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.31 (KHTML, like Gecko) Chrome/26.0.1410.63 Safari/537.31';
  
  public function __construct($adapter = null) {
    // Array of Adapters
    $arr = array('curl' => 'curl_init', 'stream' => 'stream_context_create', 'socket' => 'fsockopen');
    
    // Lets find out which Adapter to use
    
    if ($adapter) {
      if (isset($arr[$adapter]) && !function_exists($arr[$adapter])) $adapter = null;
    }
    
    if (!$adapter) {
      if (function_exists($arr['curl'])) $adapter = 'curl';
      else if (function_exists($arr['stream'])) $adapter = 'stream';
      else if (function_exists($arr['socket'])) $adapter = 'socket';
    }
    
    if (!$adapter) throw new \Exception("No Adapter/Library Found.");
    else $this->adapter = $adapter;
  }
  
  /**
   * HEAD
   *
   * @param string $url API URL
   * @param array $params {key => value} Parameters to Pass to the Request
   */
  public function head($url, $params = array()) {
    $this->request_method = 'HEAD';
    return $this->_request($url, $params, 'HEAD');
  }

  /**
   * GET
   *
   * @param string $url API URL
   * @param array $params {key => value} Parameters to Pass to the Request
   */
  public function get($url, $params = array()) {
    $this->request_method = 'GET';
    return $this->_request($url, $params, 'GET');
  }
  
  /**
   * POST
   *
   * @param string $url API URL
   * @param array $params {key => value} Parameters to Pass to the Request
   */
  public function post($url, $params = array()) {
    $this->request_method = 'POST';
    return $this->_request($url, $params, 'POST');
  }
  
  /**
   * PUT
   *
   * @param string $url API URL
   * @param array $params {key => value} Parameters to Pass to the Request
   */
  public function put($url, $params = array()) {
    $this->request_method = 'PUT';
    return $this->_request($url, $params, 'PUT');
  }
  
  /**
   * DELETE
   *
   * @param string $url URL to perform action on
   * @param array $params {key => value} Parameters to Pass to the Request
   */
  public function delete($url, $params = array()) {
    $this->request_method = 'DELETE';
    return $this->_request($url, $params, 'DELETE');
  }
  
  /**
   * Make HTTP Request.
   * Uses Several Methods -
   * 1. PHP Streams
   * 2. PHP Sockets
   * 3. cURL
   *
   * @param string API URL
   * @param array  {key => value} Parameters to Pass to the Request
   * @param string HTTP Request Method
   * @return array Array with Response Data, Error Codes/Msgs, Method used to make the Request
   */
  protected function _request($url, $params = array(), $method = 'GET') {
    $adapter = '_' . $this->adapter;
    
    $query_str = http_build_query($params);
    return $this->$adapter($url, $query_str, $method);
  }
  
  protected function _curl($url, $query_str, $method) {
    $this->adapter_used = 'curl';
    
    $data_len = strlen($query_str);
    $url_parts = parse_url($url);
    
    $ch = curl_init($url_parts['host']);
    
    switch ($method) {
      case 'HEAD':
        curl_setopt($ch, CURLOPT_NOBODY, true);
        break;
      
      case 'GET':
        if ($query_str)
          curl_setopt($ch, CURLOPT_URL, $url . "?" . $query_str);
        else
          curl_setopt($ch, CURLOPT_URL, $url);
        break;
      
      case 'POST':
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $query_str);
        break;
      
      case 'PUT':
        curl_setopt($ch, CURLOPT_URL, $url);
        
        $fh = fopen('php://memory', 'rw');
        fwrite($fh, $query_str);
        rewind($fh);
        
        curl_setopt($ch, CURLOPT_PUT, true);
        curl_setopt($ch, CURLOPT_INFILE, $fh);
        curl_setopt($ch, CURLOPT_INFILESIZE, $data_len);
        break;
      
      case 'DELETE':
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
        
        if ($query_str) {
          // $fh = fopen('php://memory', 'rw');
          // fwrite($fh, $query_str);
          // rewind($fh);
          
          // curl_setopt($ch, CURLOPT_INFILE, $fh);
          // curl_setopt($ch, CURLOPT_INFILESIZE, strlen($query_str));
          curl_setopt($ch, CURLOPT_POSTFIELDS, $query_str);
        }
        break;
    }
    
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Return the data
    if ($method != 'HEAD') curl_setopt($ch, CURLOPT_HEADER, false); // Get headers

    // Set the UA, extremely important
    curl_setopt($ch, CURLOPT_USERAGENT, $this->user_agent);
    
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    // Follow any "Location: " header. Use CURLOPT_MAXREDIRS to limit the recursion.
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_MAXREDIRS, 5);
    
    // HTTP digest authentication
    if (isset($url_parts['user']) && isset($url_parts['pass'])) {
      $authHeaders = array("Authorization: Basic " . base64_encode($url_parts['user'] . ':' . $url_parts['pass']));
      curl_setopt($ch, CURLOPT_HTTPHEADER, $authHeaders);
      // curl_setopt($curlHandle, CURLOPT_HTTPAUTH, CURLAUTH_DIGEST);
      // curl_setopt($curlHandle, CURLOPT_USERPWD, $url_parts['user'] . ':' . $url_parts['pass']);
    }
    
    $response = curl_exec($ch);
    $responseInfo = curl_getinfo($ch);
    curl_close($ch);
    if (isset($fh) && is_resource($fh)) fclose($fh);
    
    // Only return false on 404 or 500 errors for now
    if($responseInfo['http_code'] == 404 || $responseInfo['http_code'] == 500) {
      $response = false;
    }

    return $response;
  }
  
  protected function _stream($url, $query_str, $method) {
    $this->adapter_used = 'stream';
    
    $data_len = strlen($query_str);
    $url_parts = parse_url($url);
    
    // HTTP digest authentication
    if (isset($url_parts['user']) && isset($url_parts['pass'])) {
      $auth = "Authorization: Basic " . base64_encode($url_parts['user'] . ':' . $url_parts['pass']);
    }
    else {
      $auth = '';
    }
    
    // http://php.net/manual/en/context.http.php
    $opt = array (
      'http'=> array (
        'method' => strtoupper($method),
        'header' => array(
          "Content-type: application/x-www-form-urlencoded",
          "Authorization: Basic " . base64_encode($url_parts['user'] . ':' . $url_parts['pass']),
          "Connection: close",
          "Content-Length: $data_len",
          "User-Agent: {$this->user_agent}",
        ),
        'content' => $query_str,
        'timeout' => 10.0,
        'protocol_version' => 1.1,
        'max_redirects' => 5,
        'verify_peer' => false,
        'verify_host' => false, // not documented, using just like that :P
      )
    );
    
    $opt['http']['header'][] = $auth;
    
    $response = array (
      'content' => file_get_contents ($url, false, stream_context_create ($opt)),
      'headers' => $http_response_header, // Reserved and Predefined Variable
    );
    
    return $response;
  }
  
  protected function _socket($url, $query_str, $method) {
    $this->adapter_used = 'socket';
    
    $data_len = strlen($query_str);
    $url_parts = parse_url($url);
    
    // HTTP digest authentication
    if (isset($url_parts['user']) && isset($url_parts['pass'])) {
      $auth = "Authorization: Basic " . base64_encode($url_parts['user'] . ':' . $url_parts['pass']);
    }
    else {
      $auth = '';
    }
    
    $fp = fsockopen($url_parts['host'], 80, $errno, $errstr, 10);
    
    if (!$fp) {
      echo "$errstr ($errno)<br />\n";
    }
    else {
      $out = "$method {$url_parts['path']} HTTP/1.1\r\n";
      $out .= "Host: {$url_parts['host']}\r\n";
      $out .= "Content-Type: application/x-www-form-urlencoded\r\n";
      $out .= "Content-Length: $data_len\r\n";
      $out .= $auth;
      $out .= "User-Agent: Mozilla/5.0 Firefox/3.6.12\r\n";
      $out .= "Connection: Close\r\n\r\n";
      
      // Write the Headers
      fwrite($fp, $out);
      
      // Write the Body
      fwrite($fp, $query_str);
      
      $response = '';
      while (!feof($fp)) {
        $response .= fgets($fp);
      }
      
      fclose($fp);
    }
    
    return $response;
  }

}