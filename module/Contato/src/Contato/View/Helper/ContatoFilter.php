<?php

namespace Contato\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Contato\Model\Contato;

class ContatoFilter extends AbstractHelper
{

    protected $contato;

    public function __invoke(Contato $contato)
    {
        $this->contato = $contato;

        return $this;
    }

    public function id()
    {
        $result = $this->contato->id;

        return $this->view->escapeHtml($result);
    }

    public function nomeSobrenome()
    {
        $partes_nome = explode(" ", $this->nomeCompleto());
        $result = null;

        if (count($partes_nome) <= 2) {
            $result = join($partes_nome, " ");
        } else {
            $result = "{$partes_nome[0]} {$partes_nome[1]} ...";
        }

        return $this->view->escapeHtml($result);
    }

    public function nomeCompleto()
    {
        $result = ucwords(strtolower($this->contato->nome));

        return $this->view->escapeHtml($result);
    }

    public function quantidadeTelefones()
    {
        $result = ((int) !empty($this->contato->telefone_principal)) + ((int) !empty($this->contato->telefone_secundario));

        return $this->view->escapeHtml($result);
    }

    public function dataCriacao()
    {
        $result = (new \DateTime($this->contato->data_criacao))->format('d/m/Y - H:i');

        return $this->view->escapeHtml($result);
    }

    public function dataAtualizacao()
    {
        $result = (new \DateTime($this->contato->data_atualizacao))->format('d/m/Y - H:i');

        return $this->view->escapeHtml($result);
    }

    public function telefone()
    {
        $result = $this->contato->telefone ? $this->contato->telefone : 'Sem Registro';

        return $this->view->escapeHtml($result);
    }

    public function email()
    {
        $result = $this->contato->email ? $this->contato->email : 'Sem Registro';

        return $this->view->escapeHtml($result);
    }

}