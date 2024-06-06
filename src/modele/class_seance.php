<?php
class Seance {

    private $db;
    private $insert;
    private $selectByUserId;
    private $select;
    private $selectById;
    private $update;
    private $delete;
    private $selectCount;
    private $selectLimit;

    public function __construct($db){
        $this->db = $db;
        $this->insert = $this->db->prepare("INSERT INTO seance(user_id, name) VALUES (:user_id, :name)");
        $this->selectByUserId = $this->db->prepare("SELECT * FROM seance WHERE user_id = :user_id");
        $this->select = $this->db->prepare("SELECT * FROM seance");
        $this->selectById = $this->db->prepare("SELECT * FROM seance WHERE id=:id");
        $this->update = $this->db->prepare("UPDATE seance SET name=:name WHERE id=:id");
        $this->delete = $this->db->prepare("DELETE FROM seance WHERE id = :id");
        $this->selectCount = $this->db->prepare("SELECT COUNT(*) AS nb FROM seance");
        $this->selectLimit = $this->db->prepare("SELECT * FROM seance LIMIT :inf, :limite");
    }

    public function insert($user_id, $name){
        $r = true;
        $this->insert->execute(array(':user_id' => $user_id, ':name' => $name));
        if ($this->insert->errorCode() != 0){
            print_r($this->insert->errorInfo());
            $r = false;
        }
        return $r;
    }

    public function selectByUserId($user_id){
        $this->selectByUserId->execute(array(':user_id' => $user_id));
        if ($this->selectByUserId->errorCode() != 0){
            print_r($this->selectByUserId->errorInfo());
        }
        return $this->selectByUserId->fetchAll();
    }

    public function select(){
        $this->select->execute();
        if ($this->select->errorCode() != 0){
            print_r($this->select->errorInfo());
        }
        return $this->select->fetchAll();
    }

    public function selectById($id){
        $this->selectById->execute(array(':id' => $id));
        if ($this->selectById->errorCode() != 0){
            print_r($this->selectById->errorInfo());
        }
        return $this->selectById->fetch();
    }

    public function update($id, $name){
        $r = true;
        $this->update->execute(array(':id' => $id, ':name' => $name));
        if ($this->update->errorCode() != 0){
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

    public function selectCount(){
        $this->selectCount->execute();
        if ($this->selectCount->errorCode() != 0){
            print_r($this->selectCount->errorInfo());
        }
        return $this->selectCount->fetch();
    }

    public function selectLimit($inf, $limite){
        $this->selectLimit->bindValue(':inf', (int) $inf, PDO::PARAM_INT);
        $this->selectLimit->bindValue(':limite', (int) $limite, PDO::PARAM_INT);
        $this->selectLimit->execute();
        if ($this->selectLimit->errorCode() != 0){
            print_r($this->selectLimit->errorInfo());
        }
        return $this->selectLimit->fetchAll();
    }
}
?>