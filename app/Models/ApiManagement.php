<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiManagement extends Model
{
  use HasFactory;

  protected $table = 'api';

  protected $primaryKey = 'id';

  protected $fillable = ['provider', 'api_key', 'private_key', 'merchant_code', 'jenis', 'status'];
}
