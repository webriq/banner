<?php

namespace Grid\Banner\Form\View\Helper;

use Zend\Form\Exception;
use Zork\Form\Element\Locale;
use Zend\Form\ElementInterface;
use Grid\Banner\Form\Element\LocaleBanners;

/**
 * FormBannerLocales
 *
 * @author David Pozsar <david.pozsar@megaweb.hu>
 */
class FormBannerLocales extends FormBannerAbstract
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

        foreach ( $value as $locale => $banners )
        {
            $label = 'locale.sub.' . $locale;

            if ( $this->isTranslatorEnabled() && $this->hasTranslator() )
            {
                $label = $this->getTranslator()
                              ->translate( $label, 'locale' );
            }

            $groups[] = array(
                'header'        => $label,
                'markup'        => $this->renderBanners(
                    $name . '[' . $locale . ']',
                    $banners
                ),
                'attributes'    => array(
                    'data-locale'   => $locale,
                ),
            );
        }

        unset( $attributes['name'] );

        $appService = $this->getAppServiceHelper();
        $attributes['data-locales'] = array_filter(
            $appService( 'Locale' )->getAvailableFlags()
        );

        return $this->renderBannerGroups(
            $name . '[__locale__]',
            $attributes,
            $groups
        );
    }

}
