<?php

namespace App\Observers;

use App\Models\User;
use Ramsey\Uuid\Uuid;

class UserObserver
{
    public function created(User $user): void
    {
        // 
    }
    
    public function creating(User $user): void
    {
        $user->id = Uuid::uuid4();
    }

    public function updated(User $user): void
    {
        //
    }

 
    public function deleted(User $user): void
    {
        //
    }

    public function restored(User $user): void
    {
        //
    }

    public function forceDeleted(User $user): void
    {
        //
    }
}
