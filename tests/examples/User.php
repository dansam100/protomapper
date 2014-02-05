<?php
namespace ProtoMapper\Tests\Examples;
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