<?php

namespace App\Models;

use App\Enums\EmployeePosition;
use App\Enums\EmployeeStatus;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Employee extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'status' => EmployeeStatus::class,
        'position' => EmployeePosition::class,
    ];


    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function fullName(): Attribute
    {
        return new Attribute(function ($value) {
            return "{$this->first_name} {$this->last_name}";
        });
    }
}
