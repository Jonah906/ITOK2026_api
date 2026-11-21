<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rsvp extends Model
{
    protected $fillable = ['name', 'email', 'phone', 'guests', 'attendance', 'stream_token', 'email_sent'];

    public function chats()
    {
        return $this->hasMany(Chat::class);
    }
}
