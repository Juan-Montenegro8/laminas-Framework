<?php

namespace Persona\Form;

use Laminas\Form\Element\Hidden;
use Laminas\Form\Element\Submit;
use Laminas\Form\Element\Text;
use Laminas\Form\Element\Tel;
use Laminas\Form\Element\Select;
use Laminas\Form\Form;

class PersonaForm extends Form {

    public function __construct($departamentos = [],$municipios = []) {
        // We will ignore the name provided to the constructor
        parent::__construct('persona');

        $this->add([
            'name' => 'idPersona',
            'type' => Text::class,
        ]);
        $this->add([
            'name' => 'nombres',
            'type' => Text::class,
            'options' => [
                'label' => 'Nombres',
            ],
        ]);
        $this->add([
            'name' => 'apellidos',
            'type' => Text::class,
            'options' => [
                'label' => 'Apellidos',
            ],
        ]);
        $this->add([
            'name' => 'identificacion',
            'type' => Text::class,
            'options' => [
                'label' => 'Identificacion',
            ],
            'attributes' => [
                'onchange' => 'verificarIdentificacion()',
                'id' => 'identificacion',
            ],
        ]);
        $this->add([
            'name' => 'celular',
            'type' => Tel::class,
            'options' => [
                'label' => 'Celuar',
            ],
        ]);
        $this->add([
            'name' => 'idMunicipio',
            'type' => Select::class,
            'options' => [
                'label' => 'Municipio',
                'empty_option' => 'Seleccione...',
                'value_options' => $municipios,
                'disable_inarray_validator'=>true
            ],
            'attributes' => [
                'id' => 'municipio',
            ],
        ]);
        
        $this->add([
            'name' => 'departamento',
            'type' => Select::class,
            'options' => [
                'label' => 'Departamento',
                'empty_option' => 'Seleccione...',
                'value_options' => $departamentos,
            ],'attributes' => [
                'onchange'=>'getMunicipios()',
                'id' => 'departamento',
            ],
        ]);
        $this->add([
            'name' => 'submit',
            'type' => Submit::class,
            'attributes' => [
                'value' => 'Go',
                'id' => 'submitbutton',
            ],
        ]);
    }
}
