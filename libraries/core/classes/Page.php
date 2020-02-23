<?php

class Page {

  private $system;
  private $session;

  // TODO implement Layout class
  //private $id;
  //private $files; //handles images...etc by directory_id
  //private $forms;

  function __construct($system) {
    $this->createPage($system);
  }

  private function createPage($system) {

    $this->system = $system;
    $this->session = $system->getSession();
    $this->createView();
  }

  // prints the html model into the browser

  private function createView() {

    $this->system->debug('Page: createView()');

    // inject the code into the browser

    if ($this->system->getDirect()) {
      require_once $this->system->getBodyLayout();
    } else {

      $template = $this->system->getTemplate();
      $head_path = ABS_PATH . 'templates/' . $template . '/layouts/' . $template . '-head.php';
      $footer_path = ABS_PATH . 'templates/' . $template . '/layouts/' . $template . '-footer.php';

      $this->loadLayout($head_path);
      $this->system->jsGlobals();
      $this->loadLayout($this->system->getBodyLayout());
      $this->loadLayout($footer_path);
    }
  }

  // GETTERS

  function getData() {
    return $this->data;
  }

  // SETTERS

  function setPageLayout($pageLayout) {
    $this->pageLayout = $pageLayout;
  }

  // CUSTOM

  function loadLayout($layout_path) {

    if (file_exists($layout_path)) {

      $data = $this->system->getData();

      require_once $layout_path;
    } else {

      $message = 'Page: *** layout not found *** =>' . $layout_path;

      $this->debug($message);
      F::consoleLogJS($message);
    }


    $this->system->debug('Page: layout loaded => ' . $layout_path);
  }

  public function printHead() {

    echo "\n";
    foreach ($this->system->getHead() as $html) {
      echo $html . "\n";
    }
    echo "\n";
  }

  public function printFooter() {

    echo "\n";
    foreach ($this->system->getFooter() as $html) {
      echo $html . "\n";
    }
    echo "\n";
  }

  // WRAPPERS

  function getSession() {
    return $this->system->getSession();
  }

  function debug($message) {
    $this->system->debug($message);
  }

  function getRequest() {
    return $this->system->getRequest();
  }

}
