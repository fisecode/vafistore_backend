<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
  use HasFactory;

  protected $primaryKey = 'id';

  protected $fillable = [
    'user_id',
    'slug',
    'title',
    'meta_desc',
    'keyword',
    'image',
    'video',
    'content',
    'kategori',
    'tags',
    'created_date',
    'last_update',
    'status',
  ];

  public function user()
  {
    return $this->belongsTo(User::class, 'user_id');
  }
}
