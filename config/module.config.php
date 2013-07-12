<?php

return array(
    'router' => array(
        'routes' => array(
            'Grid\Banner\Set\Create' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/app/:locale/admin/banner/set/create',
                    'defaults' => array(
                        'controller' => 'Grid\Banner\Controller\Set',
                        'action'     => 'create',
                    ),
                ),
            ),
            'Grid\Banner\Set\List' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/app/:locale/admin/banner/set/list',
                    'defaults' => array(
                        'controller' => 'Grid\Banner\Controller\Set',
                        'action'     => 'list',
                    ),
                ),
            ),
            'Grid\Banner\Set\Delete' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'         => '/app/:locale/admin/banner/set/delete/:id',
                    'constraints'   => array(
                        'id'        => '[1-9][0-9]*',
                    ),
                    'defaults'      => array(
                        'controller'    => 'Grid\Banner\Controller\Set',
                        'action'        => 'delete',
                    ),
                ),
            ),
            'Grid\Banner\Set\Edit' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'         => '/app/:locale/admin/banner/set/edit/:id',
                    'constraints'   => array(
                        'id'        => '[1-9][0-9]*',
                    ),
                    'defaults'      => array(
                        'controller'    => 'Grid\Banner\Controller\Set',
                        'action'        => 'edit',
                    ),
                ),
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Grid\Banner\Controller\Set' => 'Grid\Banner\Controller\SetController',
        ),
    ),
    'factory' => array(
        'Grid\Paragraph\Model\Paragraph\StructureFactory' => array(
            'adapter' => array(
                'banner' => 'Grid\Banner\Model\Paragraph\Structure\Banner',
            ),
        ),
        'Grid\Banner\Model\Banner\StructureFactory' => array(
            'dependency' => array(
                'Grid\Banner\Model\Banner\StructureInterface',
            ),
            'adapter'   => array(
                ''      => 'Grid\Banner\Model\Banner\Structure\DefaultFallback',
                'code'  => 'Grid\Banner\Model\Banner\Structure\Code',
                'image' => 'Grid\Banner\Model\Banner\Structure\Image',
            ),
        ),
    ),
    'form' => array(
        'Grid\Paragraph\CreateWizard\Start' => array(
            'elements'  => array(
                'type'  => array(
                    'spec'  => array(
                        'options'   => array(
                            'options'   => array(
                                'code'  => array(
                                    'options' => array(
                                        'banner' => 'paragraph.type.banner',
                                    ),
                                ),
                            ),
                        ),
                    ),
                ),
            ),
        ),
        'Grid\Paragraph\Meta\Edit' => array(
            'fieldsets' => array(
                'banner' => array(
                    'spec' => array(
                        'name'      => 'banner',
                        'options'   => array(
                            'label'     => 'paragraph.type.banner',
                            'required'  => false,
                        ),
                        'elements'  => array(
                            'setId' => array(
                                'spec' => array(
                                    'type'  => 'Zork\Form\Element\SelectModel',
                                    'name'  => 'setId',
                                    'options'   => array(
                                        'label'     => 'paragraph.form.banner.set',
                                        'required'  => true,
                                        'model'     => 'Grid\Banner\Model\BannerSet\Model',
                                        'method'    => 'findOptions',
                                    ),
                                ),
                            ),
                        ),
                    ),
                ),
            ),
        ),
        'Grid\Banner\Set' => array(
            'elements'  => array(
                'name' => array(
                    'spec' => array(
                        'type'  => 'Zork\Form\Element\Text',
                        'name'  => 'name',
                        'options'   => array(
                            'label'     => 'banner.form.name',
                            'required'  => true,
                        ),
                    ),
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'template_map' => array(
            'grid/paragraph/render/banner' => __DIR__ . '/../view/grid/paragraph/render/banner.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    'translator' => array(
        'translation_file_patterns' => array(
            'embed' => array(
                'type'          => 'phpArray',
                'base_dir'      => __DIR__ . '/../languages/banner',
                'pattern'       => '%s.php',
                'text_domain'   => 'banner',
            ),
        ),
    ),
    'modules'   => array(
        'Grid\Paragraph' => array(
            'customizeMapForms' => array(
                'banner' => array(
                    'element' => 'general',
                ),
            ),
        ),
    ),
);
