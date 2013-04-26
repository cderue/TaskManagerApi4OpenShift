<?php

return array(
    'modules' => array(
        'ZendServerGateway',
    	'DoctrineModule',
    	'DoctrineMongoODMModule',
    ),
    'automodules' => array(
        'Application' => __DIR__ . '/../src/Application',
    ),
	'service_manager' => array(
		'factories' => array(
			'Application\Data\UserRepository' => function($sm) {
				$dm = $sm->get('doctrine.documentmanager.odm_default');
				$userRepository = $dm->getRepository('Application\Domain\Aggregate\User');
				return $userRepository;
			},
			'Application\Data\CategoryRepository' => function($sm) {
	 			$dm = $sm->get('doctrine.documentmanager.odm_default');
	 			$categoryRepository = $dm->getRepository('Application\Domain\Aggregate\Category');
	 			return $categoryRepository;
	 		},
	 		'Application\Data\TaskRepository' => function($sm) {
	 			$dm = $sm->get('doctrine.documentmanager.odm_default');
	 			$taskRepository = $dm->getRepository('Application\Domain\Aggregate\Task');
	 			return $taskRepository;
	 		},
		 ),
	),
    'module_listener_options' => array(
        'config_glob_paths'    => array(
            'config/autoload/{,*.}{global,local}.php',
            'config/{,*.}{global,local}.php',
        ),
        'module_paths' => array(
            './',
            './vendor',
        ),
    ),
);
