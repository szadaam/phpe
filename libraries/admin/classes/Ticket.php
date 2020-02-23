<?php

class Ticket {

  private $id;
  private $type_id;
  private $name;
  private $issued_userid;
  private $status;
  private $created;
  private $data;

  function __construct($ticket_data) {
    $this->createTicket($ticket_data);
  }

  private function createTicket($ticket_data) {
    if (isset($ticket_data['id'])) {
      $this->id = $ticket_data['id'];
    }
    if (isset($ticket_data['type_id'])) {
      $this->type_id = $ticket_data['type_id'];
    }
    if (isset($ticket_data['name'])) {
      $this->name = $ticket_data['name'];
    }
    if (isset($ticket_data['issued_userid'])) {
      $this->issued_userid = $ticket_data['issued_userid'];
    }
    if (isset($ticket_data['status'])) {
      $this->status = $ticket_data['status'];
    }
    if (isset($ticket_data['created'])) {
      $this->created = $ticket_data['created'];
    }
    if (isset($ticket_data['data'])) { 
      $this->data = unserialize($ticket_data['data']); 
    }
  }

  // GETTERS

  function getId() {
    return $this->id;
  }

  function getType_id() {
    return $this->type_id;
  }

  function getName() {
    return $this->name;
  }

  function getIssued_userid() {
    return $this->issued_userid;
  }

  function getStatus() {
    return $this->status;
  }

  function getCreated() {
    return $this->created;
  }

  function getData() {
    return $this->data;
  }

  // SETTERS

  function addMessage($message, $name, $email) {

    $data = $this->data;
    
    if(!isset($data['messages'])) {
      $data['messages'] = [];
    }
    
    $message_data['name'] = $name;
    $message_data['email'] = $email;
    $message_data['created'] = F::datetime();
    $message_data['message'] = $message;
    
    array_unshift($data['messages'], $message_data);
    
    $this->setData($data);
    
    return $this->data['messages'];
  }

  function setData($data) {
    $this->data = $data;
   
    if ($this->id > 0) {
      $sql = "UPDATE tickets SET data = ? WHERE id = ?";
      Database::update($sql, [serialize($this->data), $this->id], true);
    }
  }

}
