<?php
function getTableColumns() {
    global $dbConn;
    $sql = "SHOW COLUMNS FROM user";
    $stmt = $dbConn->query($sql);
    $rows = $stmt->fetchAll();

    return $rows;
}

function getUsers() {
    global $dbConn;
    $sql = "SELECT
    *
    FROM user";
    $stmt = $dbConn->query($sql);
    $rows = $stmt->fetchAll();

    return $rows;
}
?>