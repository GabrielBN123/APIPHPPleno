<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pessoa extends Model
{
    protected $table  = 'pessoas';
    protected $fillable = ['pes_id', 'pes_nome', 'pes_data_nascimento', 'pes_sexo', 'pes_mae', 'pes_pai'];
}
