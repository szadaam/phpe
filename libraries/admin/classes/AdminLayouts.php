<?php

class AdminLayouts {

  public static function bootstrapCard($card_data) {
    ?>
    <!-- Earnings (Monthly) Card Example -->
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-<?php echo $card_data['color']; ?> shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-<?php echo $card_data['color']; ?> text-uppercase mb-1"><?php echo $card_data['title']; ?></div>
              <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $card_data['body']; ?></div>
            </div>
            <div class="col-auto">
              <i id="edit-<?php echo $card_data['name']; ?>" class="edit-card fas fa-<?php echo $card_data['icon'] ?> fa-2x text-gray-300"></i>
              </edit>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php
  }

  public static function bootstrapCard_edit($card_data) {
    ?>
    <!-- Earnings (Monthly) Card Example -->
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-<?php echo $card_data['color']; ?> shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-<?php echo $card_data['color']; ?> text-uppercase mb-1"><?php echo $card_data['title']; ?></div>
              <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $card_data['body']; ?></div>
            </div>
            <div class="col-auto">
              <i id="edit-<?php echo $card_data['name']; ?>" class="edit-card fas fa-<?php echo $card_data['icon'] ?> fa-2x text-gray-300"></i>
              </edit>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php
  }
}
