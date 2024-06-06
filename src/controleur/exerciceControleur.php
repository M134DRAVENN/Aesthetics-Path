<?php

function exerciceControleur($twig,$db){
    $form = array();
    $exercice = new Exercice($db);

    if(isset($_POST['btSupprimer'])){
        $cocher = $_POST['cocher'];
        $form['valide'] = true;
        foreach ( $cocher as $id){
            $exec=$exercice->delete($id);
            if (!$exec){
                $form['valide'] = false;
                $form['message'] = 'Problème de suppression dans la table exercice';
            }
        }
    }
    
    if(isset($_GET['id'])){
        $exec=$exercice->delete($_GET['id']);
        if (!$exec){
            $form['valide'] = false;
            $form['message'] = 'Problème de suppression dans la table exercice';
        }else{
            $form['valide'] = true;
            $form['message'] = 'Exercice supprimé avec succès';
        }
    }
    $limite=5;

    if(!isset($_GET['nopage'])){
        $inf=0;
        $nopage=0;
    }
    else{
        $nopage=$_GET['nopage'];
        $inf=$nopage * $limite;
    }
    $r = $exercice->selectCount();
    $nb = $r['nb'];
    $liste = $exercice->selectLimit($inf,$limite);
    $form['nbpages'] = ceil($nb/$limite);
    $form['nopage'] = $nopage;
    echo $twig->render('Exercice.html.twig', array('form'=>$form,'liste'=>$liste));
   }

function addExerciceControleur($twig,$db){
    $form = array();
    if (isset($_POST['btAjouter'])){
        $photo = null;
        $exercice = new Exercice($db);
        $inputDesignation = $_POST['inputDesignation'];
        $inputDescription = $_POST['inputDescription'];
        $idType = $_POST['idType'];

        $upload = new Upload(array('png', 'gif', 'jpg', 'jpeg', 'PNG', 'GIF', 'JPG', 'JPEG', 'jfif', 'JFIF'), 'images', 500000);
        $photo = $upload->enregistrer('photo');

        $exec=$exercice->insert($inputDesignation, $inputDescription, $idType, $photo['nom']);
        if (!$exec){
            $form['valide'] = false;
            $form['message'] = 'Problème d\'incsription';
        }
        else{
                $form['valide'] = true;
            }
        }
    echo $twig->render('addExercice.html.twig', array());
}

function exerciceModifControleur($twig, $db){
    $form = array();
    if(isset($_GET['id'])){
        $exercice = new Exercice($db);
        $unExercice = $exercice->selectById($_GET['id']); 
        if ($unExercice!=null){
            $form['exercice'] = $unExercice;
        }
        else{
            $form['message'] = 'exercice incorrect';
        }
    }
    else{
        if(isset($_POST['btModifier'])){
            $photo = $_POST['photo'];
            $id = $_POST['id'];
            $designation = $_POST['inputDesignation'];
            $description = $_POST['inputDescription'];
            $type = $_POST['inputLibelle'];
            if(empty($designation)){
                $form['valide'] = false;
                $form['message'] = 'Le champ est vide';
            }
            else{
            $exercice = new Exercice($db);
            $exec=$exercice->update($id, $designation, $description, $type, $photo);
                if(!$exec){
                    $form['valide'] = false;
                    $form['message'] = 'Echec de la modification';
                }else{
                    $form['valide'] = true;
                    $form['message'] = 'Modification réussie';
                }
            }
        }else{
            $form['message'] = 'exercice non précisé';
        }
    }
    echo $twig->render('exercice-modif.html.twig', array('form'=>$form));
}

function exerciceficheControleur($twig, $db){
    $form = array();
    $exercice = new Exercice($db);

    if(isset($_GET['id'])){
        $unExercice = $exercice->selectById($_GET['id']);
        if($unExercice!=null){
            $form['exercice'] = $unExercice;
        }
        else{
            $form['message'] = 'Exercice incorrect';
        }
    }
    else{
        $form['message'] = 'Exercice non précisé';
    }
    echo $twig->render('exercice-fiche.html.twig', array('form'=>$form));
}
?>