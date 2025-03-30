<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pessoa extends Model
{
    use HasFactory;

    use SoftDeletes;

    protected $table  = 'pessoa';
    protected $primaryKey = 'pes_id';

    protected $fillable = ['pes_nome', 'pes_data_nascimento', 'pes_sexo', 'pes_mae', 'pes_pai'];

    // 📸 Relacionamento com a FotoPessoa (1 para 1)
    public function foto()
    {
        return $this->hasOne(FotoPessoa::class, 'pes_id', 'pes_id');
    }

    // 🏠 Relacionamento com Endereço (1 para N)
    public function enderecos()
    {
        return $this->hasMany(PessoaEndereco::class, 'pes_id', 'pes_id');
    }

    // 🏛 Relacionamento com Lotação (1 para N)
    public function lotacoes()
    {
        return $this->hasMany(Lotacao::class, 'pes_id', 'pes_id');
    }

    // 📜 Relacionamento com Servidor Efetivo (1 para 1)
    public function servidorEfetivo()
    {
        return $this->hasOne(ServidorEfetivo::class, 'pes_id', 'pes_id');
    }

    // ⏳ Relacionamento com Servidor Temporário (1 para 1)
    public function servidorTemporario()
    {
        return $this->hasOne(ServidorTemporario::class, 'pes_id', 'pes_id');
    }
}
