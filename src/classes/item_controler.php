<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Item_Controler
 *
 * @author srainsdon
 */
class item_controler {

    protected $container;

    // constructor receives container instance
    public function __construct(Interop\Container\ContainerInterface  $container) {
        $this->container = $container;
    }

    function get_item($request, $response, $args) {
        $item_id = (int) $args['id'];
        $this->container->logger->addInfo("Item: " . $item_id);
        $stmt = $this->container->db->query('SELECT * FROM item where itemID = ' . $item_id);
        $item = $stmt->fetch();
        $this->container->logger->addInfo(var_export($item, true));
        return $this->container->renderer->render($response, 'item.phtml', $item);
    }

}
