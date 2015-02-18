<?php

// namespace de localizacao do nosso model
namespace Contato\Model;

// import Zend\Db
use //Zend\Db\Adapter\Adapter,
    //Zend\Db\ResultSet\ResultSet,
    Zend\Db\TableGateway\TableGateway;

// import for fetchPaginator
use Zend\Db\Sql\Select,
    Zend\Db\ResultSet\HydratingResultSet,
    Zend\Stdlib\Hydrator\Reflection,
    Zend\Paginator\Adapter\DbSelect,
    Zend\Paginator\Paginator;

class ContatoTable
{
    protected $tableGateway;

    /**
     * Contrutor com dependencia da classe TableGateway
     * 
     * @param \Zend\Db\TableGateway\TableGateway $tableGateway
     */
    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    /**
     * Recuperar todos os elementos da tabela contatos
     * 
     * @return ResultSet
     */
    public function fetchAll()
    {
        return $this->tableGateway->select();
    }

    /**
     * Localizar linha especifico pelo id da tabela contatos
     * 
     * @param type $id
     * @return \Model\Contato
     * @throws \Exception
     */
    public function find($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row)
            throw new \Exception("Não foi encontrado contado de id = {$id}");

        return $row;
    }
    
    /**
    * Inserir um novo contato
    * 
    * @param \Contato\Model\Contato $contato
    * @return 1/0
    */
    public function save(Contato $contato)
    {
       $timeNow = new \DateTime();

       $data = [
           'nome'                  => $contato->nome,
           'telefone'              => $contato->telefone,
           'email'                 => $contato->email,
           'data_criacao'          => $timeNow->format('Y-m-d H:i:s'), 
           'data_atualizacao'      => $timeNow->format('Y-m-d H:i:s'), # data de criação igual a de atualização 
       ];

       return $this->tableGateway->insert($data);
    }
    
    /**
    * Atualizar um contato existente
    * 
    * @param \Contato\Model\Contato $contato
    * @throws \Exception
    */
    public function update(Contato $contato)
    {
       $timeNow = new \DateTime();

       $data = [
           'nome'                  => $contato->nome,
           'telefone'              => $contato->telefone,
           'email'                 => $contato->email, 
           'data_atualizacao'      => $timeNow->format('Y-m-d H:i:s'),
       ];

       $id = (int) $contato->id;
       if ($this->find($id)) {
           $this->tableGateway->update($data, array('id' => $id));
       } else {
           throw new \Exception("Contato #{$id} inexistente");
       }
    }
    
    /**
    * Deletar um contato existente
    * 
    * @param type $id
    */
    public function delete($id)
    {
       $this->tableGateway->delete(array('id' => (int) $id));
    }
    
    /**
    * Localizar itens por paginação
    * 
    * @param type $pagina
    * @param type $itensPagina
    * @param type $ordem
    * @param type $like
    * @param type $itensPaginacao
    * @return type Paginator
    */
   public function fetchPaginator($pagina = 1, $itensPagina = 10, $ordem = 'nome ASC', $like = null, $itensPaginacao = 5) 
   {
       // preparar um select para tabela contato com uma ordem
       $select = (new Select('contatos'))->order($ordem);

       if (isset($like)) {
           $select
                   ->where
                   ->like('id', "%{$like}%")
                   ->or
                   ->like('nome', "%{$like}%")
                   ->or
                   ->like('telefone', "%{$like}%")
                   ->or
                   ->like('email', "%{$like}%")
                   ->or
                   ->like('data_criacao', "%{$like}%")
           ;
       }

       // criar um objeto com a estrutura desejada para armazenar valores
       $resultSet = new HydratingResultSet(new Reflection(), new Contato());

       // criar um objeto adapter paginator
       $paginatorAdapter = new DbSelect(
           // nosso objeto select
           $select,
           // nosso adapter da tabela
           $this->tableGateway->getAdapter(),
           // nosso objeto base para ser populado
           $resultSet
       );

       // resultado da paginação
       return (new Paginator($paginatorAdapter))
               // pagina a ser buscada
               ->setCurrentPageNumber((int) $pagina)
               // quantidade de itens na página
               ->setItemCountPerPage((int) $itensPagina)
               ->setPageRange((int) $itensPaginacao);
   }
}