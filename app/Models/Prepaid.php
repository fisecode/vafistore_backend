<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prepaid extends Model
{
  use HasFactory;

  protected $primaryKey = 'id';

  protected $fillable = [
    'slug',
    'code',
    'title',
    'kategori',
    'brand',
    'harga_modal',
    'harga_jual',
    'harga_reseller',
    'harga_flash',
    'image',
    'flash_sale',
    'status',
    'jenis',
    'product_type',
  ];
}
