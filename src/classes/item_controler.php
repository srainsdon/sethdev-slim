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
    public function __construct(ContainerInterface $container) {
        $this->container = $container;
    }

    function get_item($request, $response, $args) {
        $item_id = (int) $args['id'];
        $this->logger->addInfo("Item: " . $item_id);
        $stmt = $this->pdo->query('SELECT * FROM item where itemID = ' . $id);
        $item = $stmt->fetch();
        $this->logger->addInfo($item);
        return $this->renderer->render($response, 'item.phtml', $item);
    }

}
