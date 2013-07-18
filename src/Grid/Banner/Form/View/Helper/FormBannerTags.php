<?php

namespace Grid\Banner\Form\View\Helper;

use Zend\Form\Exception;
use Zend\Form\ElementInterface;
use Grid\Banner\Form\Element\LocaleBanners;

/**
 * FormBannerTags
 *
 * @author David Pozsar <david.pozsar@megaweb.hu>
 */
class FormBannerTags extends FormBannerAbstract
{

    /**
     * Render a form checkbox-group element from the provided $element
     *
     * @param   \Zend\Form\ElementInterface $element
     * @throws  \Zend\Form\Exception\InvalidArgumentException
     * @throws  \Zend\Form\Exception\DomainException
     * @return  string
     */
    public function render( ElementInterface $element )
    {
        if ( ! $element instanceof LocaleBanners )
        {
            throw new Exception\InvalidArgumentException( sprintf(
                '%s requires that the element is of type Grid\Banner\Form\Element\LocaleBanners',
                __METHOD__
            ) );
        }

        $name = $element->getName();
        if ( empty( $name ) && $name !== 0 )
        {
            throw new Exception\DomainException( sprintf(
                '%s requires that the element has an assigned name; none discovered',
                __METHOD__
            ) );
        }

        $attributes = $element->getAttributes();
        $value      = (array) $element->getValue();
        $groups     = array();

        foreach ( $value as $tagId => $banners )
        {
            $groups[] = array(
                'header'        => $tagId, /// TODO: tag-name
                'markup'        => $this->renderBanners(
                    $name . '[' . $tagId . ']',
                    $banners
                ),
                'attributes'    => array(
                    'data-tagid'   => $tagId,
                ),
            );
        }

        unset( $attributes['name'] );
        return $this->renderBannerGroups(
            $name . '[__tagid__]',
            $attributes,
            $groups
        );
    }

}
