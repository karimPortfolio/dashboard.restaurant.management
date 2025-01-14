<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\EmployeePosition;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EmployeePositions extends Controller
{
    public function __invoke()
    {
        $positions = EmployeePosition::cases();

        $positions = array_map(function ($position) {
            return [
                "value" => $position->value,
                "label" => $position->label(),
            ];
        }, $positions);

        return response()->json([
            'data' => $positions
        ]);
    }
}
