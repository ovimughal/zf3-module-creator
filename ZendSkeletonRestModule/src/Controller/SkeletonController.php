<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonModule for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace ZendSkeletonModule\Controller;

use Zend\Mvc\Controller\AbstractActionController;

class SkeletonController extends AbstractActionController
{
    public function indexAction()
    {
        return new \Zend\View\Model\JsonModel([
            'method' => 'get',
            'name'=>'index'
        ]);
    }
    
    public function fooAction()
    {
        return new \Zend\View\Model\JsonModel([
            'method' => 'post',
            'name'=>'foo'
        ]);
    }
    
    public function barAction()
    {
        return new \Zend\View\Model\JsonModel([
            'method' => 'put',
            'name'=>'bar'
        ]);
    }
    
    public function bazAction()
    {
        return new \Zend\View\Model\JsonModel([
            'method' => 'delete',
            'name'=>'baz'
        ]);
    }
}
