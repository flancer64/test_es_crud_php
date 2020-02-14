<?php
/**
 * Authors: Alex Gusev <alex@flancer64.com>
 * Since: 2020
 */
require_once '../vendor/autoload.php';

const NDX_MSK = 'vsf_msk';
const NDX_CATEGORY = NDX_MSK . '_category';
const ID = 5000;

$agent = new \Flancer64\Test\Es\Agent();
$client = $agent->initClient();
$agent->setIndex(NDX_CATEGORY);
[$settings, $mapping] = $agent->indexGetInfo();
$items = $agent->indexGetItems();
// CRUD
$item = $agent->itemGet(ID);
$data = [
    "children_count" => "0",
    "id" => ID,
    "is_active" => true,
    "level" => 3,
    "name" => "Тестовая категория",
    "parent_id" => 4,
    "path" => "1/2/4/" . ID,
    "position" => 8,
    "product_count" => 5,
    "url_key" => "-complement--326",
    "url_path" => "odnorazovaya-posuda/pribory-i-bokaly-complement-metallik/-complement--326"
];
$added = $agent->itemAdd(ID, $data);
$item = $agent->itemGet(ID);
$data = ["url_key" => "updated",];
$updated = $agent->itemUpdate(ID, $data);
$item = $agent->itemGet(ID);
$deleted = $agent->itemDelete(ID);
$item = $agent->itemGet(ID);
echo 'Done.';