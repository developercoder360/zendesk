<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class Customer extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = ["tenant_id", "name", "email", "phone"];

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}
