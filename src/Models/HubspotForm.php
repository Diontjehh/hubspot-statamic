<?php

namespace DionBoerrigter\Hubspot\Models;

use Illuminate\Database\Eloquent\Model;

class HubspotForm extends Model
{
    protected $table = 'forms';

    protected $fillable = [
        'handle',
        'title',
        'hubspot_guid',
        'settings'
    ];
}