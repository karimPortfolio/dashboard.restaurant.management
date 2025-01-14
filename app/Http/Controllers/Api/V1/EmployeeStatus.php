<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EmployeeStatus extends Controller
{
    public function __invoke()
    {
        $status = \App\Enums\EmployeeStatus::cases();

        $status = array_map(function ($status) {
            return [
                "value" => $status->value,
                "label" => $status->label(),
            ];
        }, $status);

        return response()->json([
            'data' => $status
        ]);
    }
}
