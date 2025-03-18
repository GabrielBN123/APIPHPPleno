<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pessoa extends Model
{

    use HasFactory;
    protected $table  = 'pessoas';
    protected $fillable = ['pes_id', 'pes_nome', 'pes_data_nascimento', 'pes_sexo', 'pes_mae', 'pes_pai'];
}
