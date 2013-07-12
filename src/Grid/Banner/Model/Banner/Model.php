<?php

namespace Grid\Banner\Model\Banner;

use Zork\Model\MapperAwareTrait;
use Zork\Model\MapperAwareInterface;

/**
 * Banner model
 *
 * @author David Pozsar <david.pozsar@megaweb.hu>
 */
class Model implements MapperAwareInterface
{

    use MapperAwareTrait;

    /**
     * Construct model: sets the mapper
     *
     * @param   \Grid\Banner\Model\Banner\Mapper $bannerSetMapper
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
     * @return  \Grid\Banner\Model\Banner\Structure
     */
    public function find( $id )
    {
        return $this->getMapper()
                    ->find( $id );
    }

    /**
     * Create structure from plain data
     *
     * @param   array|\Traversable $data
     * @return  \Grid\Banner\Model\Banner\Structure
     */
    public function create( $data = null )
    {
        return $this->getMapper()
                    ->create( $data ?: array() );
    }

    /**
     * Save method of Model class
     *
     * @param   \Grid\Banner\Model\Banner\Structure $set
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
     * @param   int|\Grid\Banner\Model\Banner\Structure $set
     * @return  int
     */
    public function delete( $set )
    {
        return $this->getMapper()
                    ->delete( $set );
    }

}
