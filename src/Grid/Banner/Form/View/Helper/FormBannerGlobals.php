<?php

namespace Grid\Banner\Form\View\Helper;

use Zend\Form\Exception;
use Zend\Form\ElementInterface;
use Zend\Form\View\Helper\AbstractHelper;
use Grid\Banner\Form\Element\GlobalBanners;
use Zork\Form\View\Helper\Form as FormHelper;

/**
 * FormBannerGlobals
 *
 * @author David Pozsar <david.pozsar@megaweb.hu>
 */
class FormBannerGlobals extends AbstractHelper
{

    /**
     * @var string
     */
    protected $label = 'banner.form.set.global';

    /**
     * @var \Zork\Form\View\Helper\Form
     */
    protected $formHelper;

    /**
     * @var \Grid\Core\View\Helper\AppService
     */
    protected $appServiceHelper;

    /**
     * @var array
     */
    protected $bannerFormsByType = array();

    /**
     * Attributes valid for the current tag
     *
     * Will vary based on whether a select, option, or optgroup is being rendered
     *
     * @var array
     */
    protected $validTagAttributes;

    /**
     * @var array
     */
    protected $validContainerAttributes = array(
    );

    /**
     * @return  \Zork\Form\View\Helper\Form
     */
    protected function getFormHelper()
    {
        if ( null === $this->formHelper )
        {
            $this->formHelper = method_exists( $this->view, 'plugin' )
                ? $this->view->plugin( 'form' )
                : new FormHelper;
        }

        return $this->formHelper;
    }

    /**
     * @param   string  $type
     * @return  \Zend\Form\Form
     */
    protected function getAppServiceHelper()
    {
        if ( null === $this->appServiceHelper )
        {
            $this->appServiceHelper = $this->view->plugin( 'appService' );
        }

        return $this->appServiceHelper;
    }

    /**
     * @param   string  $type
     * @return  \Zend\Form\Form
     */
    protected function getBannerFormByType( $type )
    {
        if ( empty( $this->bannerFormsByType[$type] ) )
        {
            $appService     = $this->getAppServiceHelper();
            $formService    = $appService( 'Form' );
            $this->bannerFormsByType[$type] = $formService->get(
                'Grid\\Banner\\Type\\' . ucfirst( $type ),
                array( 'type' => $type )
            );
        }

        return clone $this->bannerFormsByType[$type];
    }

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
        $this->validTagAttributes = $this->validContainerAttributes;
        $escape = $this->getEscapeHtmlHelper();

        return sprintf(
            '<fieldset %s><legend>%s</legend><div>%s</div></fieldset>',
            $this->createAttributesString( $attributes ),
            $escape( $label ),
            $this->renderBanners( $name, $value )
        );
    }

    /**
     * Render banners
     *
     * @param   string  $name
     * @param   array   $value
     * @return  string
     */
    protected function renderBanners( $name, array $values = array() )
    {
        $markup = '';
        $helper = $this->getFormHelper();

        foreach ( $values as $index => $banner )
        {
            if ( is_object( $banner ) )
            {
                if ( method_exists( $banner, 'toArray' ) )
                {
                    $banner = $banner->toArray();
                }
                else if ( $banner instanceof \Traversable )
                {
                    $banner = iterator_to_array( $banner );
                }
                else
                {
                    $banner = (array) $banner;
                }
            }

            if ( ! is_array( $banner ) || empty( $banner['type'] ) )
            {
                continue;
            }

            $form = $this->getBannerFormByType( $banner['type'] );
            $id = empty( $banner['id'] ) ? '_' . $index : $banner['id'];

            $markup .= $helper->renderFieldset(
                $form->setData( $banner )
                     ->setName( $name . '[' . $id . ']' )
            );
        }

        return $markup;
    }

    /**
     * Invoke helper as functor
     *
     * Proxies to {@link render()}.
     *
     * @param  ElementInterface|null $element
     * @return string|FormBannerGlobals
     */
    public function __invoke( ElementInterface $element = null )
    {
        if ( ! $element )
        {
            return $this;
        }

        return $this->render( $element );
    }

}
