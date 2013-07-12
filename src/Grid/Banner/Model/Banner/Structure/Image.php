<?php

namespace Grid\Banner\Model\Banner\Structure;

use Grid\Banner\Model\Banner\StructureAbstract;

/**
 * Banner image
 *
 * @author David Pozsar <david.pozsar@megaweb.hu>
 */
class Image extends StructureAbstract
{

    /**
     * @const int
     */
    const MIN_WIDTH = 50;

    /**
     * @const int
     */
    const MIN_HEIGHT = 50;

    /**
     * Banner type
     *
     * @var string
     */
    protected static $type = 'image';

    /**
     * View-partial
     *
     * @var string
     */
    protected static $viewPartial = 'grid/banner/view/image';

    /**
     * Image uri
     *
     * @var string
     */
    protected $uri = '';

    /**
     * Image alt
     *
     * @var string
     */
    protected $alt = '';

    /**
     * Image width
     *
     * @var string
     */
    protected $width = 200;

    /**
     * Image height
     *
     * @var string
     */
    protected $height = 200;

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
     * Setter for uri
     *
     * @param   string $uri
     * @return  \Grid\Banner\Model\Banner\Structure\Image
     */
    public function setUri( $uri )
    {
        $this->uri = (string) $uri;
        return $this;
    }

    /**
     * Setter for alt
     *
     * @param   string $alt
     * @return  \Grid\Banner\Model\Banner\Structure\Image
     */
    public function setAlt( $alt )
    {
        $this->alt = (string) $alt;
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
        $this->width = max( static::MIN_WIDTH, (int) $width );
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
        $this->width = max( static::MIN_HEIGHT, (int) $height );
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
