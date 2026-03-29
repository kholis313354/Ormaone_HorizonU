<?php

namespace App\Models;

use CodeIgniter\Model;

class FormFieldModel extends Model
{
    protected $table = 'form_fields';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false; // Fields are hard deleted or managed by form update
    protected $protectFields = true;
    protected $allowedFields = [
        'form_id',
        'field_type',
        'label',
        'placeholder',
        'description',
        'options',
        'is_required',
        'order',
        'is_active'
    ];

    // Dates
    protected $useTimestamps = false; // Based on schema, fields might not share same timestamps as form, or do they?
    // Schema check: form_fields usually doesn't have timestamps in this setup, let's assume no for now unless schema check showed them?
    // User schema image showed 'forms' table. 'form_fields' usually simple.
    // Let's assume false for now to be safe.
}
