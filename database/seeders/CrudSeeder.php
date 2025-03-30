<?php

namespace Database\Seeders;

use App\Models\Cidade;
use App\Models\Endereco;
use App\Models\Lotacao;
use App\Models\Pessoa;
use App\Models\ServidorEfetivo;
use App\Models\ServidorTemporario;
use App\Models\Unidade;
use App\Models\UnidadeEndereco;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CrudSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $this->getCidade('Cuiabá', 'MT');

        $this->getServidor([
            'pes_nome' => 'João silva',
            'pes_data_nascimento' => '2000-05-12',
            'pes_sexo' => 'Masculino',
            'pes_mae' => 'Josiane',
            'pes_pai' => 'Julio',
            'servidorEfetivo' => [
                'se_matricula' => '301200'
            ],
            'unidade' => [
                'unid_nome' => 'Unidade do centro',
                'unid_sigla' => 'Unidade centro',
            ],
            'unidade_endereco' => [
                'end_tipo_logradouro' => 'Avenida',
                'end_logradouro' => 'Avenida 15',
                'end_numero' => '254',
                'end_bairro' => 'CPA II',
                'cid_id' => $this->getCidade('Cuiabá')->cid_id,
            ],
            'lotacao' => [
                'lot_data_lotacao' => '2015-08-22',
                'lot_data_remocao' => '2019-11-10',
                'lot_portaria' => 'Portaria nº 254/2000, publicada no Diário Oficial',
            ],
        ], 'efeivo');

        $this->getServidor([
            'pes_nome' => 'João silva',
            'pes_data_nascimento' => '2000-05-12',
            'pes_sexo' => 'Masculino',
            'pes_mae' => 'Josiane',
            'pes_pai' => 'Julio',
            'servidorTemporario' => [
                'st_data_admissao' => '2024-08-22',
                'st_data_demissao' => '2025-11-10',
            ],
            'unidade' => [
                'unid_nome' => 'Unidade do centro',
                'unid_sigla' => 'Unidade centro',
            ],
            'unidade_endereco' => [
                'end_tipo_logradouro' => 'Avenida',
                'end_logradouro' => 'Avenida 15',
                'end_numero' => '254',
                'end_bairro' => 'CPA II',
                'cid_id' => $this->getCidade('Cuiabá')->cid_id,
            ],
            'lotacao' => [
                'lot_data_lotacao' => '2015-08-22',
                'lot_data_remocao' => '2019-11-10',
                'lot_portaria' => 'Portaria nº 254/2000, publicada no Diário Oficial',
            ],
        ], 'temporario');

        $this->getServidor([
            'pes_nome' => 'Mariana Souza',
            'pes_data_nascimento' => '1992-03-18',
            'pes_sexo' => 'Feminino',
            'pes_mae' => 'Ana Souza',
            'pes_pai' => 'Carlos Souza',
            'servidorEfetivo' => [
                'se_matricula' => '302145'
            ],
            'unidade' => [
                'unid_nome' => 'Unidade do centro',
                'unid_sigla' => 'Unidade centro',
            ],
            'unidade_endereco' => [
                'end_tipo_logradouro' => 'Rua',
                'end_logradouro' => 'Rua das Flores',
                'end_numero' => '456',
                'end_bairro' => 'Jardim Primavera',
                'cid_id' => $this->getCidade('Cuiabá')->cid_id,
            ],
            'lotacao' => [
                'lot_data_lotacao' => '2012-07-10',
                'lot_data_remocao' => '2018-05-15',
                'lot_portaria' => 'Portaria nº 187/2012, publicada no Diário Oficial',
            ],
        ], 'efetivo');

        $this->getServidor([
            'pes_nome' => 'Carlos Mendes',
            'pes_data_nascimento' => '1985-11-25',
            'pes_sexo' => 'Masculino',
            'pes_mae' => 'Beatriz Mendes',
            'pes_pai' => 'Roberto Mendes',
            'servidorEfetivo' => [
                'se_matricula' => '309876'
            ],
            'unidade' => [
                'unid_nome' => 'Unidade Norte',
                'unid_sigla' => 'Unidade Norte',
            ],
            'unidade_endereco' => [
                'end_tipo_logradouro' => 'Avenida',
                'end_logradouro' => 'Avenida Central',
                'end_numero' => '789',
                'end_bairro' => 'Centro Norte',
                'cid_id' => $this->getCidade('Várzea Grande')->cid_id,
            ],
            'lotacao' => [
                'lot_data_lotacao' => '2010-03-22',
                'lot_data_remocao' => '2017-09-30',
                'lot_portaria' => 'Portaria nº 321/2010, publicada no Diário Oficial',
            ],
        ], 'efetivo');

        $this->getServidor([
            'pes_nome' => 'Lucas Pereira',
            'pes_data_nascimento' => '1998-09-10',
            'pes_sexo' => 'Masculino',
            'pes_mae' => 'Marcia Pereira',
            'pes_pai' => 'Jorge Pereira',
            'servidorTemporario' => [
                'st_data_admissao' => '2023-01-05',
                'st_data_demissao' => '2025-06-12',
            ],
            'unidade' => [
                'unid_nome' => 'Unidade do centro',
                'unid_sigla' => 'Unidade centro',
            ],
            'unidade_endereco' => [
                'end_tipo_logradouro' => 'Avenida',
                'end_logradouro' => 'Avenida 15',
                'end_numero' => '254',
                'end_bairro' => 'CPA II',
                'cid_id' => $this->getCidade('Cuiabá')->cid_id,
            ],
            'lotacao' => [
                'lot_data_lotacao' => '2023-01-10',
                'lot_data_remocao' => '2025-06-12',
                'lot_portaria' => 'Portaria nº 475/2023, publicada no Diário Oficial',
            ],
        ], 'temporario');

        $this->getServidor([
            'pes_nome' => 'Fernanda Lima',
            'pes_data_nascimento' => '1995-06-02',
            'pes_sexo' => 'Feminino',
            'pes_mae' => 'Claudia Lima',
            'pes_pai' => 'Paulo Lima',
            'servidorTemporario' => [
                'st_data_admissao' => '2022-03-15',
                'st_data_demissao' => '2024-08-01',
            ],
            'unidade' => [
                'unid_nome' => 'Unidade Sul',
                'unid_sigla' => 'Unidade Sul',
            ],
            'unidade_endereco' => [
                'end_tipo_logradouro' => 'Rua',
                'end_logradouro' => 'Rua das Acácias',
                'end_numero' => '321',
                'end_bairro' => 'Jardim Imperial',
                'cid_id' => $this->getCidade('Cuiabá')->cid_id,
            ],
            'lotacao' => [
                'lot_data_lotacao' => '2022-03-20',
                'lot_data_remocao' => '2024-08-01',
                'lot_portaria' => 'Portaria nº 590/2022, publicada no Diário Oficial',
            ],
        ], 'temporario');


    }

    public function getCidade($nome, $uf = 'MT')
    {

        $cidade = Cidade::where('cid_nome', $nome)->first();
        if(!$cidade){
            $cidade = Cidade::create([
                'cid_nome' => $nome,
                'cid_uf' => $uf,
            ]);
        }

        return $cidade;
    }

    public function getServidor($data, $tipoServidor)
    {


        $pessoa = Pessoa::where('pes_nome', $data['pes_nome'])->first();

        if(!$pessoa){
            $pessoa = Pessoa::create([
                'pes_nome' => $data['pes_nome'],
                'pes_data_nascimento' => $data['pes_data_nascimento'],
                'pes_sexo' => $data['pes_sexo'],
                'pes_mae' => $data['pes_mae'],
                'pes_pai' => $data['pes_pai'],
            ]);
        }

        switch ($tipoServidor) {
            case 'efetivo':
                $servidor = $this->getServidorEfetivo($pessoa->pes_id, $data['servidorEfetivo']);

                break;
            case 'temporario':
                $servidor = $this->getServidorTemporario($pessoa->pes_id, $data['servidorTemporario']);

                break;
            default:
                # code...
                break;
        }

        $unidade = $this->getUnidade($data['unidade']);

        $endereco = $this->getEndereco($data['unidade_endereco']);

        $enderecoUnidade = $this->getUnidadeEndereco($unidade->unid_id, $endereco->end_id);

        $lotacao = $this->getLotacao($pessoa->pes_id, $unidade->unid_id, $data['lotacao']);
    }

    public function getServidorEfetivo($pes_id, $data)
    {
        $servidor = ServidorEfetivo::where('pes_id', $pes_id)->first();
        if(!$servidor){
            $servidor = ServidorEfetivo::create([
                'pes_id' => $pes_id,
                'se_matricula' => $data['se_matricula'],
            ]);
        }
        return $servidor;
    }

    public function getServidorTemporario($pes_id, $data)
    {
        $servidor = ServidorTemporario::where('pes_id', $pes_id)->first();
        if(!$servidor){
            $servidor = ServidorTemporario::create([
                'pes_id' => $pes_id,
                'st_data_admissao' => $data['st_data_admissao'],
                'st_data_demissao' => $data['st_data_demissao'],
            ]);
        }
        return $servidor;
    }

    public function getUnidade($data)
    {
        $unidade = Unidade::where('unid_nome', $data['unid_nome'])->first();
        if(!$unidade){
            $unidade = Unidade::create([
                'unid_nome' => $data['unid_nome'],
                'unid_sigla' => $data['unid_sigla'],
            ]);
        }
        return $unidade;
    }

    public function getEndereco($data)
    {
        $endereco = Endereco::where('end_logradouro', $data['end_logradouro'])->first();
        if(!$endereco){
            $endereco = Endereco::create([
                'end_tipo_logradouro' => $data['end_tipo_logradouro'],
                'end_logradouro' => $data['end_logradouro'],
                'end_numero' => $data['end_numero'],
                'end_bairro' => $data['end_bairro'],
                'cid_id' => $data['cid_id'],
            ]);
        }
        return $endereco;
    }

    public function getUnidadeEndereco($unid_id, $end_id)
    {
        $uniEnd = UnidadeEndereco::where('unid_id', $unid_id)->first();
        if(!$uniEnd){
            $uniEnd = UnidadeEndereco::create([
                'unid_id' => $unid_id,
                'end_id' => $end_id,
            ]);
        }
        return $uniEnd;
    }

    public function getLotacao($pes_id, $unid_id, $data)
    {
        $lotacao = Lotacao::where('pes_id', $pes_id)->first();
        if(!$lotacao){
            $lotacao = Lotacao::create([
                'pes_id' => $pes_id,
                'unid_id' => $unid_id,
                'lot_data_lotacao' => $data['lot_data_lotacao'],
                'lot_data_remocao' => $data['lot_data_remocao'],
                'lot_portaria' => $data['lot_portaria'],
            ]);
        }
        return $lotacao;
    }
}
