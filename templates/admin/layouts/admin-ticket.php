<?php

function print_messages($messages) {
  foreach ($messages as $message) {
    echo '<div class="message">';
    echo $message['name'] . '<br>';
    echo $message['email'] . '<br>';
    echo '<hr>';
    echo $message['message'] . '<br>';
    echo '</div>';
  }
}

// MAIN

/* @var $this Page */

//$system->loadLibrary('bank');

if (!isset($get['id'])) {
  F::redirect(BASE_URL);
}

$ticket_id = $get['id'];
$ticket = Tickets::getTicket($ticket_id);

if ($ticket == null) {
  F::redirect(BASE_URL);
}

$ticket_data = $ticket->getData();

$messages = $ticket_data['messages'];

$summernote_css = BASE_URL . 'libraries/summernote/dist/summernote.css';
$summernote_js = BASE_URL . 'libraries/summernote/dist/summernote.js';
$popper_js = BASE_URL . 'libraries/summernote/dist/popper.min.js';

$generate_card['title'] = 'accept';
$generate_card['color'] = 'success';
$generate_card['body'] = 'Generate Card';
$generate_card['name'] = 'generate_card';
$generate_card['icon'] = 'vote-yea';

$decline['title'] = 'decline';
$decline['color'] = 'danger';
$decline['body'] = 'Decline Request';
$decline['name'] = 'decline';
$decline['icon'] = 'window-close';

?>
<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <h1 class="h3 mb-4 text-gray-800">Ticket # <?php echo $ticket_id; ?></h1>

  <style>

    .spinner-border {
      position: absolute;
      margin: auto;
      margin-top: 15px;
      margin-bottom: 15px;
    }

    content {
      display: none;
    }

    .note-popover {
      display: none;
    }
    
    .note-toolbar {
      background-color: white;
    }

    .message {
      border: 1px solid gray;
      border-radius: 3px;
      padding-left: 15px;
      padding-right: 15px;
      margin-bottom: 15px;
    }
  </style>

  <link href="<?php echo $summernote_css; ?>" rel="stylesheet">
  <script src="<?php echo $summernote_js; ?>"></script>
  <script src="<?php echo $popper_js; ?>"></script>

  <div class="spinner-border text-center" role="status">
    <span class="sr-only text-center">Loading...</span>
  </div>

  <content>
    <div class="row">

      <?php AdminLayouts::bootstrapCard($generate_card); ?>
      <?php AdminLayouts::bootstrapCard($decline); ?>
      <div class="col-md-12">

        <form id="summernote-form" method="post">

          <input type="hidden" name="ticket-id" value="<?php echo $ticket_id; ?>" />

          <!-- Project Card Example -->
          <div class="card shadow mb-4">
            <div class="card-header py-3">
              <h6 class="m-0 font-weight-bold text-primary">Messenger</h6>
            </div>
            <div class="card-body">
              <textarea id="summernote" name="message"></textarea>
              <button class="btn btn-primary btn-block">Send Message</button>
            </div>
          </div>

        </form>

        <div id="messages"><?php print_messages($messages); ?></div>

      </div>
    </div>
  </content>


</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->

<script>

  $(document).ready(function () {

    $('#summernote').summernote({
      toolbar: [
        // [groupName, [list of button]]
        ['style', ['bold', 'italic', 'underline', 'clear']],
        ['font', ['strikethrough', 'superscript', 'subscript']],
        ['fontsize', ['fontsize']],
        ['color', ['color']],
        ['para', ['ul', 'ol', 'paragraph']],
        ['height', ['height']]
      ]
    });

    $(".spinner-border").fadeOut(function () {
      $("content").hide().fadeIn("slow");
    });

  });

  $(document).on("submit", "#summernote-form", function (e) {
    e.preventDefault();

    let postData = "new_ticket_message=true&" + $(this).serialize();

    $.ajax({
      type: "POST",
      url: jsGlobals.baseUrl + "libraries/core/ajax/tickets.php",
      data: postData,
      success: function (response) {
        response = JSON.parse(response);
        if (response.status == "success") {
          let html = messagesToHtml(response.data);
          $("#summernote").summernote("reset");
          $("#messages").html(html);
        }
      }
    });
  });

  function messagesToHtml(data) {
    let html = '';

    data.forEach(function (row) {
      html += '<div class="card message">';
      html += row.name + '<br>';
      html += row.email + '<br>';
      html += '<hr>';
      html += row.message;
      html += '</div>';
    });

    return html;
  }

</script>
