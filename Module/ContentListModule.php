<?php
/**
 * Created by Vitiko
 * Date: 25.01.12
 * Time: 15:29
 */

namespace Iphp\ContentBundle\Module;

use Iphp\CoreBundle\Module\Module;


/**
 * Module - content list
 */
class ContentListModule extends Module
{

    function __construct()
    {
        $this->setName('Content list');
        $this->allowMultiple = true;
    }

    protected function registerRoutes()
    {
        $this->addRoute('index', '/', array('_controller' => 'IphpContentBundle:Content:list'))
             ->addRoute('contentBySlug', '/{slug}/', array('_controller' => 'IphpContentBundle:Content:contentBySlug'));
    }

}
