<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
  use HasFactory;

  public $timestamps = true;

  protected $primaryKey = 'id';

  protected $fillable = ['slug', 'page_name', 'content', 'image', 'video', 'user_id', 'status'];

  public function user()
  {
    return $this->belongsTo(User::class, 'user_id');
  }
}
