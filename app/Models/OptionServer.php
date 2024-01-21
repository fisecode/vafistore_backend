<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OptionServer extends Model
{
  use HasFactory;
  protected $primaryKey = 'id';

  protected $fillable = [
    'name',
    'category_id',
  ];

  public function category()
  {
    return $this->belongsTo(ProductCategory::class, 'category_id');
  }
}
