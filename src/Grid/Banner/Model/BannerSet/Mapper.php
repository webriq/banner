<?php

namespace Grid\Banner\Model\BannerSet;

use Zend\Db\Sql;
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
        $sql = $this->sql( $this->getTableInSchema( 'banner_x_set_by_tag' ) );

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

        $result = $sql->prepareStatementForSqlObject( $select )
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
        $sql = $this->sql( $this->getTableInSchema( 'banner_x_set_by_locale' ) );

        $select = $sql->select()
                      ->columns( array( 'bannerId', 'locale' ) )
                      ->where( array(
                          'setId'       => (int) $setId,
                      ) )
                      ->order( array(
                          'locale'      => 'ASC',
                          'bannerId'    => 'ASC',
                      ) );

        $result = $sql->prepareStatementForSqlObject( $select )
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
        $sql = $this->sql( $this->getTableInSchema( 'banner_x_set_by_global' ) );

        $select = $sql->select()
                      ->columns( array( 'bannerId' ) )
                      ->where( array(
                          'setId'       => (int) $setId,
                      ) )
                      ->order( array(
                          'bannerId'    => 'ASC',
                      ) );

        $result = $sql->prepareStatementForSqlObject( $select )
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

    /**
     * Save element structure to datasource
     *
     * @param   \Grid\Banner\Model\Banner\Structure\ProxyAbstract $structure
     * @return  int Number of affected rows
     */
    public function save( & $structure )
    {
        if ( $structure instanceof Structure )
        {
            $data = $structure->toArray();
        }
        else
        {
            $data = (array) $structure;
        }

        if ( isset( $data['tagBanners'] ) )
        {
            $tagBanners = (array) $data['tagBanners'];
            unset( $data['tagBanners'] );
        }

        if ( isset( $data['localeBanners'] ) )
        {
            $localeBanners = (array) $data['localeBanners'];
            unset( $data['localeBanners'] );
        }

        if ( isset( $data['globalBanners'] ) )
        {
            $globalBanners = (array) $data['globalBanners'];
            unset( $data['globalBanners'] );
        }

        $result = parent::save( $data );

        if ( $result > 0 )
        {
            if ( empty( $structure->id ) )
            {
                $structure->setOption( 'id', $id = $data['id'] );
            }
            else
            {
                $id = $structure->id;
            }

            if ( isset( $tagBanners ) )
            {
                $result += $this->saveTagBanners( $id, $tagBanners );
            }

            if ( isset( $localeBanners ) )
            {
                $result += $this->saveLocaleBanners( $id, $localeBanners );
            }

            if ( isset( $globalBanners ) )
            {
                $result += $this->saveGlobalBanners( $id, $globalBanners );
            }
        }

        return $result;
    }

    /**
     * Save tag banners
     *
     * @param   int     $setId
     * @param   array   $banners
     * @return  int
     */
    protected function saveTagBanners( $setId, array $banners )
    {
        $result = 0;
        $prio   = 1;
        $ids    = array();
        $xids   = array();
        $mapper = $this->getBannerMapper();
        $sqlx   = $this->sql( $this->getTableInSchema( 'banner_set_x_tag' ) );
        $sql    = $this->sql( $this->getTableInSchema( 'banner_x_set_by_tag' ) );

        foreach ( array_reverse( $banners ) as $tagId => $bannerList )
        {
            $update = $sqlx->update()
                           ->set( array(
                               'priority'    => $prio,
                           ) )
                           ->where( array(
                               'setId'       => $setId,
                               'tagId'       => $tagId,
                           ) );

            $rows = $sqlx->prepareStatementForSqlObject( $update )
                         ->execute()
                         ->getAffectedRows();

            if ( $rows < 1 )
            {
                $insert = $sqlx->insert()
                               ->values( array(
                                   'setId'      => $setId,
                                   'tagId'      => $tagId,
                                   'priority'   => $prio,
                               ) );

                $rows = $sqlx->prepareStatementForSqlObject( $insert )
                             ->execute()
                             ->getAffectedRows();
            }

            $result += $rows;
            $prio++;

            $select = $sqlx->select()
                           ->columns( array( 'id' ) )
                           ->where( array(
                               'setId'      => $setId,
                               'tagId'      => $tagId,
                           ) );

            $query = $sqlx->prepareStatementForSqlObject( $select )
                          ->execute();

            $setXTagId = null;

            foreach ( $query as $row )
            {
                $setXTagId = $row['id'];
            }

            if ( null === $setXTagId )
            {
                continue;
            }

            $xids[] = $setXTagId;

            foreach ( $bannerList as $banner )
            {
                $rows = $mapper->save( $banner );

                if ( $rows > 0 )
                {
                    $result += $rows;
                    $id = $ids[] = $banner->id;

                    $select = $sql->select()
                                  ->where( array(
                                      'bannerId'    => $id,
                                      'setXTagId'   => $setXTagId,
                                  ) );

                    $rows = $sql->prepareStatementForSqlObject( $select )
                                ->execute()
                                ->getAffectedRows();

                    if ( $rows < 1 )
                    {
                        $insert = $sql->insert()
                                      ->values( array(
                                          'bannerId'    => $id,
                                          'setXTagId'   => $setXTagId,
                                      ) );

                        $rows = $sql->prepareStatementForSqlObject( $insert )
                                    ->execute()
                                    ->getAffectedRows();
                    }

                    $result += $rows;
                }
            }
        }

        $delete = $sqlx->delete()
                       ->where( array(
                           'setId'   => $setId,
                           new Sql\Predicate\NotIn( 'id', $xids ),
                       ) );

        $result += $sqlx->prepareStatementForSqlObject( $delete )
                        ->execute()
                        ->getAffectedRows();

        return $result;
    }

    /**
     * Save locale banners
     *
     * @param   int     $setId
     * @param   array   $banners
     * @return  int
     */
    protected function saveLocaleBanners( $setId, array $banners )
    {
        $result = 0;
        $ids    = array();
        $mapper = $this->getBannerMapper();
        $sql    = $this->sql( $this->getTableInSchema( 'banner_x_set_by_locale' ) );

        foreach ( $banners as $locale => $bannerList )
        {
            foreach ( $bannerList as $banner )
            {
                $rows = $mapper->save( $banner );

                if ( $rows > 0 )
                {
                    $result += $rows;
                    $id = $ids[] = $banner->id;

                    $update = $sql->update()
                                  ->set( array(
                                      'locale'      => $locale,
                                  ) )
                                  ->where( array(
                                      'bannerId'    => $id,
                                      'setId'       => $setId,
                                  ) );

                    $rows = $sql->prepareStatementForSqlObject( $update )
                                ->execute()
                                ->getAffectedRows();

                    if ( $rows < 1 )
                    {
                        $insert = $sql->insert()
                                      ->values( array(
                                          'bannerId'    => $id,
                                          'setId'       => $setId,
                                          'locale'      => $locale,
                                      ) );

                        $rows = $sql->prepareStatementForSqlObject( $insert )
                                    ->execute()
                                    ->getAffectedRows();
                    }

                    $result += $rows;
                }
            }
        }

        $delete = $sql->delete()
                      ->where( array(
                          'setId'   => $setId,
                          new Sql\Predicate\NotIn( 'bannerId', $ids ),
                      ) );

        $result += $sql->prepareStatementForSqlObject( $delete )
                       ->execute()
                       ->getAffectedRows();

        return $result;
    }

    /**
     * Save global banners
     *
     * @param   int     $setId
     * @param   array   $banners
     * @return  int
     */
    protected function saveGlobalBanners( $setId, array $banners )
    {
        $result = 0;
        $ids    = array();
        $mapper = $this->getBannerMapper();
        $sql    = $this->sql( $this->getTableInSchema( 'banner_x_set_by_global' ) );

        foreach ( $banners as $banner )
        {
            $rows = $mapper->save( $banner );

            if ( $rows > 0 )
            {
                $result += $rows;
                $id = $ids[] = $banner->id;

                $select = $sql->select()
                              ->where( array(
                                  'bannerId'    => $id,
                                  'setId'       => $setId,
                              ) );

                $rows = $sql->prepareStatementForSqlObject( $select )
                            ->execute()
                            ->getAffectedRows();

                if ( $rows < 1 )
                {
                    $insert = $sql->insert()
                                  ->values( array(
                                      'bannerId'    => $id,
                                      'setId'       => $setId,
                                  ) );

                    $rows = $sql->prepareStatementForSqlObject( $insert )
                                ->execute()
                                ->getAffectedRows();
                }

                $result += $rows;
            }
        }

        $delete = $sql->delete()
                      ->where( array(
                          'setId'   => $setId,
                          new Sql\Predicate\NotIn( 'bannerId', $ids ),
                      ) );

        $result += $sql->prepareStatementForSqlObject( $delete )
                       ->execute()
                       ->getAffectedRows();

        return $result;
    }

}
