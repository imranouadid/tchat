<?php

    // loads configurations
    require_once 'config/config.php';
    // Loads helpers
    require_once 'helpers/data_validation_helper.php';
    require_once 'helpers/url_helper.php';
    require_once 'helpers/session_helper.php';

    spl_autoload_register(function($className){
        if(file_exists('../app/libraries/'.$className.'.php')){
            require_once 'libraries/'.$className.'.php';
        }elseif (file_exists('../app/models/'.$className.'.php')){
            require_once 'models/'.$className.'.php';
        }
    });

    // initialise core of application
    $init = new Core();

?>