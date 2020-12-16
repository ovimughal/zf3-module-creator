<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/Oapiemployeeprofile for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Oapirestmod\Controller;

use Oapirestmod\Handler\RestmodHandler;
use Laminas\Mvc\Controller\AbstractRestfulController;
use Laminas\View\Model\JsonModel;

class RestmodController extends AbstractRestfulController
{
    public function get($id)
    {
        $restmodHandler = new RestmodHandler();
        return new JsonModel($restmodHandler->getHandle($id));
    }

    public function getlist()
    {
        return new JsonModel([
            'Hello' => 'Welcome to ORestApi....! :)',
            'tagline' => 'Rest easily'
            ]);
    }
}
