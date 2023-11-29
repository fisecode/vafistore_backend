<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SocialProduct extends Model
{
  use HasFactory;

  protected $primaryKey = 'id';

  protected $fillable = [
    'slug',
    'code',
    'item',
    'category',
    'description',
    'min_buy',
    'max_buy',
    'capital_price',
    'selling_price',
    'reseller_price',
    'image',
    'type',
    'status',
    'provider',
  ];
}
