<?php

class Message {
  
  private $name;
  private $created;
  private $message;
  
  function __construct($message_data = null) {
    $this->createMessage($message_data);
  }
  
  private function createMessage($message_data) {
    if(isset($message_data['name'])) {
      $this->name = $message_data['name'];
      $this->created = $message_data['created'];
      $this->message = $message_data['message'];
    }
  }
  
  function getName() {
    return $this->name;
  }

  function getCreated() {
    return $this->created;
  }

  function getMessage() {
    return $this->message;
  }

  function setName($name) {
    $this->name = $name;
  }

  function setCreated($created) {
    $this->created = $created;
  }

  function setMessage($message) {
    $this->message = $message;
  }
  
}
