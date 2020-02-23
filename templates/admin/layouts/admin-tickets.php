<?php

// FUNCTIONS

function title($type_id) {
  switch ($type_id) {
    case 1:
      $result = 'General issues';
      break;
    case 2:
      $result = 'Account requests';
      break;
    case 3:
      $result = 'Card requests';
      break;
    default:
      $result = 'All Tickets';
      break;
  }

  return $result;
}

function ticket_status_class($status) {
  switch ($status) {
    // viewed
    case 1:
      $class = 'warning';
      break;
    case 2:
      $class = 'success';
      break;
    default:
      // not viewed
      $class = 'primary';
      break;
  }

  return $class;
}

function print_tickets($tickets) {
  //F::print_r($tickets);

  if (empty($tickets)) {
    echo '<div class="col-lg-12">';
    echo '<h4>No records found</h4>';
    echo '</div>';
  }

  foreach ($tickets as $ticket) {
    /* @var $ticket Ticket */

    $title = title($ticket->getType_id());
    $ticket_status_class = ticket_status_class($ticket->getStatus());
    ?>
    <div class="col-lg-3 col-md-6">
      <a href="admin?p=ticket&id=<?php echo $ticket->getId(); ?>">
        <div class="card bg-<?php echo $ticket_status_class; ?> text-white shadow ticket">
          <div class="card-body">
            <?php echo $title; ?>
            <div class="text-white-50 small">
              <?php echo $ticket->getCreated(); ?>
            </div>
          </div>
        </div>
    </div>
    </a>
    <?php
  }
}

// MAIN

/* @var $ticket Ticket */

$css_path = BASE_URL . 'libraries/admin/css/tickets.css';

$type_id = 0;
if (isset($get['type'])) {
  $type_id = $get['type'];
}

$tickets = [];

if ($type_id == 0) {
  $sql = "SELECT * FROM tickets";
  $ticket_data = Database::select($sql);
} else {
  $sql = "SELECT * FROM tickets WHERE type_id = ?";
  $ticket_data = Database::select($sql, [$type_id]);
}

foreach ($ticket_data as $data) {
  $ticket = new Ticket($data);
  array_push($tickets, $ticket);
}
?>
<link rel="stylesheet" href="<?php echo $css_path; ?>"/>
<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <h1 class="h3 mb-4 text-gray-800"><?php echo title($type_id); ?></h1>

  <div class="row">

    <?php print_tickets($tickets); ?>

  </div>

</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->