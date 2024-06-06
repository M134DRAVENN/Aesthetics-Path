<?php

class SeanceDetail {
    
    private $db;
    private $insert;
    private $selectBySeanceId;
    private $selectById;
    private $update;
    private $delete;

    public function __construct($db) {
        $this->db = $db;
        $this->insert = $this->db->prepare("INSERT INTO seance_detail(seance_id, exercice_id, sets) VALUES (:seance_id, :exercice_id, :sets)");
        $this->select = $this->db->prepare("SELECT * FROM seanceDetail");
        $this->selectBySeanceId = $this->db->prepare("SELECT * FROM seance_detail WHERE seance_id = :seance_id");
        $this->selectById = $this->db->prepare("SELECT * FROM seance_detail WHERE id = :id");
        $this->update = $this->db->prepare("UPDATE seance_detail SET exercice_id = :exercice_id, sets = :sets WHERE id = :id");
        $this->delete = $this->db->prepare("DELETE FROM seance_detail WHERE id = :id");
    }

    public function insert($seance_id, $exercice_id, $sets) {
        $this->insert->execute(array(
            ':seance_id' => $seance_id,
            ':exercice_id' => $exercice_id,
            ':sets' => $sets
        ));
        if ($this->insert->errorCode() != 0) {
            print_r($this->insert->errorInfo());
            return false;
        }
        return $this->db->lastInsertId();
    }

    public function select(){
        $this->select->execute();
        if ($this->select->errorCode() != 0){
            print_r($this->select->errorInfo());
        }
        return $this->select->fetchAll();
    }

    public function selectBySeanceId($seance_id) {
        $this->selectBySeanceId->execute(array(':seance_id' => $seance_id));
        if ($this->selectBySeanceId->errorCode() != 0) {
            print_r($this->selectBySeanceId->errorInfo());
        }
        return $this->selectBySeanceId->fetchAll();
    }

    public function selectById($id) {
        $this->selectById->execute(array(':id' => $id));
        if ($this->selectById->errorCode() != 0) {
            print_r($this->selectById->errorInfo());
        }
        return $this->selectById->fetch();
    }

    public function update($id, $exercice_id, $sets) {
        $r = true;
        $this->update->execute(array(':id' => $id, ':exercice_id' => $exercice_id, ':sets' => $sets));
        if ($this->update->errorCode() != 0) {
            print_r($this->update->errorInfo());
            $r = false;
        }
        return $r;
    }

    public function delete($id) {
        $r = true;
        $this->delete->execute(array(':id' => $id));
        if ($this->delete->errorCode() != 0) {
            print_r($this->delete->errorInfo());
            $r = false;
        }
        return $r;
    }
}

?>