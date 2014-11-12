<?php

/**
 * namespace de localizacao do nosso formulário
 */
namespace Contato\Form;

// import Zend\Captcha
use Zend\Captcha;
// import Form\Form
use Zend\Form\Form;
// import Form\Element
use Zend\Form\Element;

class ContatoForm extends Form
{

    public function __construct($name = null)
    {
        parent::__construct($name);
        
        // config form atributes
        $this->setAttributes(array(
            'method'    => 'POST',
            'class'     => 'form-horizontal',
        ));

        // elemento do tipo hidden
        $this->add(array(
            'type' => 'Hidden', # ou 'type' => 'Zend\Form\Element\Hidden'
            'name' => 'id',
        ));

        // elemento do tipo text
        $this->add(array(
            'type' => 'Text', # ou 'type' => 'Zend\Form\Element\Text'
            'name' => 'nome',
            'attributes' => array(
                'class'         => 'form-control',
                'id'            => 'inputNome',
                'placeholder'   => 'Nome Completo',
            ),
        ));

        // elemento do tipo text
        $this->add(
                (new Element\Text()) /* uso de interface fluente do PHP >= 5.4 */
                        ->setName('telefone')
                        ->setAttributes(/* [] == array() no PHP >= 5.4 */
                                [
                                    'class'         => 'form-control',
                                    'id'            => 'inputTelefonePrincipal',
                                    'placeholder'   => 'Digite seu telefone principal'
                                ]
                        )
        );

        // elemento do tipo text
        $this->add(array(
            'type' => 'Zend\Form\Element\Text', # ou 'type' => 'Text'
            'name' => 'email',
            'attributes' => array(
                'class'         => 'form-control',
                'id'            => 'inputEmail',
                'placeholder'   => 'Digite seu e-mail (opcional)',
            ),
        ));

        // elemento do tipo captcha para evitar ataques de robos
        $this->add(
                (new Element\Captcha())
                        ->setName('captcha')
                        ->setOptions(array(
                            'captcha' => (new Captcha\Figlet(array(
                                'wordLen'       => 12,      # quantidade de caracteres para o nosso captcha
                                'timeout'       => 300,     # tempo de validade do captcha em milisegundos
                                'outputWidth'   => '500',   # quantidade de strings por linha do capcha
                                'font'          => 'data/fonts/banner3.flf', # font para o captcha do tipo figlet
                            )))->setMessage("Campo faltando ou digitado incorretamente.")
                        ))
                        ->setAttributes([
                            'class'         => 'form-control',
                            'id'            => 'inputCaptcha',
                            'placeholder'   => 'Digite a palavra acima, aqui, para proseguir',
                        ])
        );

        // elemento para evitar ataques Cross-Site Request Forgery
        $this->add(new Element\Csrf('security'));
    }

}