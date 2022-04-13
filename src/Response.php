<?php

namespace Src;

class Response
{

    public  $HTTP_OK = "HTTP/1.1 200 OK";
    public  $HTTP_CREATED = "HTTP/1.1 201 CREATED";
    public  $HTTP_BAD_REQUEST = "HTTP/1.1 400 Bad Request";
    public  $HTTP_NOT_FOUND = "HTTP/1.1 404 NOT FOUND";
    public  $HTTP_UNPROCESSABLE = "HTTP/1.1 422 Unprocessable Entity";
    public  $HTTP_NOT_ALLOWED = "HTTP/1.1 405 Method Not Allowed";
    public  $HTTP_INTERNAL_SERVER_ERROR = "HTTP/1.1 500 INTERNAL SERVER ERROR";


    public $headers=[];
    protected $content;
    protected $statusCode;
    protected $statusText;

    
    
    public static $statusTexts = [        
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        400 => 'Bad Request',
        404 => 'Not Found',
        405 => 'Not Allowed',
        422 => 'Unprocessable Entity',
        500 => 'Internal Server Error',
    ];


    public function __construct( $content = '', int $status , array $headers = [])
    {
        $this->setHeader($status);
        $this->setContent($content);
        $this->setStatusCode($status);
    }

    /**
     * Factory method for chainability.
     */
    public function get()
    {
        $response['header'] = implode("','",$this->headers);
        $response['body']['msg'] = $this->statusText;
        $response['body']['code'] = $this->statusCode;
        $response['body']['content'] = $this->content ;
        header($response['header']);
        if ($response['body']) {
          print_r (json_encode($response['body']) );
        }
        exit();
    }

   
    public function setHeader($status)
    {
     
        switch($status){
            case 200 :
              array_push($this->headers,$this->HTTP_OK);
              break;
            case 201 :
              array_push($this->headers,$this->HTTP_CREATED);
              break;
            case 400 :
                header($this->HTTP_BAD_REQUEST);
              break;
            case 404 :
              array_push($this->headers,$this->HTTP_NOT_FOUND);
              break;
            case 405 :
              array_push($this->headers,$this->HTTP_NOT_ALLOWED);
              break;
            case 422 :
              array_push($this->headers,$this->HTTP_UNPROCESSABLE);
              break;
            case 500 :
              array_push($this->headers,$this->HTTP_INTERNAL_SERVER_ERROR);
              break;
        }
        
        
    }

    public function setContent($content)
    {
        $this->content = $content;
    }

    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;
        $this->statusText = Response::$statusTexts[$statusCode];
    }

}