<?php
class User{
	public $isAdmin;
	public $isActive;
	public $isVerified;
	public $memberId;
	public $firstName;
	public $lastName;
	public $profiles;
	public $degrees;
	public $experiences;
	public $languages;
	public $media;
	public $skills;
	
	public function __construct(){
		$this->profiles = array();
		$this->degrees = array();
		$this->experiences = array();
		$this->languages = array();
		$this->media = array();
		$this->skills = array();
	}
}

class Profile{
	public $defaultAddress;
	public $status;
	public $objective;
}

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

class Media{
	public $type;
	public $name;
	public $value;
}

class Address{
	public $street1;
	public $street2;
	public $city;
	public $province;
	public $country;
	public $postalCode;
}

class Language{
	public $name;
}

class Skill{
	public $name;
}

class Company{
	public $name;
	public $industry;
}

class Degree{
	public $school;
	public $program;
	public $status;
	public $startDate;
	public $endDate;
	public $description;
}

class Activity{
    public $description;
}

class Duration{
    public $startDate;
    public $endDate;
}
