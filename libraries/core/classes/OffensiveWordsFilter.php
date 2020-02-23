<?php

class OffensiveWordsFilter {

  public static function add($word, $language) {
    switch ($language) {
      case 'hu':
        $table = 'offensive_words_hun';
        break;
      default:
        return false;
    }

    $sql = "SELECT COUNT(*) FROM " . $table . " WHERE word = ?";
    $exist = Database::selectSingle($sql, [$word])['COUNT(*)'] > 0;

    if (!$exist) {
      $sql = "INSERT INTO " . $table . "(word, createdby) VALUES (?, ?)";
      $session = new Session();
      $user_id = $session->getUserId();
      Database::update($sql, [$word, $user_id]);
      return $word . ' added to ' . $table . ' database';
    } else {
      return $word . ' alerady exists';
    }
  }

  public static function wordForm() {
    
    echo '<div class="card" style="width: 18rem;">';
    echo '<div class="card-body">';
    echo '<h5 class="card-title">Offensive Word</h5>';
    echo '<form id="offensive-words-form">';
    echo '<div class="form-group">';
    echo '<label>Language</label>';
    echo '<select class="form-control" name="language" required>';
    echo '<option value="hu">Magyar</option>';
    echo '</select>';
    echo '</div>';
    echo '<div class="form-group">';
    echo '<label>Word</label>';
    echo '<input class="form-control" type="text" name="word" placeholder="Offensive word here" required>';
    echo '</div>';
    echo '<input class="form-control" type="submit" value="Mentés" required>';
    echo '</form>';
    echo '</div>';
    echo '</div>';
    echo '<script>';
    echo '$(document).on("submit", "#offensive-words-form", function(e) {
            e.preventDefault();  
            let postData = "add_offensive_word=true&" + $("#offensive-words-form").serialize();
            $.ajax({
                type: "post",
                url: "libraries/core/ajax/developer-ajax.php",
                data: postData,
                success: function(response) {
                  alert(response);
                  console.log(response);
                  $("#offensive-words-form").trigger("reset");
                },
                error:function (xhr, ajaxOptions, thrownError){
                  alert(xhr.status);
                  alert(thrownError);
                }  
            });
        });';
    echo '</script>';
  }

}