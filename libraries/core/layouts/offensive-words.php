<?php

if($this->session->isLoggedin()) {
  echo '<br>';
  OffensiveWordsFilter::wordForm();
} else {
  echo '<h1>No access</h1>';
}
