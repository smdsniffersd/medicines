<?php
require_once __DIR__ . '/DataBase.php';

function OneFetch($sql, $params = []) {
    return DataBase::OneFetch($sql, $params);
}

function AllFetch($sql, $params = []) {
    return DataBase::AllFetch($sql, $params);
}

function execu($sql, $params = []) {
    return DataBase::execu($sql, $params);
}

function InsertRow($table, $data = []) {
    return DataBase::insertRow($table, $data);
}

function LastId() {
    return DataBase::lastId();
}
function deleteRow($table, $column, $value){
    return DataBase::deletes($table, $column, $value);
}

function update($table, $data, $where, $whereValue){
    return DataBase::updateRow($table, $data, $where, $whereValue);
}
