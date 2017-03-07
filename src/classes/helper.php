<?php

use Interop\Container\ContainerInterface;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of nunet_helper
 *
 * @author srainsdon
 */

namespace nunet;

class helper {

    protected $container;

    //put your code here
    public function __construct(ContainerInterface $container) {
        $this->container = $container;
        $container->logger->addInfo("Class: " . __CLASS__);
    }
    public function get_app_data($request, $response, $args) {
        
    }
}
