<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 3/16/15
 * Time: 11:09 PM
 */

namespace Maverickslab\Shopify;


use Guzzle\Service\Client;
use Maverickslab\Shopify\Exceptions\ShopifyException;

class Shopify {

    /**
     * @var
     */
    public  $requestor;

    public function __construct(ApiRequestor $requestor){

        $this->requestor = $requestor;
    }



    public function __call($methodName, $arguments){

        if(is_null($this->requestor->storeUrl))
            throw new ShopifyException('Store Url not provided');

//        if(is_null($this->requestor->storeToken))
//            throw new ShopifyException('Access Token not provided');

        $class = $this->resolveClass($methodName);

       return new $class($this->requestor);
    }


    public function install()
    {
        return $this->requestor->install();
    }


    public function getAccessToken($responseParams)
    {
       return $this->requestor->getAccessToken($responseParams);
    }


    public function resolveClass($className)
    {
        $class = $this->getNamespace().$this->sanitizeClassName ( $className );
        if( class_exists($class) ){
            return $class;
        }

        throw new \Exception;
    }


    public function shop($storeUrl = null, $storeToken = null)
    {
        $this->requestor->storeUrl = $storeUrl;
        $this->requestor->storeToken = $storeToken;

        return $this;
    }

    /**
     * @param $className
     * @return bool
     */
    public function sanitizeClassName ( $className )
    {
        if(trim(substr($className, -1)) == 's'){
            $className = chop($className, 's');
        }
        return  ucfirst( $className );
    }


    public function getNamespace()
    {
        return 'Maverickslab\Shopify\Resources\\';
    }
} 