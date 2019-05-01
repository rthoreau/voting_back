<?php

require_once 'base.php';

class Vote extends Proto {
  public $id_vote = '0';
  public $creation_date = '';
  public $nickname = '';
  public $table_name = 'votes';
  public $id_name = 'id_vote';

  function vote_exists() {
    $nickname = san_utf8($this->nickname);
    $sql = "SELECT id_vote FROM $this->table_name WHERE nickname = '$nickname' LIMIT 1";
    $r = result($sql, 'FIRST');
    return empty($r) ? false : $r != $this-> id_vote;
  }
}
