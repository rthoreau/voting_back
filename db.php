<?php

try{
  $db = new PDO('mysql:host='.HOST.';charset=utf8;dbname='.DATABASE, USERNAME, PASSWORD);
}catch (Exception $e){
  die('La base de données est inaccessible !' . $e);
}

function rexec($query, $retour = 'ID'){
    global $db;
    $query = $db->prepare($query);
    $result = $query->execute();
    if (!$result){
      echo 'Une erreur est survenue !';
    }
    if ('ID') {
      return $db->lastInsertId();
    }
    return $result;
}

function result($query, $mode = 'FETCH'){
    global $db;
    $result = $db->query($query);
    $r = false;
    if ($mode === 'FETCH'){
      $r = $result->fetch(PDO::FETCH_ASSOC);
    }else if ($mode === 'FETCHALL'){
      $r = $result->fetchAll(PDO::FETCH_ASSOC);
    }else if ($mode === 'FIRST'){
      $r = $result->fetch(PDO::FETCH_BOTH)[0];
    }
    return $r;
}
