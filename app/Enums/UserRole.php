<?php

namespace App\Enums;

use Illuminate\Contracts\Support\Arrayable;



enum UserRole: string implements Arrayable
{

    CASE SUPER_ADMIN = 'super_admin';
    CASE ADMIN = 'admin';
    CASE USER = 'user';

    public function label(): string
    {
        return match ($this) {
            self::SUPER_ADMIN => 'Super Admin',
            self::ADMIN => 'Admin',
            self::USER => 'User',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::SUPER_ADMIN => 'purple',
            self::ADMIN => 'yellow',
            self::USER => 'blue',
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