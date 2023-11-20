<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeProduct extends Model
{
  use HasFactory;

  protected $primaryKey = 'id';

  protected $fillable = [
    'name',
    'image',
    'sort',
    'status',
  ];

  public function productCategory()
  {
    return $this->hasMany(ProductCategory::class, 'type_id');
  }
}
