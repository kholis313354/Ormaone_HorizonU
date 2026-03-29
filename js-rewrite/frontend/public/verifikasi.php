<?php
require_once FCPATH . 'app/Controllers/Sertifikat.php';

$sertifikatController = new \App\Controllers\Sertifikat();
$id = $this->request->getUri()->getSegment(2);

$sertifikatController->verify($id);
?>