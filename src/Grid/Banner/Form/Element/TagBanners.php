<?php

namespace Grid\Banner\Form\Element;

use Zork\Form\Element;

/**
 * TagBanners
 *
 * @author David Pozsar <david.pozsar@megaweb.hu>
 */
class TagBanners extends Element
{

    /**
     * @var array
     */
    protected $attributes = array(
        'type'          => 'banner_tags',
        'data-js-type'  => 'js.banner.tags',
    );

}
