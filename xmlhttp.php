<?php
require_once 'base.php';
headers();
$operation = p('operation');
$r = [];
if ($operation) {
  if ($operation === '') {
  }
}
if (empty($r)) {
  return;
}
echo json_encode($r);