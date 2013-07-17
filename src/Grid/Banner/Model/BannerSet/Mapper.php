<?php

namespace Grid\Banner\Model\BannerSet;

use Zork\Model\Mapper\DbAware\ReadWriteMapperAbstract;
use Grid\Banner\Model\Banner\Mapper as BannerMapper;

/**
 * Banner-set mapper
 *
 * @author David Pozsar <david.pozsar@megaweb.hu>
 */
class Mapper extends ReadWriteMapperAbstract
{

    /**
     * Table name used in all queries
     *
     * @var string
     */
    protected static $tableName = 'banner_set';

    /**
     * Default column-conversion functions as types;
     * used in selected(), deselect()
     *
     * @var array
     */
    protected static $columns = array(
        'id'    => self::INT,
        'name'  => self::STRING,
    );

    /**
     * @var \Grid\Banner\Model\Banner\Mapper
     */
    protected $bannerMapper;

    /**
     * @return \Grid\Banner\Model\Banner\Mapper
     */
    public function getBannerMapper()
    {
        return $this->bannerMapper;
    }

    /**
     * @param   \Grid\Banner\Model\Banner\Mapper $bannerMapper
     * @return  \Grid\Banner\Model\BannerSet\Mapper
     */
    public function setBannerMapper( BannerMapper $bannerMapper )
    {
        $this->bannerMapper = $bannerMapper;
        return $this;
    }

    /**
     * Contructor: Sets banner mapper & banner-set structure prototype
     *
     * @param   \Grid\Banner\Model\Banner\Mapper        $bannerMapper
     * @param   \Grid\Banner\Model\BannerSet\Structure  $bannerSetStructurePrototype
     */
    public function __construct( BannerMapper $bannerMapper,
                                 Structure $bannerSetStructurePrototype = null )
    {
        $this->setBannerMapper( $bannerMapper );
        parent::__construct( $bannerSetStructurePrototype ?: new Structure );
    }

    /**
     * Find tag banners
     *
     * @param   int $setId
     * @return  \Grid\Banner\Model\Banner\StructureInterface[][]
     */
    public function findTagBanners( $setId )
    {
        $sql = $this->sql(
            $this->getTableInSchema( 'banner_x_set_by_tag' )
        );

        $select = $sql->select()
                      ->columns( array( 'bannerId' ) )
                      ->join( 'banner_set_x_tag',
                              'banner_set_x_tag.id = setXTagId',
                              array( 'tagId' ) )
                      ->where( array(
                          'setId'       => (int) $setId,
                      ) )
                      ->order( array(
                          'priority'    => 'DESC',
                          'bannerId'    => 'ASC',
                      ) );

        $result = $this->sql()
                       ->prepareStatementForSqlObject( $select )
                       ->execute();

        if ( $result->getAffectedRows() < 1 )
        {
            return array();
        }

        $ids    = array();
        $tagIds = array();
        $return = array();

        foreach ( $result as $row )
        {
            $tagIds[$ids[] = (int) $row['bannerId']] = $row['tagId'];
        }

        foreach ( $this->getBannerMapper()
                       ->findAllByIds( $ids ) as $banner )
        {
            $tagId = $tagIds[$banner->id];

            if ( empty( $return[$tagId] ) )
            {
                $return[$tagId] = array();
            }

            $return[$tagId][] = $banner;
        }

        return $return;
    }

    /**
     * Find locale banners
     *
     * @param   int $setId
     * @return  \Grid\Banner\Model\Banner\StructureInterface[][]
     */
    public function findLocaleBanners( $setId )
    {
        $sql = $this->sql(
            $this->getTableInSchema( 'banner_x_set_by_locale' )
        );

        $select = $sql->select()
                      ->columns( array( 'bannerId', 'locale' ) )
                      ->where( array(
                          'setId'       => (int) $setId,
                      ) )
                      ->order( array(
                          'locale'      => 'ASC',
                          'bannerId'    => 'ASC',
                      ) );

        $result = $this->sql()
                       ->prepareStatementForSqlObject( $select )
                       ->execute();

        if ( $result->getAffectedRows() < 1 )
        {
            return array();
        }

        $ids        = array();
        $locales    = array();
        $return     = array();

        foreach ( $result as $row )
        {
            $locales[$ids[] = (int) $row['bannerId']] = $row['locale'];
        }

        foreach ( $this->getBannerMapper()
                       ->findAllByIds( $ids ) as $banner )
        {
            $locale = $locales[$banner->id];

            if ( empty( $return[$locale] ) )
            {
                $return[$locale] = array();
            }

            $return[$locale][] = $banner;
        }

        return $return;
    }

    /**
     * Find global banners
     *
     * @param   int $setId
     * @return  \Grid\Banner\Model\Banner\StructureInterface[]
     */
    public function findGlobalBanners( $setId )
    {
        $sql = $this->sql(
            $this->getTableInSchema( 'banner_x_set_by_global' )
        );

        $select = $sql->select()
                      ->columns( array( 'bannerId' ) )
                      ->where( array(
                          'setId'       => (int) $setId,
                      ) )
                      ->order( array(
                          'bannerId'    => 'ASC',
                      ) );

        $result = $this->sql()
                       ->prepareStatementForSqlObject( $select )
                       ->execute();

        if ( $result->getAffectedRows() < 1 )
        {
            return array();
        }

        $ids = array();

        foreach ( $result as $row )
        {
            $ids[] = (int) $row['bannerId'];
        }

        return $this->getBannerMapper()
                    ->findAllByIds( $ids );
    }

}
