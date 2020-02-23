<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <h1 class="h3 mb-4 text-gray-800"><i class="fas fa-cog"></i> Settings</h1>
  <div class="row">
    <?php
    $card_data['name'] = 'userdata';
    $card_data['title'] = 'USER DATA';
    $card_data['icon'] = 'user';
    $card_data['body'] = '<a href="?p=settings-userdata">Enter</a>';
    $card_data['color'] = CARD_COLORS[0];

    AdminLayouts::bootstrapCard($card_data);

    $card_data['name'] = 'security';
    $card_data['title'] = 'ACCOUNT SECURITY';
    $card_data['icon'] = 'shield-alt';
    $card_data['body'] = '<a href="?p=settings-security">Enter</a>';
    $card_data['color'] = CARD_COLORS[3];

    AdminLayouts::bootstrapCard($card_data);
    
    ?>
  </div>
</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->