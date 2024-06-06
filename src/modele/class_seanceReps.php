<?php
class SeanceReps {
    private $db;
    private $insert;

    public function __construct($db) {
        $this->db = $db;
        $this->insert = $this->db->prepare("INSERT INTO seance_reps (seance_detail_id, set_number, reps, weight, rest_time) VALUES (:seance_detail_id, :set_number, :reps, :weight, :rest_time)");
        $this->selectBySeanceDetailId = $this->db->prepare("SELECT * FROM seance_reps WHERE seance_detail_id = :seance_detail_id ORDER BY set_number");
        $this->update = $this->db->prepare("UPDATE seance_reps SET set_number = :set_number, reps = :reps, weight = :weight, rest_time = :rest_time WHERE id = :id");
    }

    public function insert($seance_detail_id, $set_number, $reps, $weight, $rest_time) {
        $this->insert->execute(array(
            ':seance_detail_id' => $seance_detail_id,
            ':set_number' => $set_number,
            ':reps' => $reps,
            ':weight' => $weight,
            ':rest_time' => $rest_time
        ));
        if ($this->insert->errorCode() != 0) {
            print_r($this->insert->errorInfo());
            return false;
        }
        return true;
    }

    public function selectBySeanceDetailId($seance_detail_id) {
        $this->selectBySeanceDetailId->execute(array(':seance_detail_id' => $seance_detail_id));
        if ($this->selectBySeanceDetailId->errorCode() != 0) {
            print_r($this->selectBySeanceDetailId->errorInfo());
        }
        return $this->selectBySeanceDetailId->fetchAll();
    }

    public function update($id, $set_number, $reps, $weight, $rest_time) {
        $r = true;
        $this->update->execute(array(':id' => $id, ':set_number' => $set_number, 'reps' => $reps, 'weight' => $weight, 'rest_time' => $rest_time));
        if ($this->update->errorCode() != 0) {
            print_r($this->update->errorInfo());
            $r = false;
        }
        return $r;
    }
}
?>