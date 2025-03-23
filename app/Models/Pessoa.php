<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pessoa extends Model
{
    use HasFactory;

    use SoftDeletes;

    protected $table  = 'pessoa';

    protected $fillable = ['pes_id', 'pes_nome', 'pes_data_nascimento', 'pes_sexo', 'pes_mae', 'pes_pai'];
}
