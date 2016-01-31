<?php

class View {

  function render($template_view, $data = null, $return = FALSE) {

    if ($return) {
      if ($data) {
        foreach ($data as $key => $value) {
          $$key = $value;
        }
      }

      ob_start();
      include 'app/view/' . $template_view . '.html';
      return ob_get_clean();
    }

    if ($data) {
      foreach ($data as $key => $value) {
        $$key = $value;
      }
    }


    include 'app/view/' . $template_view . '.html';
  }
}
