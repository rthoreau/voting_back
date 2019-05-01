<?php
require_once 'base.php';
headers();
$operation = p('operation');
$r = [];
if ($operation) {
  if ($operation === 'vote') {
    $r['error'] = 1;
    $vote = new Vote();
    $vote->nickname = p('nickname');
    if ($vote->vote_exists()) {
      $r['message'] = 'Un seul vote par personne !';
      echo json_encode($r);
      return;
    }
    $r['id'] = $vote->save();
    $votes = p('votes');
    foreach($votes as $v) {
      $sql = 'INSERT INTO candidates_votes (id_vote, id_candidate, note) VALUES ('
        . $r['id']. ','
        . san($v->id_candidate) . ','
        . san($v->note) . ')';
      rexec($sql);
    }
    $r['message'] = 'Merci !';
    $r['error'] = 0;
  }
  if ($operation === 'get_candidates') {
    $sql = 'SELECT * FROM candidates';
    $result = result($sql, 'FETCHALL');
    $r['candidates'] = $result;
  }
}
if (empty($r)) {
  return;
}
echo json_encode($r);
