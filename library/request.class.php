<?php

class Request {
    
    function __construct() {}

    // Create the response
	function sendRequest($url){
        if(!isset($url)) return false;
        
        $opt = array();
        
        // Insert cookie authenToken in to the request's header
        if(isset($_COOKIE['accountID']) && isset($_COOKIE['accountID']) && isset($_COOKIE['accountID'])){
            $cookies = array(
                'accountID' => $_COOKIE['accountID'],
                'authToken' => $_COOKIE['authToken'],
                'email' => $_COOKIE['email']
            );
            $opt['http']['header'] = "Cookie: ";
            foreach($cookies as $k => $v){
                $opt['http']['header'] .= $k . "=" . $v . ";";
            }
        }
        
        $context = stream_context_create($opt);        
        $result = file_get_contents($url, false, $context);

        return (is_string($result)) ? $result : FALSE;
    }
    
    // Format the response
    function buildResponse($str_res){
        // Init structure
        $res = array(
            'status' => FALSE, 
            'code' => 500,
            'message' => '', 
            'value' => NULL
        );
        
        //Incorrect type response
        if(!is_string($str_res)){ 
            $res['message'] = 'Response is not a string';
            return $res;
        }
        
        // JSON decode fails
        $json_obj = json_decode($str_res);                      
        if(!$json_obj){ 
            $res['message'] = 'Response is not in Json type';
            return $res;
        }
        
        assert(is_int($json_obj->jsonCode));
        $res['value'] = $json_obj;
        
        $res['code'] = $json_obj->jsonCode;
        
        switch($json_obj->jsonCode){
            case 200:
                $res['status'] = TRUE;
                $res['message'] = 'OK';
                break;
            case 400:
                $res['message'] = 'Unrecognized command';
                break;
            case 402:
                $res['message'] = 'Missing argument';
                break;
            case 408:
                $res['message'] = 'Session expired';
                break;
            default:
                $res['message'] = $json_obj->jsonCode . ' error';
                break;
        }
        
        header('access-control-allow-origin: *');
        http_response_code($json_obj->jsonCode);
        
        return $res;
    }
}