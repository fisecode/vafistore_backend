<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slide extends Model
{
  use HasFactory;

  protected $primaryKey = 'id';

  protected $fillable = ['image', 'description', 'sort', 'user_id', 'status'];

  public function user()
  {
    return $this->belongsTo(User::class, 'user_id');
  }
}
