<?php

function pr ($arr) {
  echo '<pre>';
  print_r($arr);
  echo '</pre>';
}

function e ($v) {
  $v = htmlentities($v, ENT_QUOTES, 'UTF-8', true);
  return $v;
}