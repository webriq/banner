<?php

namespace Grid\Banner\Model\Banner\Structure;

use Zork\Model\Structure\MapperAwareAbstract;
use Grid\Banner\Model\Banner\StructureInterface;

/**
 * Structure
 *
 * @author David Pozsar <david.pozsar@megaweb.hu>
 */
class ProxyBase extends MapperAwareAbstract
             implements StructureInterface
{

    /**
     * ID of the banner
     *
     * @val int|null
     */
    protected $id;

    /**
     * Type of the banner
     *
     * @var string|null
     */
    public $type;

    /**
     * Get ID of the banner
     *
     * @return int|null
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get type of the banner
     *
     * @return string|null
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Get service locator
     *
     * @return \Zend\ServiceManager\ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
        return $this->getMapper()
                    ->getServiceLocator();
    }

}
