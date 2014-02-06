<?php
return array(
    'routes' => array(
        'home' => array(
            'type' => 'literal',
            'options' => array(
                'route'    => '/',
                'defaults' => array(
                    'controller' => 'epg-index',
                    'action' => 'index',
                ),
            ),
        ),
        'epg-schedule' => array(
            'verb' => 'get',
            'type' => 'segment',
            'options' => array(
                'route'    => '/:service/programmes/schedules[/:outlet]',
                'defaults' => array(
                    'controller' => 'epg-rest-epg',
                ),
            ),
        ), 
        'epg-schedule-tomorrow' => array(
            'verb' => 'get',
            'type' => 'segment',
            'options' => array(
                'route'    => '/:service/programmes/schedules[/:outlet]/tomorrow',
                'defaults' => array(
                    'controller' => 'epg-rest-epg',
                ),
            ),
        ), 
        'epg-schedule-yesterday' => array(
            'verb' => 'get',
            'type' => 'segment',
            'options' => array(
                'route'    => '/:service/programmes/schedules[/:outlet]/yesterday',
                'defaults' => array(
                    'controller' => 'epg-rest-epg',
                ),
            ),
        ),
        'epg-schedule-date' => array(
            'verb' => 'get',
            'type' => 'segment',
            'options' => array(
                'route'    => '/:service/programmes/schedule[/:outlet]/:year/:month/:day',
                'defaults' => array(
                    'controller' => 'epg-rest-epg',
                ),
            ),
        ),

    ),
);
