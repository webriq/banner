<?php

namespace Grid\Banner\Model\Banner\Structure;

use Grid\Banner\Model\Banner\StructureAbstract;

/**
 * Banner code
 *
 * @author David Pozsar <david.pozsar@megaweb.hu>
 */
class Code extends StructureAbstract
{

    /**
     * Banner type
     *
     * @var string
     */
    protected static $type = 'code';

    /**
     * View-partial
     *
     * @var string
     */
    protected static $viewPartial = 'grid/banner/view/code';

    /**
     * HTML code to display the banner
     *
     * @var string
     */
    protected $code = '';

    /**
     * Setter for code
     *
     * @param   string $code
     * @return  \Grid\Banner\Model\Banner\Structure\Code
     */
    public function setCode( $code )
    {
        $this->code = (string) $code;
        return $this;
    }

}
