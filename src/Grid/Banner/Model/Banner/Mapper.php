<?php

namespace Grid\Banner\Model\Banner;

use Zork\Model\Mapper\DbAware\ReadWriteMapperAbstract;
use Zork\Session\ContainerAwareTrait as SessionContainerAwareTrait;

/**
 * Banner mapper
 *
 * @author David Pozsar <david.pozsar@megaweb.hu>
 */
class Mapper extends ReadWriteMapperAbstract
{

    use SessionContainerAwareTrait;

    /**
     * Table name used in all queries
     *
     * @var string
     */
    protected static $tableName = 'banner';

    /**
     * Default column-conversion functions as types;
     * used in selected(), deselect()
     *
     * @var array
     */
    protected static $columns = array(
        'id'    => self::INT,
        'type'  => self::STRING,
    );

    /**
     * @var array
     */
    protected static $previousBlockedIds = null;

    /**
     * Contructor: Sets banner structure prototype
     *
     * @param   \Grid\Banner\Model\Banner\Structure  $bannerStructurePrototype
     */
    public function __construct( Structure $bannerStructurePrototype = null )
    {
        if ( null === static::$previousBlockedIds )
        {
            $session = $this->getSessionContainer();

            if ( empty( $session['blockedIds'] ) )
            {
                static::$previousBlockedIds = array();
            }
            else
            {
                static::$previousBlockedIds = (array) $session['blockedIds'];
            }

            $session['blockedIds'] = array();
        }

        parent::__construct( $bannerStructurePrototype ?: new Structure );
    }

    /**
     * @return  array
     */
    protected function getBlockedIds()
    {
        $session = $this->getSessionContainer();

        return array_merge(
            static::$previousBlockedIds,
            $session['blockedIds']
        );
    }

    /**
     * @param   int $id
     * @return  \Grid\Banner\Model\Banner\Mapper
     */
    protected function addToBlockedIds( $id )
    {
        if ( ! empty( $id ) )
        {
            $session = $this->getSessionContainer();
            $session['blockedIds'][] = (int) $id;
        }

        return $this;
    }

    /**
     * @param   int     $setId
     * @param   array   $tagIds
     * @param   string  $locale
     * @return  \Grid\Banner\Model\Banner\Structure
     */
    public function findRandomBySetIdTagIdsLocale( $setId, array $tagIds, $locale )
    {
        /// TODO: implement

        $blockedIds = $this->getBlockedIds();



        // $banner;
        $this->addToBlockedIds( $banner->id );
        return $banner;
    }

}
