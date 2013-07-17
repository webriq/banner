<?php

namespace Grid\Banner;

use Zork\Stdlib\ModuleAbstract;
use Zend\ModuleManager\Feature\ViewHelperProviderInterface;

/**
 * Grid\Banner\Module
 *
 * @author David Pozsar <david.pozsar@megaweb.hu>
 */
class Module extends ModuleAbstract
          implements ViewHelperProviderInterface
{

    /**
     * Module base-dir
     *
     * @const string
     */
    const BASE_DIR = __DIR__;

    /**
     * Expected to return \Zend\ServiceManager\Config object or array to
     * seed such an object.
     *
     * @return array|\Zend\ServiceManager\Config
     */
    public function getViewHelperConfig()
    {
        return array(
            'invokables' => array(
                'formBannerGlobals' => 'Grid\Banner\Form\View\Helper\FormBannerGlobals',
            ),
        );
    }

}
