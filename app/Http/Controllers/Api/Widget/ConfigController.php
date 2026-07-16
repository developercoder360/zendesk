<?php

namespace App\Http\Controllers\Api\Widget;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WidgetSetting;

class ConfigController extends Controller
{
    public function show(Request $request)
    {
        $settings = WidgetSetting::where("tenant_id", tenant("id"))->first();

        if (!$settings) {
            // Default configuration
            return response()->json([
                "colors" => ["primary" => "#2563eb"],
                "position" => "bottom-right",
                "language" => "en",
                "modules" => [
                    "tickets" => true,
                    "chat" => false,
                ]
            ]);
        }

        return response()->json([
            "colors" => ["primary" => $settings->primary_color],
            "position" => $settings->position,
            "language" => $settings->language,
            "modules" => [
                "tickets" => true,
                "chat" => false,
            ]
        ]);
    }
}
