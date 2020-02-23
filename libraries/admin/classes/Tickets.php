<?php

class Tickets {

  public static function getTicket($ticket_id) {
    $sql = "SELECT * FROM tickets WHERE id = ?";
    $ticket_data = Database::selectSingle($sql, [$ticket_id]);

    if (empty($ticket_data)) {
      return null;
    }

    $ticket = new Ticket($ticket_data);

    return $ticket;
  }

}
