<?php

namespace App\Controllers;

use App\Models\OrganisasiModel;

class Api extends BaseController
{
    public function facultyCodes()
    {
        $organisasiModel = new OrganisasiModel();
        $mapping = $organisasiModel->getFacultyCodeMapping();
        
        return $this->response->setJSON($mapping);
    }
}