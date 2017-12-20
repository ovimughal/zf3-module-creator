<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonModule for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace ZendSkeletonModule\Controller;

use Oapirestmod\Handler\RestmodHandler;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;

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
//        return new \Zend\View\Model\JsonModel([
//            'method' => 'post',
//            'name'=>'foo'
//        ]);
    }
    
    public function barAction()
    {
        return new JsonModel([
            'method' => 'put',
            'name'=>'bar'
        ]);
    }
    
    public function bazAction()
    {
        return new JsonModel([
            'method' => 'delete',
            'name'=>'baz'
        ]);
    }
}
