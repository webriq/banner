<?php

namespace Grid\Banner\Form\Element;

use Zork\Form\Element;

/**
 * GlobalBanners
 *
 * @author David Pozsar <david.pozsar@megaweb.hu>
 */
class GlobalBanners extends Element
{

    /**
     * @var array
     */
    protected $attributes = array(
        'type'          => 'banner_globals',
        'data-js-type'  => 'js.banner.globals',
    );

}
