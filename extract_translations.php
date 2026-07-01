<?php
$idJson = json_decode(file_get_contents(__DIR__ . '/lang/id.json'), true);
$enJson = [];
foreach ($idJson as $key => $val) {
    $enJson[$key] = $key;
}
file_put_contents(__DIR__ . '/lang/en.json', json_encode($enJson, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
