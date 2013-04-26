<?php
return array(
    'doctrine' => array(

        'connection' => array(
            'odm_default' => array(
                'server'    => 'localhost',
                'port'      => '27017',
                'user'      => '',
                'password'  => '',
                'dbname'    => '',
                'options'   => array()
            ),
        ),

        'configuration' => array(
            'odm_default' => array(
                'metadata_cache'     => 'array',

                'driver'             => 'odm_default',

                'generate_proxies'   => true,
                'proxy_dir'          => 'data/DoctrineMongoODMModule/Proxy',
                'proxy_namespace'    => 'DoctrineMongoODMModule\Proxy',

                'generate_hydrators' => true,
                'hydrator_dir'       => 'data/DoctrineMongoODMModule/Hydrator',
                'hydrator_namespace' => 'DoctrineMongoODMModule\Hydrator',

                'default_db'         => 'taskmanager',

                'filters'            => array(),  // array('filterName' => 'BSON\Filter\Class'),

                'logger'             => null // 'DoctrineMongoODMModule\Logging\DebugStack'
            )
        ),

        'driver' => array(
        	'odm_driver' => array(
        		'class' => 'Doctrine\ODM\MongoDB\Mapping\Driver\AnnotationDriver',
        		'paths' => array(__DIR__ . '/../src/Application/Domain/Aggregate')
        	),
            'odm_default' => array(
                'drivers' => array(
                	'Application\Domain\Aggregate' => 'odm_driver'
                ),
            ),
        ),

        'documentmanager' => array(
            'odm_default' => array(
                'connection'    => 'odm_default',
                'configuration' => 'odm_default',
                'eventmanager' => 'odm_default'
            )
        ),

        'eventmanager' => array(
            'odm_default' => array(
                'subscribers' => array()
            )
        ),
    ),
);