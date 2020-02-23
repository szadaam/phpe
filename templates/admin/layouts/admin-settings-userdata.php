<?php

// table editor
// users táblára lefejleszve
// !!!: table schema

function drawUserSettings() {
  $table_name = 'users';
  $select_columns_data = [
      'email',
      'username',
      'realname',
      'tel',
      'lastlogin',
      'membersince',
      'lat',
      'lng',
      'county_id',
      'town_id',
      'postcode_id',
      'address',
      'active',
      'blocked'
  ];

  $table_schema = Database::getTableSchema($select_columns_data, $table_name);

  $color_index = 0;
  foreach ($table_schema as $column) {
    $card_data['name'] = $column;
    $card_data['title'] = strtoupper($column);
    $card_data['icon'] = 'edit';
    // !!! implement the input type
    $card_data['body'] = '<input class="form-control" type="text">';
    $card_data['color'] = CARD_COLORS[$color_index];

    AdminLayouts::bootstrapCard_edit($card_data);

    $color_index++;
    if ($color_index >= count(CARD_COLORS)) {
      $color_index = 0;
    }
  }
}
?>
<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <h1 class="h3 mb-4 text-gray-800">Settings</h1>

  <div class="row">

    <?php drawUserSettings(); ?>

  </div>
  <!-- /.container-fluid -->

</div>
<!-- End of Main Content -->