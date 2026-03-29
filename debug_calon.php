<?php
require 'vendor/autoload.php';
$app = require_once 'system/bootstrap.php';
$db = \Config\Database::connect();

$builder = $db->table('pemilihan_calons');
$builder->select('pemilihan_calons.id, pemilihan_calons.pemilihan_id, pemilihans.status, pemilihans.periode, organisasis.name');
$builder->join('pemilihans', 'pemilihan_calons.pemilihan_id = pemilihans.id');
$builder->join('organisasis', 'pemilihans.organisasi_id = organisasis.id');
$results = $builder->get()->getResult();

echo "Kandidat Data:\n";
foreach ($results as $row) {
    echo "ID: {$row->id}, Pemilihan ID: {$row->pemilihan_id}, Status: {$row->status}, Org: {$row->name}, Periode: {$row->periode}\n";
}
