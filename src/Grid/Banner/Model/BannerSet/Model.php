<?php

namespace Grid\Banner\Model\BannerSet;

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
        return $this->getMapper()
                    ->getPaginator();
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
