<?php

namespace Grid\Banner\Model\BannerSet;

use Zork\Model\Structure\MapperAwareAbstract;
use Grid\Banner\Model\Banner\StructureInterface as BannerStructure;

/**
 * Banner-set structure
 *
 * @author David Pozsar <david.pozsar@megaweb.hu>
 * @method \Grid\Banner\Model\BannerSet\Mapper getMapper()
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
    protected $tagBanners;

    /**
     * @var \Grid\Banner\Model\Banner\StructureInterface[][]
     */
    protected $localeBanners;

    /**
     * @var \Grid\Banner\Model\Banner\StructureInterface[]
     */
    protected $globalBanners;

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
     * @param   array   $data
     * @return  \Grid\Banner\Model\Banner\StructureInterface
     */
    protected function createBanner( $data )
    {
        $mapper = $this->getMapper()
                       ->getBannerMapper();
        $banner = $mapper->getStructureFactory()
                         ->factory( $data );
        $banner->setMapper( $mapper );
        return $banner;
    }

    /**
     * Get tag banners
     *
     * @return  null|\Grid\Banner\Model\Banner\StructureInterface[][]
     */
    public function getTagBanners()
    {
        if ( $this->id && null === $this->tagBanners )
        {
            $this->tagBanners = $this->getMapper()
                                     ->findTagBanners( $this->id );
        }

        return $this->tagBanners;
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
                    $banner = $this->createBanner( $banner );
                }

                $set[$tagId][] = $banner;
            }
        }

        $this->tagBanners = $set;

        return $this;
    }

    /**
     * Get locale banners
     *
     * @return  null|\Grid\Banner\Model\Banner\StructureInterface[][]
     */
    public function getLocaleBanners()
    {
        if ( $this->id && null === $this->localeBanners )
        {
            $this->localeBanners = $this->getMapper()
                                        ->findLocaleBanners( $this->id );
        }

        return $this->localeBanners;
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
                    $banner = $this->createBanner( $banner );
                }

                $set[$locale][] = $banner;
            }
        }

        $this->localeBanners = $set;

        return $this;
    }

    /**
     * Get global banners
     *
     * @return  null|\Grid\Banner\Model\Banner\StructureInterface[]
     */
    public function getGlobalBanners()
    {
        if ( $this->id && null === $this->globalBanners )
        {
            $this->globalBanners = $this->getMapper()
                                        ->findGlobalBanners( $this->id );
        }

        return $this->globalBanners;
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
                $banner = $this->createBanner( $banner );
            }

            $set[] = $banner;
        }

        $this->globalBanners = $set;

        return $this;
    }

}
