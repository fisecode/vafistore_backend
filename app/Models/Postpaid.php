<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Postpaid extends Model
{
  use HasFactory;

  protected $primaryKey = 'id';

  protected $fillable = [
    'slug',
    'code',
    'title',
    'kategori',
    'harga_modal',
    'harga_jual',
    'harga_reseller',
    'image',
    'currency',
    'status',
    'jenis',
    'product_type',
  ];
}
