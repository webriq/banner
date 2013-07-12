<?php

namespace Grid\Banner\Model\BannerSet;

use Zork\Model\Structure\MapperAwareAbstract;

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
     * Find a banner in this set
     *
     * @param   array   $tagIds
     * @param   string  $locale
     * @return  \Grid\Banner\Model\Banner\Structure
     */
    public function findBanner( array $tagIds, $locale )
    {
        if ( empty( $this->id ) )
        {
            return array();
        }

        return $this->getMapper()
                    ->findBanner( $this->id, $tagIds, $locale );
    }

}
