<?php

namespace App\Models;

use App\Models\Seller;
use Illuminate\Database\Eloquent\Model;
use App\Exceptions\UnavailableBalanceException;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class  Wallet extends Model
{
    use HasFactory;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'user_id',
        'balance'
    ];

    public function walletable(): MorphTo
    {
        return $this->morphTo();
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }
    
    public function deposit(float $amount): bool
    {
        return $this->update([
            'balance' => $this->balance + $amount
        ]);
    }

    public function withdraw(float $amount): bool
    {
        if($amount > $this->balance){
            throw new UnavailableBalanceException('Insufficient balance account', 401);
        }

        return $this->update([
            'balance' => $this->balance - $amount
        ]);
    }
}
