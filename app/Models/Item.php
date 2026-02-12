<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = [
        'user_id',
        'category_id',
        'type',
        'title',
        'description',
        'location',
        'date',
        'photo',
        'status',
        'handed_over_at',
        'received_at',
    ];

        protected $casts = [
        'date' => 'date',
        'handed_over_at' => 'datetime',
        'received_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function chats()
    {
        return $this->hasMany(Chat::class);
    }

    public function confirmations()
    {
        return $this->hasMany(Confirmation::class);
    }

}
