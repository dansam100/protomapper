<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ProtoMapper\Tests\Examples;

/**
 * Description of Experience
 *
 * @author sam.jr
 */
class Experience{
    public $position;
    public $description;
    public $department;
    public $startDate;
    public $endDate;
    public $durations;
    public $isCurrent;
    public $activities;
    public function __construct(){
        $this->activities = array();
        $this->durations = array();
    }
}