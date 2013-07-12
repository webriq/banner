<?php

namespace Grid\Banner\Model\Paragraph\Structure;

use Grid\Banner\Model\BannerSet;
use Grid\Tag\Model\TagsAwareInterface;
use Grid\Paragraph\Model\Paragraph\Structure\AbstractLeaf;

/**
 * Banner paragraph structure
 *
 * @author David Pozsar <david.pozsar@megaweb.hu>
 */
class Banner extends AbstractLeaf
{

    /**
     * Paragraph type
     *
     * @var string
     */
    protected static $type = 'banner';

    /**
     * Paragraph-render view-open
     *
     * @var string
     */
    protected static $viewOpen = 'grid/paragraph/render/banner';

    /**
     * Locale-aware properties
     *
     * @var array
     */
    protected static $localeAwareProperties = array();

    /**
     * Selected banner-set id
     *
     * @var int
     */
    protected $setId;

    /**
     * Selected banner-set structure
     *
     * @var \Grid\Banner\Model\BannerSet\Structure
     */
    private $setStructure = null;

    /**
     * Banner-set model
     *
     * @var \Grid\Banner\Model\BannerSet\Model
     */
    private $setModel = null;

    /**
     * Get the rendered content
     *
     * @return mixed|null
     */
    protected function getRenderedContent()
    {
        try
        {
            return $this->getServiceLocator()
                        ->get( 'RenderedContent' );
        }
        catch ( ServiceNotFoundException $ex )
        {
            return null;
        }
    }

    /**
     * Get banner-set model
     *
     * @return \Grid\Banner\Model\BannerSet\Model
     */
    protected function getSetModel()
    {
        if ( null === $this->setModel )
        {
            $this->setModel = $this->getServiceLocator()
                                   ->get( 'Grid\Banner\Model\BannerSet\Model' );
        }

        return $this->setModel;
    }

    /**
     * Set banner-set id
     *
     * @param   int $setId
     * @return  \Grid\Banner\Model\Paragraph\Structure\Banner
     */
    public function setSetId( $setId )
    {
        $this->setId = (int) $setId;

        if ( null !== $this->setStructure &&
             $this->setStructure->id != $this->setId )
        {
            $this->setStructure = null;
        }

        return $this;
    }

    /**
     * Get banner-set structure
     *
     * @return  \Grid\Banner\Model\BannerSet\Structure
     */
    public function getSet()
    {
        if ( empty( $this->setStructure ) && ! empty( $this->setId ) )
        {
            $this->setStructure = $this->getSetModel()
                                       ->find( $this->setId );
        }

        return $this->setStructure;
    }

    /**
     * Set area structure object
     *
     * @param   \Grid\Banner\Model\BannerSet\Structure $set
     * @return  \Grid\Banner\Model\Paragraph\Structure\Banner
     */
    public function setSet( BannerSet\Structure $set )
    {
        $this->setId        = $set->id;
        $this->setStructure = $set;
        return $this;
    }

    /**
     * Find banner
     *
     * @return  \Grid\Banner\Model\Banner\Structure
     */
    public function findBanner()
    {
        if ( empty( $this->setId ) )
        {
            return null;
        }

        $tagIds     = array();
        $rendered   = $this->getRenderedContent();
        $locale     = (string) $this->getServiceLocator()
                                    ->get( 'Locale' );

        $bannerMapper = $this->getServiceLocator()
                             ->get( 'Grid\Banner\Model\Banner\Mapper' );

        if ( $rendered instanceof TagsAwareInterface )
        {
            $tagIds = $rendered->getTagIds();
        }

        return $bannerMapper->findBanner( $this->setId, $tagIds, $locale );
    }

}
