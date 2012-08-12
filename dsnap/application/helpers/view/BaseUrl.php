<?php

class Zend_View_Helper_BaseUrl extends Zend_View_Helper_Abstract
{
    public function baseUrl( $public = true )
    {
    	$frontController = Zend_Controller_Front::getInstance();
       	$baseUrl = $frontController->getBaseUrl(); 
    	if ( $public && defined('RUNNING_FROM_ROOT' )) 
    	{
            $baseUrl .= '/public'; 
        } 
		
		return $baseUrl;
    }
}
