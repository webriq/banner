<?php

namespace Grid\Banner\Model\BannerSet;

use Zork\Model\Structure\MapperAwareAbstract;
use Grid\Banner\Model\Banner\StructureInterface as BannerStructure;
use Grid\Banner\Model\Banner\StructureFactory as BannerStructureFactory;

/**
 * Banner-set structure
 *
 * @author David Pozsar <david.pozsar@megaweb.hu>
 */
class Structure extends MapperAwareAbstract
{

    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var \Grid\Banner\Model\Banner\StructureInterface[][]
     */
    private $_tagBanners;

    /**
     * @var \Grid\Banner\Model\Banner\StructureInterface[][]
     */
    private $_localeBanners;

    /**
     * @var \Grid\Banner\Model\Banner\StructureInterface[]
     */
    private $_globalBanners;

    /**
     * Setter for name
     *
     * @param   string  $name
     * @return  \Grid\Banner\Model\BannerSet\Structure
     */
    public function setName( $name )
    {
        $this->name = (string) $name;
        return $this;
    }

    /**
     * Get tag banners
     *
     * @return  null|\Grid\Banner\Model\Banner\StructureInterface[][]
     */
    public function getTagBanners()
    {
        if ( $this->id && null === $this->_tagBanners )
        {
            $this->_tagBanners = $this->getMapper()
                                      ->findTagBanners( $this->id );
        }

        return $this->_tagBanners;
    }

    /**
     * Set tag banners
     *
     * @param   array|\Traversable  $banners
     * @return  \Grid\Banner\Model\BannerSet\Structure
     */
    public function setTagBanners( $banners )
    {
        $set = array();

        foreach ( $banners as $tagId => $bannerList )
        {
            $set[$tagId] = array();

            foreach ( $bannerList as $banner )
            {
                if ( ! $banner instanceof BannerStructure )
                {
                    $banner = BannerStructureFactory::factory( $banner );
                }

                $set[$tagId][] = $banner;
            }
        }

        $this->_tagBanners = $set;

        return $this;
    }

    /**
     * Get locale banners
     *
     * @return  null|\Grid\Banner\Model\Banner\StructureInterface[][]
     */
    public function getLocaleBanners()
    {
        if ( $this->id && null === $this->_tagBanners )
        {
            $this->_tagBanners = $this->getMapper()
                                      ->findLocaleBanners( $this->id );
        }

        return $this->_tagBanners;
    }

    /**
     * Set locale banners
     *
     * @param   array|\Traversable  $banners
     * @return  \Grid\Banner\Model\BannerSet\Structure
     */
    public function setLocaleBanners( $banners )
    {
        $set = array();

        foreach ( $banners as $locale => $bannerList )
        {
            $set[$locale] = array();

            foreach ( $bannerList as $banner )
            {
                if ( ! $banner instanceof BannerStructure )
                {
                    $banner = BannerStructureFactory::factory( $banner );
                }

                $set[$locale][] = $banner;
            }
        }

        $this->_localeBanners = $set;

        return $this;
    }

    /**
     * Get global banners
     *
     * @return  null|\Grid\Banner\Model\Banner\StructureInterface[]
     */
    public function getGlobalBanners()
    {
        if ( $this->id && null === $this->_tagBanners )
        {
            $this->_tagBanners = $this->getMapper()
                                      ->findGlobalBanners( $this->id );
        }

        return $this->_tagBanners;
    }

    /**
     * Set global banners
     *
     * @param   array|\Traversable  $banners
     * @return  \Grid\Banner\Model\BannerSet\Structure
     */
    public function setGlobalBanners( $banners )
    {
        $set = array();

        foreach ( $banners as $banner )
        {
            if ( ! $banner instanceof BannerStructure )
            {
                $banner = BannerStructureFactory::factory( $banner );
            }

            $set[] = $banner;
        }

        $this->_globalBanners = $set;

        return $this;
    }

}
