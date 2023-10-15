<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
  use HasFactory;

  protected $primaryKey = 'cuid';

  protected $fillable = [
    'slug',
    'title',
    'meta_desc',
    'keyword',
    'image',
    'video',
    'content',
    'author',
    'kategori',
    'created_date',
    'last_update',
    'user',
    'status',
  ];
}
