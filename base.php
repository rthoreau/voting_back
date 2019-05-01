<?php

session_start();

require_once 'db_access.php';
require_once 'db.php';
require_once 'vote.class.php';

function local() {
  return strrpos($_SERVER['HTTP_HOST'], 'localhost') !== false;
}

function p($val, $defaut = false) {
  if (empty($val)) {
    pre($_POST);
    pre(json_decode(file_get_contents('php://input')));
  }
  if (isset($_POST[$val])) {
    $r = $_POST[$val];
  }else if (empty($_POST)){
    $post = file_get_contents('php://input');
    $post = $post ? json_decode(file_get_contents('php://input')) : [];
    $r = isset($post->$val) ? $post->$val : $defaut;
  }else {
    $r = $defaut;
  }
  return $r;
}
function g($val, $defaut = false) {
  return isset($_GET[$val]) ? $_GET[$val] : $defaut;
}
function s($val, $defaut = false) {
  return isset($_SESSION[$val]) ? $_SESSION[$val] : $defaut;
}
function e($erreur) {
  $_SESSION['erreur'] = $erreur;
}
function san($texte, $rc = false) {
  $texte = $rc ? nl2br(addslashes($texte)) : addslashes($texte);
  return $texte;
}
function san_utf8($texte, $rc = false) {
  return utf8_encode(san($texte, $rc));
}
function uns($texte, $rc = false) {
  return $rc ? stripslashes(str_replace('<br />', "", $texte)) : stripslashes($texte) ;
}

function check_id($id) {
  return is_int($id) || (!empty($id) && is_int(intval($id)));
}

function pre($var) {
  echo '<pre>' ;
  print_r($var);
  echo '</pre>';
}

class Proto {
  public $table_name = '';
  public $id_name = '';
  public $id = '0';

  function __construct($id = 0) {
    $this->id_name = $this->id_name ? $this->id_name : 'id_' . substr($this->table_name, 0, -1);
    $id_name = $this->id_name;
    $this->setId(intval($id));
    if (check_id($this->$id_name)) {
      $sql = "SELECT * FROM $this->table_name WHERE $this->id_name = " . $this->getId();
      $resultat = result($sql);
      if ($resultat) {
        foreach ($resultat as $key => $val) {
          if (isset($this->$key)) {
            $this->$key = $val;
          }
        }
      }
    }
  }

  public function getId() {
    $id_name = $this->id_name;
    return $this->$id_name;
  }

  public function setId($id = '') {
    $id_name = $this->id_name;
    $this->$id_name = $id;
  }

  public function sanitize() {
    $props = get_object_vars($this);
    foreach ($props as $key => $value) {
      /*if ($key !== 'mdp') {
        if ($key === 'description'){
          $this->$key = san($this->$key, true);
        } else {
          $this->$key = san($this->$key);
        }
      }*/
      if (gettype($this->$key) === 'object') {
        foreach($this->$key as $k_key => $k_value) {
          $this->$key[$k_key] = san($k_value);
        }
      } else {
        $this->$key = san($this->$key);
      }
    }
    $id = $this->getId();
    if ($id) {
      $this->setId(intval($id));
    }
  }

  public function recover() {
    $props = get_object_vars($this);
    foreach ($props as $key => $val) {
      $this->$key = $data = p($key, $val);
    }
  }

  public function delete() {
    $id = $this->getId();
    if (check_id($id)) {
        $sql = "DELETE FROM $this->table_name WHERE $this->id_name = $id";
        return rexec($sql);
    }
  }

  public function getColumns() {
    $sql = "SELECT COLUMN_NAME, DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '$this->table_name'";
    return result($sql, 'FETCHALL');
  }

  public function save() {
    $this->sanitize();

    $columns = $this->getColumns();
    $id = $this->getId();

    if (!check_id($id)) {
      $sql_save = "INSERT INTO $this->table_name (";
      $key_list = [];
      $value_list = [];
      foreach($columns as $column) {
        $col = $column['COLUMN_NAME'];
        if ($col !== $this->id_name && isset($this->$col) && ($column['DATA_TYPE'] !== 'datetime' || $this->$col !== '')) {
          $column_value = $column['DATA_TYPE'] === 'int' && $this->$col === '' ? 0 : $this->$col;
          $key_list[] = $col;
          $value_list[] = $column_value;
        }
      }
      $sql_save .= implode(', ', $key_list);
      $sql_save .= ') VALUES ("' . implode('", "', $value_list) .'")';
    } else {
      $sql_save = "UPDATE $this->table_name SET ";
      $count = 0;
      foreach($columns as $column) {
        $col = $column['COLUMN_NAME'];
        if ($col !== $this->id_name && isset($this->$col) && ($column['DATA_TYPE'] !== 'datetime' || $this->$col !== '')) {
          $column_value = $column['DATA_TYPE'] === 'int' && $this->$col === '' ? 0 : $this->$col;
          $sql_save .= $count === 0 ? '' : ', ';
          $sql_save .= "$col = '$column_value' ";
          $count++;
        }
      }
      $sql_save .= "WHERE $this->id_name = $id";
    }
    $r = rexec($sql_save, 'ID');
    return check_id($id) ? $id : $r;
  }

  public function listAll() {
    $sql = "SELECT * FROM $this->table_name";
    return result($sql, 'FETCHALL');
  }
}
