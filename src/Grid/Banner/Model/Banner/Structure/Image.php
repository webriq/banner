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
     * @const string
     */
    const DEFAULT_METHOD = 'fit';

    /**
     * @const string
     */
    const DEFAULT_WIDTH = 200;

    /**
     * @const string
     */
    const DEFAULT_HEIGHT = 200;

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
     * Image-method
     *
     * @var string
     */
    protected $method = self::DEFAULT_METHOD;

    /**
     * Image width
     *
     * @var string
     */
    protected $width = self::DEFAULT_WIDTH;

    /**
     * Image height
     *
     * @var string
     */
    protected $height = self::DEFAULT_HEIGHT;

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
     * Setter for method
     *
     * @param   string $method
     * @return  \Grid\Banner\Model\Banner\Structure\Image
     */
    public function setMethod( $method )
    {
        $this->method = (string) $method;
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
