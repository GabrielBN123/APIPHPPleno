<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServidorEfetivo extends Model
{

    use SoftDeletes;

    protected $table = 'servidor_efetivo';

    protected $fillable = ['pes_id', 'se_matricula'];
}
