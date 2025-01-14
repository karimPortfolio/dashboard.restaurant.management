<?php

namespace App\Enums;

use Illuminate\Contracts\Support\Arrayable;



enum EmployeePosition: string implements Arrayable
{

    case MANAGER = 'manager';
    case CHEF = 'chef';
    // case SOUSCHEF = 'sous_chef';
    case WAITER = 'waiter';
    // case BARTENDER = 'bartender';
    case HOST = 'host';
    case DISHWASHER = 'dishwasher';
    case DELIVERYDRIVER = 'delivery_driver';
    case INTERN = 'intern';

    public function label(): string
    {
        return match ($this) {
            self::MANAGER => 'Manager',
            self::CHEF => 'Chef',
            // self::SOUSCHEF => 'Sous Chef',
            self::WAITER => 'Waiter',
            // self::BARTENDER => 'Bartender',
            self::HOST => 'Host',
            self::DISHWASHER => 'Dishwasher',
            self::DELIVERYDRIVER => 'Delivery Driver',
            self::INTERN => 'Intern',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::MANAGER => 'purple',
            self::CHEF => 'yellow',
            // self::SOUSCHEF => 'yellow',
            self::WAITER => 'blue',
            // self::BARTENDER => 'blue',
            self::HOST => 'green',
            self::DISHWASHER => 'green',
            self::DELIVERYDRIVER => 'green',
            self::INTERN => 'green',
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