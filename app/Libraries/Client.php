<?php

namespace App\Libraries;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Exceptions\ApiException;

class Client
{
    public static function getServiceAuthUrl()
    {
        return "localhost/api/v1/";
    }
    
    public static function call($action, $params = [], $method = "post", $token = false, $attach = [])
    {
        $responseHeaders = [
            "Content-Type" => "application/json",
            "Accept" => "application/json",
        ];
        
        if($token)
            $responseHeaders["Authorization"] = "Bearer " . session("api_token");
        
        $url = static::getServiceAuthUrl() . $action;
        if(!empty($attach))
        {
            if($method != "post")
                throw new Exception("Method not allowed");
            
            unset($responseHeaders["Content-Type"]);
            $response = Http::withHeaders($responseHeaders);
            
            foreach($attach as $file)
                $response->attach($file['name'], fopen($file["tmp_name"], "r"), $file['file_name']);
                
            $response = $response->post($url, $params);
        }
        else
        {
            $response = Http::withHeaders($responseHeaders);
            switch($method)
            {
                case "get":
                    $response = $response->asForm()->get($url, $params);
                break;
                case "post":
                    $response = $response->asForm()->post($url, $params);
                break;
                case "delete":
                    $response = $response->asForm()->delete($url, $params);
                break;
                case "patch":
                    $response = $response->patch($url, $params);
                break;
                case "put":
                    $response = $response->asForm()->put($url, $params);
                break;
            }
        }
        
        return [
            "data" => $response->json(),
            "status_code" => $response->getStatusCode(),
        ];
    }
}