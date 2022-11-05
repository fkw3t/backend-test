<?php

namespace App\Models;

use Ramsey\Uuid\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Seller extends Model
{
    use HasFactory;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'name',
        'person_type',
        'document_id',
        'email',
        'password',
    ];

    protected $hidden = [
        'password'
    ];

    // protected static function booted()
    // {
    //     static::creating(fn(Seller $seller) => $seller->id = (string) Uuid::uuid4());
    // }
}
