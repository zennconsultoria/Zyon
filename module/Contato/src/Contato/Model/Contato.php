<?php

/**
 * namespace de localizacao do nossa Entity
 */
namespace Contato\Model;

// imports Zend\InputFilter
use Zend\InputFilter\InputFilterAwareInterface,
    Zend\InputFilter\InputFilter,
    Zend\InputFilter\InputFilterInterface;

class Contato implements InputFilterAwareInterface
{

    public $id;
    public $nome;
    public $telefone;
    public $email;
    public $data_criacao;
    public $data_atualizacao;
    protected $inputFilter;

    public function exchangeArray($data)
    {
        $this->id = (!empty($data['id'])) ? $data['id'] : null;
        $this->nome = (!empty($data['nome'])) ? $data['nome'] : null;
        $this->telefone = (!empty($data['telefone'])) ? $data['telefone'] : null;
        $this->email = (!empty($data['email'])) ? $data['email'] : null;
        $this->data_criacao = (!empty($data['data_criacao'])) ? $data['data_criacao'] : null;
        $this->data_atualizacao = (!empty($data['data_atualizacao'])) ? $data['data_atualizacao'] : null;
    }

    /**
     * Método obrigatório de implementação da interface InputFilterAwareInterface, 
     * não utilizaremos esse método para nada, logo lançamos uma exception em 
     * casa de uso deste
     * 
     * @param \Zend\InputFilter\InputFilterInterface $inputFilter
     * @throws Exception
     */
    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new Exception('Não utilizado.');
    }

    /**
     * Método obrigatório de implementação da interface InputFilterAwareInterface,
     * aqui colocamos todas as regras de validações e filtros para nossos campos de
     * input
     * 
     * @return InputFilter
     */
    public function getInputFilter()
    {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();

            // input filter para campo de id
            $inputFilter->add(array(
                'name' => 'id',
                'required' => true,
                'filters' => array(
                    array('name' => 'Int'), # transforma string para inteiro
                ),
            ));

            // input filter para campo de nome  
            $inputFilter->add(array(
                'name' => 'nome',
                'required' => true,
                'filters' => array(
                    array('name' => 'StripTags'), # remove xml e html da string
                    array('name' => 'StringTrim'), # remove espacos do início e do final da string
                    array('name' => 'StringToUpper'), # transofrma string para maiusculo
                ),
                'validators' => array(
                    array(
                        'name' => 'NotEmpty',
                        'options' => array(
                            'messages' => array(
                                \Zend\Validator\NotEmpty::IS_EMPTY => 'Campo obrigatório.'
                            ),
                        ),
                    ),
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min' => 3,
                            'max' => 100,
                            'messages' => array(
                                \Zend\Validator\StringLength::TOO_SHORT => 'Mínimo de caracteres aceitáveis %min%.',
                                \Zend\Validator\StringLength::TOO_LONG => 'Máximo de caracteres aceitáveis %max%.',
                            ),
                        ),
                    ),
                ),
            ));

            // input filter para campo de telefone principal
            $inputFilter->add(array(
                'name' => 'telefone',
                'required' => true,
                'filters' => array(
                    array('name' => 'StripTags'), # remove xml e html da string
                    array('name' => 'StringTrim'), # remove espacos do início e do final da string
                ),
                'validators' => array(
                    array(
                        'name' => 'NotEmpty',
                        'options' => array(
                            'messages' => array(
                                \Zend\Validator\NotEmpty::IS_EMPTY => 'Campo obrigatório.'
                            ),
                        ),
                    ),
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min' => 8, #xxxxxxxx
                            'max' => 15, #(xxx)xxxx-xxxxx
                            'messages' => array(
                                \Zend\Validator\StringLength::TOO_SHORT => 'Mínimo de caracteres aceitáveis %min%.',
                                \Zend\Validator\StringLength::TOO_LONG => 'Máximo de caracteres aceitáveis %max%.',
                            ),
                        ),
                    ),
                ),
            ));

            // input filter para campo email
            $inputFilter->add(array(
                'name' => 'email',
                'required' => false,
                'filters' => array(
                    array('name' => 'StripTags'), # remove xml e html da string
                    array('name' => 'StringTrim'), # remove espacos do início e do final da string
                    array('name' => 'StringToLower')
                ),
                'validators' => array(
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min' => 5, #xxxxxxxx
                            'max' => 90, #(xxx)xxxx-xxxxx
                            'messages' => array(
                                \Zend\Validator\StringLength::TOO_SHORT => 'Mínimo de caracteres aceitáveis %min%.',
                                \Zend\Validator\StringLength::TOO_LONG => 'Máximo de caracteres aceitáveis %max%.',
                            ),
                        ),
                    ),
                ),
            ));

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }

}