<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Broadcast extends Model
{
    protected $fillable = [
        'Title',
        'Message',
        'AuthorID',
        'TargetRole',
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'AuthorID');
    }
}
