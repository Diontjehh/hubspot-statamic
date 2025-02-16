<?php

namespace DionBoerrigter\Hubspot\Models;

use Illuminate\Database\Eloquent\Model;

class FormFields extends Model
{
    protected $table = 'form_fields';

    protected $fillable = [
        'statamic_field',
        'hubspot_field',
    ];
}