<?php

namespace App\Model;

use Ramsey\Uuid\Uuid;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\Extension\Core\Type\FormType;

class SearchData
{
    /** @var */
    public $id_user_current =null;

    /** @var int */
    public $page = 1;

    /** @var string */
    public string $q='' ;

    public function setSearch(string $search){
        $this->q=$search;
    }

    /** @var array */
    public array $category=[];

    public function getCategory(){
        return $this->category;
    }

    public function setCategory(array $categorie){
        $this->category=$categorie;
    }

    /** @var array */
    public array $instrument=[];

    public function getInstrument(){
        return $this->instrument;
    }

    public function setInstrument(array $instruments){
        $this->instrument=$instruments;
    }

    /** @var array */
    public array $moods=[];

    public function getMoods(){
        return $this->moods;
    }

    public function setMoods(array $mood){
        $this->moods=$mood;
    }

}

