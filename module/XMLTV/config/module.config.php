<?php
return array(                  
    'xmltv_options' => array(   
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
            'tvp' => 'XMLTV\Controller\EpgController',
        ),                     
    ),
    'console' => array(        
        'router' => array(     
            'routes' => array( 
                'import-transactions' => array(         
                    'options' => array(             
                        'route' => '--import-epg [<id>]',     
                        'defaults' => array(            
                            'controller' => 'tvp',    
                            'action'     => 'index',        
                        ),     
                    ),
                ),             
            ),                 
        ),
    ),
);
