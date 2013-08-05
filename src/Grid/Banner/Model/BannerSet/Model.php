<?php

namespace Grid\Banner\Model\BannerSet;

use Zend\Db\Sql\Select;
use Zend\Db\Sql\Expression;
use Zork\Model\MapperAwareTrait;
use Zork\Model\MapperAwareInterface;

/**
 * Banner-set model
 *
 * @author David Pozsar <david.pozsar@megaweb.hu>
 */
class Model implements MapperAwareInterface
{

    use MapperAwareTrait;

    /**
     * Construct model: sets the mapper
     *
     * @param   \Grid\Banner\Model\BannerSet\Mapper $bannerSetMapper
     */
    public function __construct( Mapper $bannerSetMapper )
    {
        $this->setMapper( $bannerSetMapper );
    }

    /**
     * Get paginator for listing
     *
     * @return \Zend\Paginator\Paginator
     */
    public function getPaginator()
    {
        $xTag       = new Select( 'banner_x_set_by_tag' );
        $xLocale    = new Select( 'banner_x_set_by_locale' );
        $xGlobal    = new Select( 'banner_x_set_by_global' );

        $xTag->join( 'banner_set_x_tag',
                     'banner_set_x_tag.id = banner_x_set_by_tag.setXTagId',
                     array( 'setId' ),
                     Select::JOIN_INNER )
             ->join( 'tag',
                     'tag.id = banner_set_x_tag.tagId',
                     array(),
                     Select::JOIN_INNER )
             ->group( 'banner_set_x_tag.setId' )
             ->columns( array(
                 'tags' => new Expression(
                     'STRING_AGG( DISTINCT ?.?, ? )',
                     array( 'tag', 'name', "\n" ),
                     array( Expression::TYPE_IDENTIFIER,
                            Expression::TYPE_IDENTIFIER,
                            Expression::TYPE_VALUE )
                 ),
             ) );

        $xLocale->group( 'banner_x_set_by_locale.setId' )
                ->columns( array(
                    'setId',
                    'locales' => new Expression(
                        'STRING_AGG( DISTINCT ?, ? )',
                        array( 'locale', "\n" ),
                        array( Expression::TYPE_IDENTIFIER,
                               Expression::TYPE_VALUE )
                    ),
                ) );

        $xGlobal->group( 'banner_x_set_by_global.setId' )
                ->columns( array(
                    'setId',
                    'globals' => new Expression( 'COUNT(*) > 0' ),
                ) );

        return $this->getMapper()
                    ->getPaginator(
                        null,
                        null,
                        null,
                        array(
                            'x_tag' => array(
                                'table'     => array( 'x_tag' => $xTag ),
                                'where'     => 'x_tag.setId = banner_set.id',
                                'columns'   => array( 'tags' ),
                                'type'      => Select::JOIN_LEFT,
                            ),
                            'x_locale' => array(
                                'table'     => array( 'x_locale' => $xLocale ),
                                'where'     => 'x_locale.setId = banner_set.id',
                                'columns'   => array( 'locales' ),
                                'type'      => Select::JOIN_LEFT,
                            ),
                            'x_global' => array(
                                'table'     => array( 'x_global' => $xGlobal ),
                                'where'     => 'x_global.setId = banner_set.id',
                                'columns'   => array( 'globals' ),
                                'type'      => Select::JOIN_LEFT,
                            ),
                        )
                    );
    }

    /**
     * Find element by primary keys
     *
     * @param   int $id
     * @return  \Grid\Banner\Model\BannerSet\Structure
     */
    public function find( $id )
    {
        return $this->getMapper()
                    ->find( $id );
    }

    /**
     * Find sets as "$id" => "$name" pairs
     *
     * @param  string|null       $schema
     * @return array
     */
    public function findOptions()
    {
        $mapper = $this->getMapper();

        return $mapper->findOptions(
            array(
                'value' => 'id',
                'label' => 'name',
            ),
            array(),
            array(
                'name'  => 'ASC',
            )
        );
    }

    /**
     * Create structure from plain data
     *
     * @param   array|\Traversable $data
     * @return  \Grid\Banner\Model\BannerSet\Structure
     */
    public function create( $data = null )
    {
        return $this->getMapper()
                    ->create( $data ?: array() );
    }

    /**
     * Save method of Model class
     *
     * @param   \Grid\Banner\Model\BannerSet\Structure $set
     * @return  int
     */
    public function save( Structure $set )
    {
        return $this->getMapper()
                    ->save( $set );
    }

    /**
     * Delete a set structure by its id or structure
     *
     * @param   int|\Grid\Banner\Model\BannerSet\Structure $set
     * @return  int
     */
    public function delete( $set )
    {
        return $this->getMapper()
                    ->delete( $set );
    }

}
