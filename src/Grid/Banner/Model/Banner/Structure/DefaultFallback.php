<?php

namespace Grid\Banner\Model\Banner\Structure;

/**
 * Default-fallback
 *
 * @author David Pozsar <david.pozsar@megaweb.hu>
 */
class DefaultFallback extends ProxyAbstract
{

    /**
     * View-partial
     *
     * @var string
     */
    protected static $viewPartial = 'grid/banner/view/default-fallback';

    /**
     * Return true if and only if $options accepted by this adapter
     * If returns float as likelyhood the max of these will be used as adapter
     *
     * @param array $options;
     * @return float
     */
    public static function acceptsOptions( array $options )
    {
        return 0.01;
    }

}
