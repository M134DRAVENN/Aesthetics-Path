<?php
Class Exercice {

    private $db;
    private $insert;
    private $select;
    private $selectById;
    private $selectByType;
    private $selectAll;
    private $update;
    private $delete;
    private $selectLimit;
    private $selectCount; 
    private $recherche;
    private $selectIn;

    
    public function __construct($db){
        $this->db = $db;
        $this->insert = $this->db->prepare("insert into exercice(designation, description, idType, photo) values (:designation, :description, :idType, :photo)");
        $this->select = $db->prepare("select p.id, designation, description, t.libelle as type, photo from exercice p, type t where p.idType = t.id order by p.id");
        $this->selectById = $db->prepare("select p.id, designation, description, t.libelle as type, photo from exercice p JOIN type t ON p.idType = t.id where p.id=:id");
        $this->selectByType = $db->prepare("select p.id, designation, description, t.libelle as type, photo from exercice p, type t where p.idType=:idType");
        $this->selectAll = $db->prepare("SELECT * FROM exercice");
        $this->update = $db->prepare("update exercice set designation=:designation, description=:description, idType=:idType, photo=:photo where id=:id");
        $this->delete = $db->prepare("delete from exercice where id=:id");
        $this->selectLimit = $db->prepare("select p.id, designation, description, t.libelle as type, photo from exercice p, type t where p.idType = t.id order by designation limit :inf,:limite");
        $this->selectCount =$db->prepare("select count(*) as nb from exercice");
        $this->recherche = $db->prepare("select p.id,designation,description,photo,t.libelle as type from exercice p,type t where p.idType = t.id and (designation like :recherche or description like :recherche) order by designation");
        $this->selectIn = $this->db->prepare("select p.id, p.designation, p.description, p.photo, t.libelle as type from exercice p JOIN type t ON p.idType = t.id WHERE FIND_IN_SET(p.id, :ids)");
    }
    
    public function insert($designation, $description, $type, $photo) {
        $r = true;
        $this->insert->execute(array(':designation' => $designation, ':description' => $description, ':idType' => $type, ':photo' => $photo));
        if ($this->insert->errorCode() != 0) {
            print_r($this->insert->errorInfo());
            $r = false;
        }
        return $r;
    }

    public function select(){
        $this->select->execute();
        if ($this->select->errorCode()!=0){
            print_r($this->select->errorInfo());
        }
        return $this->select->fetchAll();
    }

    public function selectById($id) {
        $this->selectById->execute(array(':id' => $id));
        if ($this->selectById->errorCode() != 0) {
            print_r($this->selectById->errorInfo());
        }
        return $this->selectById->fetch();
    }

    public function selectByType($idType) {
        $this->selectByType->execute(array(':idType' => $idType));
        if ($this->selectByType->errorCode() != 0) {
            print_r($this->selectByType->errorInfo());
        }
        return $this->selectByType->fetch();
    }

    public function selectAll() {
        $this->selectAll->execute();
        if ($this->selectAll->errorCode() != 0) {
            print_r($this->selectAll->errorInfo());
        }
        return $this->selectAll->fetchAll();
    }

    public function update($id, $designation, $description, $type, $photo){
        $r = true;
        $this->update->execute(array(':id'=>$id,':designation'=>$designation,':description'=>$description ,':idType'=>$type, ':photo' => $photo));
        if ($this->update->errorCode()!=0){ print_r($this->update->errorInfo());
        $r=false;
        }
        return $r;
    }

    public function delete($id){
        $r = true;
        $this->delete->execute(array(':id' => $id));
        if ($this->delete->errorCode() != 0){
            print_r($this->delete->errorInfo());
            $r = false;
        }
        return $r;
    }

    public function selectLimit($inf, $limite){
        $this->selectLimit->bindParam(':inf', $inf, PDO::PARAM_INT);
        $this->selectLimit->bindParam(':limite', $limite, PDO::PARAM_INT);
        $this->selectLimit->execute();
        if ($this->selectLimit->errorCode()!=0){
        print_r($this->selectLimit->errorInfo());
        }
        return $this->selectLimit->fetchAll();
    }

    public function selectCount(){
        $this->selectCount->execute();
        if ($this->selectCount->errorCode()!=0){
        print_r($this->selectCount->errorInfo());
        }
        return $this->selectCount->fetch();
    }

    public function recherche($recherche){
        $this->recherche->execute(array('recherche'=>'%'.$recherche.'%'));
        if ($this->recherche->errorCode()!=0){
        print_r($this->recherche->errorInfo());
        }
        return $this->recherche->fetchAll();
    }

    public function selectIn($ids){
        $implose = implode(',', $ids);
        $this->selectIn->bindParam(':ids', $implose);
        $this->selectIn->execute();
        if ($this->selectIn->errorCode()!=0){
        print_r($this->selectIn->errorInfo());
        }
        return $this->selectIn->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>