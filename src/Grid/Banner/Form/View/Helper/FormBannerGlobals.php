<?php

namespace Grid\Banner\Form\View\Helper;

use Zend\Form\Exception;
use Zend\Form\ElementInterface;
use Grid\Banner\Form\Element\GlobalBanners;

/**
 * FormBannerGlobals
 *
 * @author David Pozsar <david.pozsar@megaweb.hu>
 */
class FormBannerGlobals extends FormBannerAbstract
{

    /**
     * @var string
     */
    protected $label = 'banner.form.set.global';

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
        if ( ! $element instanceof GlobalBanners )
        {
            throw new Exception\InvalidArgumentException( sprintf(
                '%s requires that the element is of type Grid\Banner\Form\Element\GlobalBanners',
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
        $label      = $this->label;

        if ( $this->isTranslatorEnabled() && $this->hasTranslator() )
        {
            $label = $this->getTranslator()
                          ->translate( $label,
                                       $this->getTranslatorTextDomain() );
        }

        unset( $attributes['name'] );
        return $this->renderBannerGroups( $name, $attributes, array(
            array(
                'header'        => $label,
                'markup'        => $this->renderBanners( $name, $value ),
                'attributes'    => array(),
            ),
        ) );
    }

}
