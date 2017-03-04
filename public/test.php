<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
 
// Declare data file name
$dataFile = 'data.json';
 
// Load our data
$data = loadData($dataFile);
 
// Could we load the data?
if (!$data) {
    die('Could not load data');
}
 
if (!isset($data['hitCount'])) {
    $data['hitCount'] = 1;
}
else {
    $data['hitCount'] += 1;
}
 
$result = saveData($data, $dataFile);
 
echo ($result) ? 'Success' : 'Error';
 
function loadData($file)
{
    // Does the file exist?
    if (!file_exists($file)) {
        // Well, just create it now
        // Save an empty array encoded to JSON in it
        file_put_contents($file, json_encode(array()));
    }
 
    // Get JSON data
    $jsonData = file_get_contents($file);
    $phpData  = json_decode($jsonData);
 
    return ($phpData) ? $phpData : false;
}
 
function saveData($array, $file)
{
    $jsonData = json_encode($array);
    $bytes = file_put_contents($file, $jsonData);
 
    return ($bytes != 0) ? true : false;
}