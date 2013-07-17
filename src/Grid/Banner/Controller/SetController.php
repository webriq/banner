<?php

namespace Grid\Banner\Controller;

use Zork\Stdlib\Message;
use Grid\Core\Controller\AbstractListController;

/**
 * Banner set admin controller
 *
 * @author David Pozsar <david.pozsar@megaweb.hu>
 */
class SetController extends AbstractListController
{

    /**
     * Define rights required to use this controller
     *
     * @var array
     */
    protected $aclRights = array(
        'list' => array(
            'banner' => 'view',
        ),
        'create' => array(
            'banner' => 'create',
        ),
        'edit' => array(
            'banner' => 'edit',
        ),
        'delete' => array(
            'banner' => 'delete',
        ),
    );

    /**
     * Get the list to display admin list.
     *
     * @return \Zend\Paginator\Paginator
     */
    protected function getPaginator()
    {
        return $this->getServiceLocator()
                    ->get( 'Grid\Banner\Model\BannerSet\Model' )
                 // ->setLocale( $this->getAdminLocale() )
                    ->getPaginator();
    }

    /**
     * Controller action to display create form from the configuration and
     * create a new area in model.
     */
    public function createAction()
    {
        $request    = $this->getRequest();
        $locator    = $this->getServiceLocator();
        $model      = $locator->get( 'Grid\Banner\Model\BannerSet\Model' );
        $form       = $locator->get( 'Form' )
                              ->get( 'Grid\Banner\Set' );

        $set = $model->create();

        /* @var $form \Zend\Form\Form */
        $form->setHydrator( $model->getMapper() )
             ->bind( $set );

        if ( $request->isPost() )
        {
            $form->setData( $request->getPost() );

            if ( $form->isValid() && $set->save() )
            {
                $this->messenger()
                     ->add( 'banner.form.set.success',
                            'banner', Message::LEVEL_INFO );

                return $this->redirect()
                            ->toRoute( 'Grid\Banner\Set\List', array(
                                'locale' => (string) $this->locale(),
                            ) );
            }
            else
            {
                $this->messenger()
                     ->add( 'banner.form.set.failed',
                            'banner', Message::LEVEL_ERROR );
            }
        }

        $form->setCancel(
            $this->url()
                 ->fromRoute( 'Grid\Banner\Set\List', array(
                        'locale' => (string) $this->locale(),
                    ) )
        );

        return array(
            'form' => $form,
        );
    }

    /**
     * Controller action to display edit form from the configuration and
     * edit an existing gmap area in model identified by id from uri.
     */
    public function editAction()
    {
        $params     = $this->params();
        $request    = $this->getRequest();
        $locator    = $this->getServiceLocator();
        $model      = $locator->get( 'Grid\Banner\Model\BannerSet\Model' );
        $form       = $locator->get( 'Form' )
                              ->get( 'Grid\Banner\Set' );

        $set = $model->find( $params->fromRoute( 'id' ) );

        if ( empty( $set ) )
        {
            $this->getResponse()
                 ->setStatusCode( 404 );

            return;
        }

        /* @var $form \Zend\Form\Form */
        $form->setHydrator( $model->getMapper() )
             ->bind( $set );

        if ( $request->isPost() )
        {
            $form->setData( $request->getPost() );

            if ( $form->isValid() && $set->save() )
            {
                $this->messenger()
                     ->add( 'banner.form.set.success',
                            'banner', Message::LEVEL_INFO );

                return $this->redirect()
                            ->toRoute( 'Grid\Banner\Set\List', array(
                                'locale' => (string) $this->locale(),
                            ) );
            }
            else
            {
                $this->messenger()
                     ->add( 'banner.form.set.failed',
                            'banner', Message::LEVEL_ERROR );

            }
        }

        $form->setCancel(
            $this->url()
                 ->fromRoute( 'Grid\Banner\Set\List', array(
                        'locale' => (string) $this->locale(),
                    ) )
        );

        return array(
            'form' => $form,
            'set'  => $set,
        );
    }

    /**
     * Delete an existing gmap area in model identified by id from uri and after it
     * redirect to the list action.
     */
    public function deleteAction()
    {
        $params     = $this->params();
        $locator    = $this->getServiceLocator();
        $model      = $locator->get( 'Grid\Banner\Model\BannerSet\Model' );

        $set = $model->find( $params->fromRoute( 'id' ) );

        if ( empty( $set ) )
        {
            $this->getResponse()
                 ->setStatusCode( 404 );

            return;
        }

        if ( $model->delete( $set ) )
        {
            $this->messenger()
                 ->add( 'banner.action.set.delete.success',
                        'banner', Message::LEVEL_INFO );
        }
        else
        {
            $this->messenger()
                 ->add( 'banner.action.set.delete.failed',
                        'banner', Message::LEVEL_INFO );
        }

        return $this->redirect()
                    ->toRoute( 'Grid\Banner\Set\List', array(
                        'locale' => (string) $this->locale()
                    ) );
    }

}
