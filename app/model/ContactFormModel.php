<?php

class ContactFormModel {
  private $csv = 'contact_form.csv';

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
   * return array lines.
   */
  public function getCsv() {
    $result = array();
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
