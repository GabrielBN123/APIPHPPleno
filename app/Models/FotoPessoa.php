<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FotoPessoa extends Model
{
    use SoftDeletes;

    protected $table  = 'foto_pessoa';

    protected $fillable = ['fp_id','pes_id','fp_data','fp_bucket','fp_hash'];
}
