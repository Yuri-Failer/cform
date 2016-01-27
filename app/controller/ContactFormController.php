<?php

class ContactFormController {

  public $model;
  public $view;

  function __construct() {
    $this->view = new View();
    $this->model = new ContactFormModel;
  }


  public function submit() {

    $fields = $_POST;

    $this->model->addToCSV($fields);
    $this->index();
  }

  function index() {
    $data = array();
    $csv_data['table'] = $this->model->getCsv();
    if (!empty($csv_data)) {
      $data['table'] = $this->view->render('table', $csv_data, TRUE);
    }

    $this->view->render('ContactForm', $data);
  }

  function moveToDatabase(){
    $csv_data = $this->model->getCsv();
    $data['message'] = '';
    if(!empty($csv_data)) {
      $result = $this->model->addToDB($csv_data);

      $data['table'] = $this->view->render('table', $csv_data, TRUE);
    }

    $this->view->render('ContactForm', $data );
  }

}
