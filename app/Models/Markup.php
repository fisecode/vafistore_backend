<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Markup extends Model
{
  use HasFactory;

  protected $primaryKey = 'id';

  protected $fillable = ['persen_sell', 'persen_res', 'persen_flash', 'com_ref', 'satuan'];
}
