<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of item_mapper
 *
 * @author srainsdon
 */
class item_mapper {

    private $pdo;

    function __construct($pdo) {
        $this->pdo = $pdo;
    }

    function get_item($id) {
        $stmt = $this->pdo->query('SELECT * FROM item where itemID = ' . $id);
        $item = $stmt->fetch();
        return $item;
    }

}
