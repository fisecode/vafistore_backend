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
    'title',
    'kategori',
    'deskripsi',
    'min_buy',
    'max_buy',
    'harga_modal',
    'harga_jual',
    'harga_reseller',
    'image',
    'status',
    'jenis',
    'product_type',
  ];
}
