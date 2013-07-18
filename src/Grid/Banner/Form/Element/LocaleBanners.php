<?php

namespace Grid\Banner\Form\Element;

use Zork\Form\Element;

/**
 * LocaleBanners
 *
 * @author David Pozsar <david.pozsar@megaweb.hu>
 */
class LocaleBanners extends Element
{

    /**
     * @var array
     */
    protected $attributes = array(
        'type'          => 'banner_locales',
        'data-js-type'  => 'js.banner.locales',
    );

}
