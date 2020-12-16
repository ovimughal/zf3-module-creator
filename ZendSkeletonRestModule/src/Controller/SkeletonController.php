<?php

/**
 * @link      http://github.com/zendframework/ZendSkeletonModule for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace ZendSkeletonModule\Controller;

use Oapirestmod\Handler\RestmodHandler;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\JsonModel;

class SkeletonController extends AbstractActionController
{

    public function indexAction()
    {
        return new JsonModel([
            'Hello' => 'Welcome to ORestApi....! :)',
            'tagline' => 'Rest easily'
        ]);
    }

    public function fooAction()
    {
        $restmodHandler = new RestmodHandler();
        $id = 1;
        return new JsonModel($restmodHandler->fooHandle($id));
//        return new \Laminas\View\Model\JsonModel([
//            'method' => 'post',
//            'name'=>'foo'
//        ]);
    }

    public function barAction()
    {
        $data = json_decode($this->getRequest()->getContent(), true);
        if (json_last_error()) {
            parse_str($this->getRequest()->getContent(), $data);
        }
        if (empty($data)) {
            $data = 'bar';
        }
        // print_r($data);die();
        return new JsonModel([
            'method' => 'put',
            'name' => $data
        ]);
    }

    public function exceptionExampleAction()
    {
        //$fileName = $this->params('filename'); e.g param
        $restmodHandler = new RestmodHandler();
        return new \Laminas\View\Model\JsonModel($restmodHandler->exceptionExampleHandle());
    }

}
