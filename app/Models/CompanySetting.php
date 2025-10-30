<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanySetting extends Model
{
    protected $fillable = [
        'facebook_url',
        'x_url',
        'instagram_url',
        'linkedin_url',
        'youtube_url',
        'phone_number',
        'email',
        'address',
        'working_hours',
    ];
}
