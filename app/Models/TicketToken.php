<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketToken extends Model
{
    use HasFactory;

    protected $fillable = ["ticket_id", "token", "expires_at"];

    protected $casts = [
        "expires_at" => "datetime",
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }
}
