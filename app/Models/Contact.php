<?php

namespace App\Models;

use App\Models\Group;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Contact extends Model
{
    protected $table = 'contacts';

    protected $fillable = [
        'user_id',
        'email',
        'name',
        'description',
    ];

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Group::class, 'contact_tags', 'contact_id', 'tag_id');
    }
}
