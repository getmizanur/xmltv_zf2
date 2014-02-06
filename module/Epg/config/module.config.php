<?php
return array(
    'router' => include 'routes.config.php',
    'iodocs' => include 'iodocs.config.php',
    'errors' => array (
        'show_exception' => array(
            'message' => true,
            'trace' => true,
        )
    ), 
    'service_manager' => array(
        'factories' => array(  
            'default_navigation' => 
                'Zend\Navigation\Service\DefaultNavigationFactory',
            'translator' => 
                'Zend\I18n\Translator\TranslatorServiceFactory',
        ),                     
    ),
    'controllers' => array(
        'invokables' => array(
            'epg-index' => 'Epg\Controller\IndexController',
            'epg-rest-epg' => 'Epg\Controller\Restful\EpgController',
        ),
    ),
    'controller_plugins' => array(
        'invokables' => array(
            'flashmessenger' => 'Mm\Mvc\Controller\Plugin\FlashMessenger',
        ),
    ),
    'view_helpers' => array(
        'invokables' => array(
            'messages' => 'Mm\View\Helper\FlashMessenger',
            'accordion' => 'Mm\View\Helper\IoDocs',
            'escape' => 'Zend\View\Helper\Escape',
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/exception',
        'layout'                   => 'layout/master',
        'template_map' => array(
            'layout/front' => __DIR__ . '/../view/layout/master.phtml',
            'error/404' => __DIR__ . '/../view/error/404.phtml',
            'error/exception' => __DIR__ . '/../view/error/exception.phtml'
        ),
        'template_path_stack' => array(
            'view' => __DIR__ . '/../view',
        ),
        'strategies' => array(
            'ViewJsonStrategy',
        ),
    ),
   //'view_manager' => array(
        //'template_path_stack' => array(
            //__DIR__ . '/../view',
        //),
        //'strategies' => array(
            //'ViewJsonStrategy',
        //),
    //),
    'navigation' => array(     
        'default' => array(    
            'home' => array(
                'type' => 'mvc', 
                'route' => 'home',
                'active'=>true, 
                'label' => 'Home'
            ), 
        ),
    ),
);
