<?php
// src/Entity/Apuesta.php
namespace App\Entity;

class Apuesta
{
    protected $texto;
    protected $fecha;

    public function getTexto()
    {
        return $this->texto;
    }

    public function setTexto($texto)
    {
        $this->texto = $texto;
    }

    public function getFecha()
    {
        return $this->fecha;
    }

    public function setFecha(\DateTime $fecha = null)
    {
        $this->fecha = $fecha;
    }
}