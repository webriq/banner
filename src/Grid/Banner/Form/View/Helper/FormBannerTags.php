<?php

namespace Grid\Banner\Form\View\Helper;

use Zend\Form\Exception;
use Zend\Form\ElementInterface;
use Grid\Banner\Form\Element\TagBanners;

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
        if ( ! $element instanceof TagBanners )
        {
            throw new Exception\InvalidArgumentException( sprintf(
                '%s requires that the element is of type Grid\Banner\Form\Element\TagBanners',
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

        $appService = $this->getAppServiceHelper();
        $tagModel   = $appService( 'Grid\Tag\Model\Tag\Model' );
        $attributes = $element->getAttributes();
        $value      = (array) $element->getValue();
        $groups     = array();

        foreach ( $value as $tagId => $banners )
        {
            $tag = $tagModel->find( $tagId );

            if ( $tag && $tag->locale )
            {
                $locale = 'locale.sub.' . $tag->locale;

                if ( $this->isTranslatorEnabled() && $this->hasTranslator() )
                {
                    $locale = $this->getTranslator()
                                   ->translate( $locale, 'locale' );
                }
            }

            $label = $tag
                ? $tag->name . ( isset( $locale ) ? ' (' . $locale . ')' : '' )
                : '#' . $tagId;

            $groups[] = array(
                'header'        => $label,
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
