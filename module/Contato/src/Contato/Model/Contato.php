<?php
namespace Contato\Model;

class Contato
{
    public $id;
    public $nome;
    public $telefone;
    public $email;
    public $data_criacao;
    public $data_atualizacao;

    public function exchangeArray($data)
    {
        $this->id                   = (!empty($data['id'])) ? $data['id'] : null;
        $this->nome                 = (!empty($data['nome'])) ? $data['nome'] : null;
        $this->telefone             = (!empty($data['telefone'])) ? $data['telefone'] : null;
        $this->email                = (!empty($data['email'])) ? $data['email'] : null;
        $this->data_criacao         = (!empty($data['data_criacao'])) ? $data['data_criacao'] : null;
        $this->data_atualizacao     = (!empty($data['data_atualizacao'])) ? $data['data_atualizacao'] : null;
    }
}