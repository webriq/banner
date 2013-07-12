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
     * @param   int     $setId
     * @param   array   $tagIds
     * @param   string  $locale
     * @return  \Grid\Banner\Model\Banner\Structure
     */
    public function findBanner( $setId, array $tagIds, $locale )
    {
        return $this->getBannerMapper()
                    ->findBySetId( $setId, $tagIds, $locale );
    }

}
