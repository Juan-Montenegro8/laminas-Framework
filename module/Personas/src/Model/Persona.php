<?php

namespace Persona\Model;

use DomainException;
use Laminas\Filter\StringTrim;
use Laminas\Filter\StringToUpper;
use Laminas\Filter\StripTags;
use Laminas\Filter\ToInt;
use Laminas\InputFilter\InputFilter;
use Laminas\InputFilter\InputFilterAwareInterface;
use Laminas\InputFilter\InputFilterInterface;
use Laminas\Validator\StringLength;
use Laminas\Validator\Db\RecordExists;

class Persona implements InputFilterAwareInterface 
{
    public $idPersona;
    public $nombres;
    public $apellidos;
    public $identificacion;
    public $celular;
    public $idMunicipio;

    private $inputFilter;
    
    public function exchangeArray(array $array): void
    {
        $this->idPersona     = ! empty($array['idPersona']) ? $array['idPersona'] : null;
        $this->nombres = ! empty($array['nombres']) ? $array['nombres'] : null;
        $this->apellidos  = ! empty($array['apellidos']) ? $array['apellidos'] : null;
        $this->identificacion  = ! empty($array['identificacion']) ? $array['identificacion'] : null;
        $this->celular  = ! empty($array['celular']) ? $array['celular'] : null;
        $this->idMunicipio  = ! empty($array['idMunicipio']) ? $array['idMunicipio'] : null;
    }
    
    public function getArrayCopy()
    {
        return [
            'idPersona'     => $this->idPersona,
            'nombres' => $this->nombres,
            'apellidos'  => $this->apellidos,
            'identificacion'  => $this->identificacion,
            'celular'  => $this->celular,
            'idMunicipio'  => $this->idMunicipio,
        ];
    }
    
    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new DomainException(sprintf(
            '%s does not allow injection of an alternate input filter',
            __CLASS__
        ));
    }

    public function getInputFilter()
    {
        if ($this->inputFilter) {
            return $this->inputFilter;
        }

        $inputFilter = new InputFilter();

        $inputFilter->add([
            'name' => 'idPersona',
            'required' => true,
            'filters' => [
                ['name' => ToInt::class],
            ],
        ]);

        $inputFilter->add([
            'name' => 'nombres',
            'required' => true,
            'filters' => [
                ['name' => StripTags::class],
                ['name' => StringTrim::class],
                ['name' => StringToUpper::class],
            ],
            'validators' => [
                [
                    'name' => StringLength::class,
                    'options' => [
                        'encoding' => 'UTF-8',
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
            ],
        ]);

        $inputFilter->add([
            'name' => 'apellidos',
            'required' => true,
            'filters' => [
                ['name' => StripTags::class],
                ['name' => StringTrim::class],
                ['name' => StringToUpper::class]
            ],
            'validators' => [
                [
                    'name' => StringLength::class,
                    'options' => [
                        'encoding' => 'UTF-8',
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
            ],
        ]);
        
        $inputFilter->add([
            'name' => 'identificacion',
            'required' => true,
            'filters' => [
                ['name' => StripTags::class],
                ['name' => StringTrim::class],
            ],
            'validators' => [
                [
                    'name' => StringLength::class,
                    'options' => [
                        'encoding' => 'UTF-8',
                        'min' => 1,
                        'max' => 15,
                    ],
                ],
            ],
        ]);
        
        $inputFilter->add([
            'name' => 'celular',
            'required' => true,
            'filters' => [
                ['name' => StripTags::class],
                ['name' => StringTrim::class],
                ['name' => 'Digits'],
            ],
            'validators' => [
                [
                    'name' => StringLength::class,
                    'options' => [
                        'encoding' => 'UTF-8',
                        'min' => 10,
                        'max' => 15,
                    ],
                ],
            ],
        ]);
        
        $inputFilter->add([
            'name' => 'idMunicipio',
            'required' => true,
        ]);
        

        $this->inputFilter = $inputFilter;
        return $this->inputFilter;
    }
}