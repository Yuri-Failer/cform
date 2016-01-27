<?php

class ContactFormModel {
  private $csv = 'contact_form.csv'; //
  private $db = Database::getInstance();

  /**
   * Add data to database.
   * @param $csv
   *  array rows form CSV.
   */
  public function addToDB($csv) {
    $mysqli = $this->db->getConnection();
    var_dump($csv);
    foreach($csv as $row) {
      foreach($row as $value){
        $sql_query = "SELECT foo FROM .....";
        $result = $mysqli->query($sql_query);
      }
    }
  }

  /**
   * Add line to csv file, create file if the file doesn't exist.
   * @param $fields
   *  The form fields.
   */

  public function addToCSV($fields){
    if (file_exists($this->csv)) {
      $csvfile = fopen($this->csv, 'a');
      fputcsv($csvfile, $fields);
      fclose($csvfile);
    }
    else {
      $csvfile = fopen($this->csv, 'w');
      // Put header from keys.
      fputcsv($csvfile, array_keys($fields));
      // Add first line.
      fputcsv($csvfile, $fields);
      fclose($csvfile);
    }
  }

  /**
   * Get array data from CSV file.
   *
   * return array lines.
   */
  public function getCsv() {
    $result = array();
    // todo: move to var file name. to settings
    if (file_exists('contact_form.csv')) {

      if (($csvfile = fopen("contact_form.csv", "r")) !== FALSE) {
        $row = 0;
        while (($data = fgetcsv($csvfile, 1000, ",")) !== FALSE) {
          if ($row == 0) {
            foreach ($data as $value) {
              $result['headers'][] = $value;
            }
          }
          else {
            $result[$row] = array_combine($result['headers'], $data);
          }
          $row++;
        }
        fclose($csvfile);
      }
    }
    return $result;
  }
}
