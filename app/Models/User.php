<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Item;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function items()
    {
        return $this->hasMany(Item::class);
    }

    // Barang yang diposting sebagai DITEMUKAN
    public function foundItems()
    {
        return $this->items()->where('type', 'found');
    }

    // Barang yang diposting sebagai HILANG
    public function lostItems()
    {
        return $this->items()->where('type', 'lost');
    }

    // Barang yang SUDAH DIKEMBALIKAN (SERAH TERIMA)
    public function returnedItems()
    {
        return $this->items()->where('status', 'completed');
    }
    
    public function getFoundItemsCountAttribute()
    {
        return $this->foundItems()->count();
    }

    public function getLostItemsCountAttribute()
    {
        return $this->lostItems()->count();
    }

    public function getReturnedItemsCountAttribute()
    {
        return $this->returnedItems()->count();
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
