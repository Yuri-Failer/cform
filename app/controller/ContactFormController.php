<?php

class ContactFormController {

  public $model;
  public $view;
  private $data;

  function __construct() {
    $this->view = new View;
    $this->model = new ContactFormModel;
    $this->data = array();
  }

  public function submit() {

    $fields = $_POST;
    if (!array_search('', $fields)) {
      $fields = $this->model->filterData($fields);
      $this->model->addToCSV($fields);
      $this->data['success'] = 'The data added to CSV';
    }
    else {
      $this->data['error'] = "One or more fields are empty";
    }

    $this->index();
  }

  function index() {
    // Get table from DB.
    $data['table'] = $this->model->getDataFromDb('contact_form');
    $data['headers'] = array_keys($data['table'][0]);
    if (!empty($data['table'])) {
      $this->data['table'] = $this->view->render('table', $data, TRUE);
    }

    $this->view->render('ContactForm', $this->data);
  }

  function csvToDb(){
    $csv_data = $this->model->getCsv();
    $data['message'] = '';
    if(!empty($csv_data)) {
      $result = $this->model->addToDB($csv_data);
      if (isset($result['error'])){
        $this->data = $result;
      }
      else {
        $this->data['table'] = $this->view->render('table', $csv_data, TRUE);
      }
    }

    $this->index();
  }

}
