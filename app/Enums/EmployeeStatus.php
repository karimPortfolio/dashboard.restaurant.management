<?php

namespace App\Enums;

use Illuminate\Contracts\Support\Arrayable;



enum EmployeeStatus: string implements Arrayable
{

    CASE ACTIVE = 'active';
    CASE INACTIVE = 'inactive';

    public function label(): string
    {
        return match ($this) {
            self::ACTIVE => 'Active',
            self::INACTIVE => 'Inactive',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::INACTIVE =>  'negative',
            self::ACTIVE => 'positive',
        };
    }

    public function toArray(): array
    {
        return [
            'value' => $this->value,
            'label' => $this->label(),
            'color'=> $this->color(),
        ];
    }

}