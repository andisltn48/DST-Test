<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use HasFactory, SoftDeletes;

    public $table = 'transaction';
    protected $fillable = [
        'uuid',
        'user_id',
        'product_id',
        'amount',
        'tax',
        'admin_fee',
        'total',
    ];
}
