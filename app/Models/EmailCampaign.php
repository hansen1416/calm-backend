<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailCampaign extends Model
{
    protected $table = 'email_campaigns';

    protected $fillable = [
        'user_id',
        'name',
        'status',
        'mode',
        'kind',
        'entity_id',
        'graph_json',
    ];

    protected $casts = [
        'graph_json' => 'array',
    ];
}
