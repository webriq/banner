<?php

namespace Grid\Banner\Form\View\Helper;

use Zend\Form\ElementInterface;
use Zend\Form\View\Helper\AbstractHelper;
use Zork\Form\View\Helper\Form as FormHelper;

/**
 * FormBannerGlobals
 *
 * @author David Pozsar <david.pozsar@megaweb.hu>
 */
abstract class FormBannerAbstract extends AbstractHelper
{

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
     * Render banner groups
     *
     * @param   string  $name
     * @param   array   $attributes
     * @param   array   $groupMarkups
     * @return  string
     */
    public function renderBannerGroups( $name,
                                        $attributes,
                                        array $groupMarkups )
    {
        /* @var $factory \Grid\Banner\Model\Banner\StructureFactory */
        $appService     = $this->getAppServiceHelper();
        $escapeHtml     = $this->getEscapeHtmlHelper();
        $escapeHtmlAttr = $this->getEscapeHtmlAttrHelper();
        $factory        = $appService( 'Grid\Banner\Model\Banner\StructureFactory' );

        $templatesMarkup = '';
        foreach ( $factory->getRegisteredAdapters() as $type => $_ )
        {
            if ( empty( $type ) )
            {
                continue; // fallback
            }

            if ( $templatesMarkup )
            {
                $templatesMarkup .= PHP_EOL;
            }

            $templatesMarkup .= sprintf(
                '<span class="type-template" data-type="%s" data-template="%s"></span>',
                $escapeHtmlAttr( $type ),
                $escapeHtmlAttr( $this->renderTypeForm( $type, $name . '[__index__]' ) )
            );
        }

        $groupsMarkup = '';
        foreach ( $groupMarkups as $group )
        {
            if ( $groupsMarkup )
            {
                $groupsMarkup .= PHP_EOL;
            }

            $groupsMarkup .= sprintf(
                '<div class="banner-group" %s>' . PHP_EOL .
                    '<div class="banner-group-header">%s</div>' . PHP_EOL .
                    '<div class="banner-group-banners">%s</div>' . PHP_EOL .
                '</div>',
                $this->createAttributesString( $group['attributes'] ),
                $escapeHtml( $group['header'] ),
                $group['markup']
            );
        }

        $hiddenMarkup .= sprintf(
            '<input type="hidden" name="%s" value="" />',
            $escapeHtmlAttr( preg_replace( '/\\[__[a-zA-Z0-9]+__\\]$/', '', $name ) )
        );

        $this->validTagAttributes = $this->validContainerAttributes;
        return sprintf(
            '<div %s>' . PHP_EOL . '%s' . PHP_EOL . '%s' . PHP_EOL . '%s' .PHP_EOL . '</div>',
            $this->createAttributesString( $attributes ),
            $hiddenMarkup,
            $groupsMarkup,
            $templatesMarkup
        );
    }

    /**
     * Render a form banners element from the provided $element
     *
     * @param   \Zend\Form\ElementInterface $element
     * @throws  \Zend\Form\Exception\InvalidArgumentException
     * @throws  \Zend\Form\Exception\DomainException
     * @return  string
     */
    abstract public function render( ElementInterface $element );

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

            $id = empty( $banner['id'] ) ? '_' . $index : $banner['id'];

            $markup .= $this->renderTypeForm(
                $banner['type'],
                $name . '[' . $id . ']',
                $banner
            );
        }

        return $markup;
    }

    /**
     * Render type form
     *
     * @param   string  $type
     * @param   string  $name
     * @param   array   $data
     * @return  string
     */
    protected function renderTypeForm( $type, $name, array $data = array() )
    {
        $formHelper         = $this->getFormHelper();
        $escapeHelper       = $this->getEscapeHtmlHelper();
        $escapeAttrHelper   = $this->getEscapeHtmlAttrHelper();
        $form               = $this->getBannerFormByType( $type );
        $title              = 'banner.type.' . $type;

        if ( $this->isTranslatorEnabled() && $this->hasTranslator() )
        {
            $title = $this->getTranslator()
                          ->translate( $title, 'banner' );
        }

        return sprintf(
            '<div class="banner banner-%s">' . PHP_EOL .
                '<div class="banner-title">%s</div>' . PHP_EOL .
                '%s' . PHP_EOL .
            '</div>',
            $escapeAttrHelper( $type ),
            $escapeHelper( $title ),
            $formHelper->renderFieldset(
                $form->setData( $data )
                     ->setWrapElements( true )
                     ->setName( $name )
                     ->prepare()
            )
        );
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
