<?php
return array(                  
    'ebs_options' => array(   
        'epg_options' => array(     
            'company_codes' => array(
                46, 72
            ),
            'company_namespaces' => array(
                'tvplayer', 'vutv'
            )
         ),
    ),
    'controllers' => array(    
        'invokables' => array( 
            'ebs' => 'TvGrabber\Controller\EbsController',
            'hc' => 'TvGrabber\Controller\HCController',
            'bxn' => 'TvGrabber\Controller\BoxNationController',
        ),                     
    ),
    'console' => array(        
        'router' => array(     
            'routes' => array( 
                'import-ebs' => array(         
                    'options' => array(             
                        'route' => '--import-from-ebs [<id>]',
                        'defaults' => array(            
                            'controller' => 'ebs',    
                            'action'     => 'index',        
                        ),     
                    ),
                ),             
                'import-hnc' => array(         
                    'options' => array(             
                        'route' => '--import-horse-and-country',
                        'defaults' => array(            
                            'controller' => 'hc',    
                            'action'     => 'index',        
                        ),     
                    ),
                ), 
                'import-bxn' => array(         
                    'options' => array(             
                        'route' => '--import-box-nation',
                        'defaults' => array(            
                            'controller' => 'bxn',    
                            'action'     => 'index',        
                        ),     
                    ),
                ),
            ),                 
        ),
    ),
);
