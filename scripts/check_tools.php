<?php
require_once 'app/config/config.php';
require_once 'app/config/database.php';
require_once 'app/core/Database.php';
$db = new Database();
$db->query('DESCRIBE tools');
$cols = $db->resultSet();
foreach ($cols as $col) {
    echo $col->Field . "\n";
}
