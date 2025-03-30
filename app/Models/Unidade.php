<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Unidade extends Model
{
    use SoftDeletes;

    protected $table = 'unidade';

    protected $primaryKey = 'unid_id';

    protected $fillable = ['unid_nome','unid_sigla'];
}
