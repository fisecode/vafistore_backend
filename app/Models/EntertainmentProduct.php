<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EntertainmentProduct extends Model
{
  use HasFactory;

  protected $fillable = [
    'slug',
    'code',
    'item',
    'brand',
    'category',
    'capital_price',
    'selling_price',
    'reseller_price',
    'image',
    'currency',
    'type',
    'status',
    'provider',
  ];
}
