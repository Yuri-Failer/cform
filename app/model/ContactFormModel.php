<?php

class ContactFormModel {
  private $csv = 'contact_form.csv'; //
  private $mysqli;
  private $tableName = 'contact_form';

  public function __construct(){
    $db = Database::getInstance();
    $this->mysqli = $db->getConnection();
  }

  /**
   * Add data to database.
   *
   * @param $csv
   *   Array rows form CSV.
   * @return array Array with an error message or null.
   *   Array with an error message or null.
   */
  public function addToDB($csv) {
    unset($csv['headers']);

    foreach($csv as $row) {

      $sql_fields = implode(', ',array_keys($row));
      $values = array();
      foreach($row as $value) {
        $values[] = "'" . $this->mysqli->real_escape_string($value) . "'";
      }
      $sql_values = implode(', ', $values);
      $sql_query = "INSERT INTO $this->tableName ($sql_fields) VALUES ($sql_values)";
      $result = $this->mysqli->query($sql_query);
      if (!$result) {
        return array('error' => 'The sql query "' . $sql_query . '" failed');
      }
    }
  }

  /**
   * Get all data from current table.
   * @param $table
   *   String. Table name.
   * @return array
   *   Result array or null.
   */
  public function getDataFromDb($table) {
    $sql_query = "SELECT * FROM $table";
    $result = $this->mysqli->query($sql_query);
    if ($result->num_rows > 0) {
      while(($resultArray[] = $result->fetch_assoc())|| array_pop($resultArray));
      $result->free();
      return $resultArray;
    }
  }

  /**
   * Add line to csv file, create file if the file doesn't exist.
   * @param $fields
   *   The form fields.
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
    if (file_exists($this->csv)) {

      if (($csvfile = fopen($this->csv, "r")) !== FALSE) {
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

  /**
   * @param $fields
   *  Array for filter.
   * @return mixed
   *  Array of safe data.
   */
  public function filterData($fields) {
    $safe_fields = array();
    foreach ($fields as $key => $field) {

      if($key == 'email') {
        $safe_fields[$key] = filter_var($field, FILTER_SANITIZE_EMAIL);
      }
      else {
        $safe_fields[$key] = strip_tags($field);
      }
    }
    return $safe_fields;
  }
}
