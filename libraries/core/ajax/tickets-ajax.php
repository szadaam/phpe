<?php

// +Snippet ajax_core_session
// 
// loader

require_once 'loader.php';
require_once $root . 'config/paths-config.php';

// load core library

require_once ABS_PATH . 'config/core-config.php';
require_once ABS_PATH . 'libraries/core/classes/autoload_ajax.php';

// initilaze

$system = new System(true);
$session = $system->getSession();
$post = $session->post();

// -Snippet ajax_core_session

if(isset($post['new_ticket'])) {
  
  $type_id = $post['type_id'];
  $name = $post['name'];
  $issued_userid = $post['issued_userid'];
  
  $data['messages'] = [];
  
  $sql = "INSERT INTO tickets (type_id, name, issued_userid, data) VALUES (?, ?, ?, ?)";
  Database::update($sql, [$type_id, $name, $issued_userid, serialize($data)], true);
  $response['status'] = 'success';
  
  echo json_encode($response);
}

if(isset($post['new_ticket_message'])) {
  
  $ticket_id = $post['ticket-id'];
  $message = $post['message'];
  
  $session = new Session();
  $ticket = Tickets::getTicket($ticket_id);
  $user = $session->getUser();
  
  // !!!: rights check
  
  $name = $user->getName();
  $email = $user->getEmail();
  $user_id = $user->getId();
  
  $addMessage = $ticket->addMessage($message, $name, $email, $user_id);
  
  $response['status'] = 'success';
  $response['data'] = $addMessage;
  
  echo json_encode($response);
  
}