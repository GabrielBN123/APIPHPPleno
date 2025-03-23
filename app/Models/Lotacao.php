<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lotacao extends Model
{

    use SoftDeletes;

    protected $table  = 'lotacao';

    protected $fillable = ['lot_id','pes_id','unid_id','lot_data_lotacao','lot_data_remocao','lot_portaria'];
}
