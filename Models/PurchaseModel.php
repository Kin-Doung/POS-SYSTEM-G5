<?php
require_once __DIR__ . '/../Databases/database.php';

class PurchaseModel {
    private $pdo;

    function __construct() {
        $this->pdo = new Database();
    }
}

