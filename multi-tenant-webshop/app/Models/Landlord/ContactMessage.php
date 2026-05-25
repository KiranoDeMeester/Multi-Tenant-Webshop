<?php

namespace App\Models\Landlord;

use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    protected $connection = 'landlord';

    protected $fillable = [
        'name',
        'email',
        'message',
    ];
}
