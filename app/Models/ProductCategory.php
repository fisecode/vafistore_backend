<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
  use HasFactory;

  protected $primaryKey = 'id';

  protected $fillable = [
    'slug',
    'name',
    'check_id',
    'image',
    'type_id',
    'description',
    'help_text',
    'subtitle',
    'subimage',
    'popular',
    'sort',
    'user_id',
    'status',
  ];

  public function user()
  {
    return $this->belongsTo(User::class, 'user_id');
  }

  public function type()
  {
    return $this->belongsTo(TypeProduct::class, 'type_id');
  }
}
