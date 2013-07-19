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
                ''              => 'Grid\Banner\Model\Banner\Structure\DefaultFallback',
                'code'          => 'Grid\Banner\Model\Banner\Structure\Code',
                'externalImage' => 'Grid\Banner\Model\Banner\Structure\ExternalImage',
                'image'         => 'Grid\Banner\Model\Banner\Structure\Image',
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
                                        'label'     => 'banner.form.paragraph.setId',
                                        'required'  => true,
                                        'model'     => 'Grid\Banner\Model\BannerSet\Model',
                                        'method'    => 'findOptions',
                                    ),
                                ),
                            ),
                            'priorityMul' => array(
                                'spec' => array(
                                    'type'  => 'Zork\Form\Element\Range',
                                    'name'  => 'priorityMul',
                                    'options'   => array(
                                        'label'     => 'banner.form.paragraph.priorityMul',
                                        'required'  => true,
                                        'min'       => 0,
                                        'max'       => 1,
                                        'step'      => 0.01,
                                    ),
                                    'attributes'    => array(
                                        'data-js-type'  => 'js.banner.priorityMul',
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
                'name'  => array(
                    'spec' => array(
                        'type'  => 'Zork\Form\Element\Text',
                        'name'  => 'name',
                        'options'   => array(
                            'label'     => 'banner.form.set.name',
                            'required'  => true,
                        ),
                    ),
                ),
                'tagBanners' => array(
                    'spec'      => array(
                        'type'  => 'Grid\Banner\Form\Element\TagBanners',
                        'name'  => 'tagBanners',
                        'options'   => array(
                            'label'     => 'banner.form.set.tagBanners',
                            'required'  => false,
                        ),
                    ),
                ),
                'localeBanners' => array(
                    'spec'      => array(
                        'type'  => 'Grid\Banner\Form\Element\LocaleBanners',
                        'name'  => 'localeBanners',
                        'options'   => array(
                            'label'     => 'banner.form.set.localeBanners',
                            'required'  => false,
                        ),
                    ),
                ),
                'globalBanners' => array(
                    'spec'      => array(
                        'type'  => 'Grid\Banner\Form\Element\GlobalBanners',
                        'name'  => 'globalBanners',
                        'options'   => array(
                            'label'     => 'banner.form.set.globalBanners',
                            'required'  => false,
                        ),
                    ),
                ),
                'submit' => array(
                    'spec' => array(
                        'type'  => 'Zork\Form\Element\Submit',
                        'name'  => 'submit',
                        'attributes'  => array(
                            'value' => 'banner.form.set.submit',
                        ),
                    ),
                ),
            ),
        ),
        'Grid\Banner\Type\Code' => array(
            'elements'  => array(
                'id'    => array(
                    'spec' => array(
                        'type'  => 'Zork\Form\Element\Hidden',
                        'name'  => 'id',
                        'options'   => array(
                            'required'  => false,
                        ),
                    ),
                ),
                'type'  => array(
                    'spec' => array(
                        'type'  => 'Zork\Form\Element\Hidden',
                        'name'  => 'type',
                        'options'   => array(
                            'required'  => true,
                        ),
                        'attributes'    => array(
                            'value'     => 'code',
                        ),
                    ),
                ),
                'code'  => array(
                    'spec' => array(
                        'type'  => 'Zork\Form\Element\Textarea',
                        'name'  => 'code',
                        'options'   => array(
                            'label'     => 'banner.form.code.code',
                            'required'  => true,
                        ),
                    ),
                ),
            ),
        ),
        'Grid\Banner\Type\ExternalImage' => array(
            'elements'  => array(
                'id'    => array(
                    'spec' => array(
                        'type'  => 'Zork\Form\Element\Hidden',
                        'name'  => 'id',
                        'options'   => array(
                            'required'  => false,
                        ),
                    ),
                ),
                'type'  => array(
                    'spec' => array(
                        'type'  => 'Zork\Form\Element\Hidden',
                        'name'  => 'type',
                        'options'   => array(
                            'required'  => true,
                        ),
                        'attributes'    => array(
                            'value'     => 'externalImage',
                        ),
                    ),
                ),
                'url' => array(
                    'spec' => array(
                        'type'  => 'Zork\Form\Element\Url',
                        'name'  => 'url',
                        'options'   => array(
                            'label'     => 'banner.form.image.url',
                            'required'  => true,
                        ),
                    ),
                ),
                'alternate' => array(
                    'spec'  => array(
                        'type'  => 'Zork\Form\Element\Text',
                        'name'  => 'alternate',
                        'options'   => array(
                            'label'     => 'banner.form.image.alternate',
                            'required'  => true,
                        ),
                    ),
                ),
                'linkTo'    => array(
                    'spec'  => array(
                        'type'  => 'Zork\Form\Element\Text',
                        'name'  => 'linkTo',
                        'options'   => array(
                            'label'     => 'banner.form.image.linkTo',
                            'required'  => false,
                        ),
                    ),
                ),
                'linkTarget'    => array(
                    'spec'      => array(
                        'type'      => 'Zork\Form\Element\Select',
                        'name'      => 'linkTarget',
                        'options'   => array(
                            'label'     => 'banner.form.image.linkTarget',
                            'required'  => false,
                            'options'   => array(
                                ''          => 'default.link.target.default',
                                '_self'     => 'default.link.target.self',
                                '_blank'    => 'default.link.target.blank',
                                '_parent'   => 'default.link.target.parent',
                                '_top'      => 'default.link.target.top',
                            ),
                            'text_domain' => 'default',
                        ),
                    ),
                ),
                'width'     => array(
                    'spec'  => array(
                        'type'  => 'Zork\Form\Element\Number',
                        'name'  => 'width',
                        'options'   => array(
                            'label'     => 'banner.form.image.width',
                            'required'  => false,
                            'min'       => 50,
                            'max'       => 1000,
                        ),
                    ),
                ),
                'height'    => array(
                    'spec'  => array(
                        'type'  => 'Zork\Form\Element\Number',
                        'name'  => 'height',
                        'options'   => array(
                            'label'     => 'banner.form.image.height',
                            'required'  => false,
                            'min'       => 50,
                            'max'       => 1000,
                        ),
                    ),
                ),
            ),
        ),
        'Grid\Banner\Type\Image' => array(
            'elements'  => array(
                'id'    => array(
                    'spec' => array(
                        'type'  => 'Zork\Form\Element\Hidden',
                        'name'  => 'id',
                        'options'   => array(
                            'required'  => false,
                        ),
                    ),
                ),
                'type'  => array(
                    'spec' => array(
                        'type'  => 'Zork\Form\Element\Hidden',
                        'name'  => 'type',
                        'options'   => array(
                            'required'  => true,
                        ),
                        'attributes'    => array(
                            'value'     => 'image',
                        ),
                    ),
                ),
                'url'   => array(
                    'spec'  => array(
                        'type'      => 'Zork\Form\Element\PathSelect',
                        'name'      => 'url',
                        'options'   => array(
                            'label'     => 'banner.form.image.url',
                            'required'  => true,
                        ),
                    ),
                ),
                'alternate' => array(
                    'spec'  => array(
                        'type'  => 'Zork\Form\Element\Text',
                        'name'  => 'alternate',
                        'options'   => array(
                            'label'     => 'banner.form.image.alternate',
                            'required'  => true,
                        ),
                    ),
                ),
                'linkTo'    => array(
                    'spec'  => array(
                        'type'  => 'Zork\Form\Element\Text',
                        'name'  => 'linkTo',
                        'options'   => array(
                            'label'     => 'banner.form.image.linkTo',
                            'required'  => false,
                        ),
                    ),
                ),
                'linkTarget'    => array(
                    'spec'      => array(
                        'type'      => 'Zork\Form\Element\Select',
                        'name'      => 'linkTarget',
                        'options'   => array(
                            'label'     => 'banner.form.image.linkTarget',
                            'required'  => false,
                            'options'   => array(
                                ''          => 'default.link.target.default',
                                '_self'     => 'default.link.target.self',
                                '_blank'    => 'default.link.target.blank',
                                '_parent'   => 'default.link.target.parent',
                                '_top'      => 'default.link.target.top',
                            ),
                            'text_domain' => 'default',
                        ),
                    ),
                ),
                'method'    => array(
                    'spec'  => array(
                        'type'      => 'Zork\Form\Element\Select',
                        'name'      => 'method',
                        'options'   => array(
                            'label'         => 'banner.form.image.method',
                            'required'      => true,
                            'text_domain'   => 'image',
                            'options'       => array(
                                'fit'       => 'image.method.fit',
                                'frame'     => 'image.method.frame',
                                'cut'       => 'image.method.cut',
                                'stretch'   => 'image.method.stretch',
                            ),
                        ),
                    ),
                ),
                'width'     => array(
                    'spec'  => array(
                        'type'  => 'Zork\Form\Element\Number',
                        'name'  => 'width',
                        'options'   => array(
                            'label'     => 'banner.form.image.width',
                            'required'  => true,
                            'min'       => 50,
                            'max'       => 1000,
                        ),
                        'attributes'    => array(
                            'value'     => 100,
                        ),
                    ),
                ),
                'height'    => array(
                    'spec'  => array(
                        'type'  => 'Zork\Form\Element\Number',
                        'name'  => 'height',
                        'options'   => array(
                            'label'     => 'banner.form.image.height',
                            'required'  => true,
                            'min'       => 50,
                            'max'       => 1000,
                        ),
                        'attributes'    => array(
                            'value'     => 100,
                        ),
                    ),
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'template_map' => array(
            'grid/banner/set/create'            => __DIR__ . '/../view/grid/banner/set/create.phtml',
            'grid/banner/set/edit'              => __DIR__ . '/../view/grid/banner/set/edit.phtml',
            'grid/banner/set/list'              => __DIR__ . '/../view/grid/banner/set/list.phtml',
            'grid/banner/view/code'             => __DIR__ . '/../view/grid/banner/view/code.phtml',
            'grid/banner/view/defaultFallback'  => __DIR__ . '/../view/grid/banner/view/defaultFallback.phtml',
            'grid/banner/view/externalImage'    => __DIR__ . '/../view/grid/banner/view/externalImage.phtml',
            'grid/banner/view/image'            => __DIR__ . '/../view/grid/banner/view/image.phtml',
            'grid/paragraph/render/banner'      => __DIR__ . '/../view/grid/paragraph/render/banner.phtml',
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
        'Grid\Core'  => array(
            'navigation'    => array(
                'function'  => array(
                    'label'         => 'admin.navTop.function',
                    'textDomain'    => 'admin',
                    'order'         => 5,
                    'uri'           => '#',
                    'parentOnly'    => true,
                    'pages'         => array(
                        'banner'    => array(
                            'label'         => 'banner.navTop.set',
                            'textDomain'    => 'banner',
                            'uri'           => '#',
                            'order'         => 2,
                            'parentOnly'    => true,
                            'pages'         => array(
                                'list'      => array(
                                    'label'         => 'banner.navTop.setList',
                                    'textDomain'    => 'banner',
                                    'route'         => 'Grid\Banner\Set\List',
                                    'order'         => 1,
                                    'resource'      => 'banner',
                                    'privilege'     => 'view',
                                ),
                                'create'    => array(
                                    'label'         => 'banner.navTop.setCreate',
                                    'textDomain'    => 'banner',
                                    'route'         => 'Grid\Banner\Set\Create',
                                    'order'         => 2,
                                    'resource'      => 'banner',
                                    'privilege'     => 'create',
                                ),
                            ),
                        ),
                    ),
                ),
            ),
        ),
        'Grid\Paragraph' => array(
            'customizeMapForms' => array(
                'banner' => array(
                    'element' => 'general',
                ),
            ),
        ),
    ),
);
