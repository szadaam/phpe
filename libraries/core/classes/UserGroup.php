<?php

class UserGroup {

  private $id;
  private $name;
  private $label;
  private $level;
  private $owner_id;
  private $su_ids;
  private $member_ids;
  private $status;

  function __construct($data) {
    $this->createUserGroup($data);
  }

  private function createUserGroup($data) {
    
    if (isset($data['id'])) {
      $this->id = $data['id'];
    }
    
    if (isset($data['name'])) {
      $this->label = $data['label'];
    }
    
    if (isset($data['level'])) {
      $this->level = $data['level'];
    }
    
    if (isset($data['owner_id'])) {
      $this->owner_id = $data['owner_id'];
    }
    
    if (isset($data['su_ids'])) {
      $this->su_ids = unserialize($data['su_ids']);
    }
    
    if (isset($data['member_ids'])) {
      $this->member_ids = unserialize($data['member_ids']);
    }
    
    if (isset($data['updated'])) {
      $this->updated = $data['updated'];
    }
    
    if (isset($data['status'])) {
      $this->status = $data['status'];
    }
  }
  
  function getId() {
    return $this->id;
  }

  function getName() {
    return $this->name;
  }

  function getLabel() {
    return $this->label;
  }

  function getLevel() {
    return $this->level;
  }

  function getOwner_id() {
    return $this->owner_id;
  }

  function getSu_ids() {
    return $this->su_ids;
  }

  function getMember_ids() {
    return $this->member_ids;
  }

  function getStatus() {
    return $this->status;
  }

  function setName($name) {
    $this->name = $name;
  }

  function setLabel($label) {
    $this->label = $label;
  }

  function setLevel($level) {
    $this->level = $level;
  }

  function setOwner_id($owner_id) {
    $this->owner_id = $owner_id;
  }

  function setSu_ids($su_ids) {
    $this->su_ids = $su_ids;
  }

  function setMember_ids($member_ids) {
    $this->member_ids = $member_ids;
  }

  function setStatus($status) {
    $this->status = $status;
  }

}
