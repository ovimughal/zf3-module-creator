<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ZendSkeletonModule\Model;

use Oapiconfig\BaseProvider\OmodelBaseProvider;
use Oapiconfig\DI\ServiceInjector;
/**
 * Description of OapirestmodModel
 *
 * @author OviMughal
 */
class OapirestmodModel extends OmodelBaseProvider
{

    public function getRestmodDetails($id)
    {
        $dql = 'Write your query';

        $params = ['Pass','Param','Array'];
        $errMsg = 'Any error msg :)';
        $result = ['Hello' => 'Welcome to ORestApi....! :)'];//$this->select($dql, $params, $errMsg);
        if ($this->getSuccess()) {
		$result['tagline'] = 'Rest easily';
        }
        return $result;
    }

}
