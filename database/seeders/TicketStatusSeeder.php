<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TicketStatus;

class TicketStatusSeeder extends Seeder
{
    public function run(): void
    {
        $statuses = [
            ["name" => "Open", "color" => "blue", "is_default" => true],
            ["name" => "Pending", "color" => "yellow", "is_default" => false],
            ["name" => "Resolved", "color" => "green", "is_default" => false],
            ["name" => "Closed", "color" => "gray", "is_default" => false],
        ];

        foreach ($statuses as $status) {
            TicketStatus::firstOrCreate(
                ["name" => $status["name"]],
                ["color" => $status["color"], "is_default" => $status["is_default"]]
            );
        }
    }
}
