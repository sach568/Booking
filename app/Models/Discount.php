<?php

// app/Models/Discount.php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    use HasFactory;

    protected $fillable = ['type', 'value', 'max_uses', 'max_amount'];

    public function applyDiscount($amount)
    {
        if ($this->type == 'percentage') {
            return $amount - ($amount * ($this->value / 100));
        } elseif ($this->type == 'amount') {
            return max(0, $amount - $this->value);
        }
        return $amount;
    }
}
