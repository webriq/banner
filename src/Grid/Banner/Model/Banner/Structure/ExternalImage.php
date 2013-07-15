<?php

namespace Grid\Banner\Model\Banner\Structure;

/**
 * Banner external-image
 *
 * @author David Pozsar <david.pozsar@megaweb.hu>
 */
class ExternalImage extends ProxyAbstract
{

    /**
     * Banner type
     *
     * @var string
     */
    protected static $type = 'externalImage';

    /**
     * View-partial
     *
     * @var string
     */
    protected static $viewPartial = 'grid/banner/view/externalImage';

    /**
     * Image url
     *
     * @var string
     */
    protected $url = '';

    /**
     * Image alternate
     *
     * @var string
     */
    protected $alternate = '';

    /**
     * Image width
     *
     * @var string
     */
    protected $width;

    /**
     * Image height
     *
     * @var string
     */
    protected $height;

    /**
     * Image link to
     *
     * @var string
     */
    protected $linkTo;

    /**
     * Image link target
     *
     * @var string
     */
    protected $linkTarget;

    /**
     * Setter for url
     *
     * @param   string $url
     * @return  \Grid\Banner\Model\Banner\Structure\Image
     */
    public function setUrl( $url )
    {
        $this->url = (string) $url;
        return $this;
    }

    /**
     * Setter for alternate
     *
     * @param   string $alternate
     * @return  \Grid\Banner\Model\Banner\Structure\Image
     */
    public function setAlternate( $alternate )
    {
        $this->alt = (string) $alternate;
        return $this;
    }

    /**
     * Setter for width
     *
     * @param   int $width
     * @return  \Grid\Banner\Model\Banner\Structure\Image
     */
    public function setWidth( $width )
    {
        $this->width = empty( $width ) ? null : (int) $width;
        return $this;
    }

    /**
     * Setter for height
     *
     * @param   int $height
     * @return  \Grid\Banner\Model\Banner\Structure\Image
     */
    public function setHeight( $height )
    {
        $this->height = empty( $height ) ? null : (int) $height;
        return $this;
    }

    /**
     * Setter for link-to
     *
     * @param   string $linkTo
     * @return  \Grid\Banner\Model\Banner\Structure\Image
     */
    public function setLinkTo( $linkTo )
    {
        $this->linkTo = (string) $linkTo;
        return $this;
    }

    /**
     * Setter for link-target
     *
     * @param   string $linkTarget
     * @return  \Grid\Banner\Model\Banner\Structure\Image
     */
    public function setLinkTarget( $linkTarget )
    {
        $this->linkTarget = (string) $linkTarget;
        return $this;
    }

}
