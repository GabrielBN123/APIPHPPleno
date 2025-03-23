<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UnidadeEndereco extends Model
{
    use SoftDeletes;

    protected $table = 'unidade_endereco';

    protected $fillable = ['unid_id','end_id'];
}
