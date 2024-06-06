<?php
function typeControleur($twig,$db){
    $form = array();
    $type = new Type($db);

    if(isset($_POST['btSupprimer'])){
        $cocher = $_POST['cocher'];
        $form['valide'] = true;
        $etat = true;
        foreach ( $cocher as $id){
            $exec=$type->delete($id);
            if (!$exec){
                $etat = false;
            }
        }
        header('Location: index.php?page=Type&etat='.$etat);
        exit;
    }

    if(isset($_GET['id'])){
        $exec=$type->delete($_GET['id']);
        if (!$exec){
            $etat = false;
        }else{
            $etat = true;
        }
        header('Location: index.php?page=Type&etat='.$etat);
        exit;
    }

    if(isset($_GET['etat'])){
        $form['etat'] = $_GET['etat'];
    }

    $liste = $type->select();
    echo $twig->render('type.html.twig', array('form'=>$form,'liste'=>$liste));
}

function addTypeControleur($twig, $db){
    $form = array();

    if(isset($_POST['btAjouter'])){
        $type = new Type($db);
        $libelle = $_POST['inputLibelle'];
        $exec = $type->insert($libelle);
        if(!$exec){
            $form['valide'] = false;
            $form['message'] = 'Problème d\'insertion dans la table type';
        }
        else{
            $form['valide'] = true;
            $form['message'] = 'Type ajouté avec succès';
        }
    }


    echo $twig->render('addType.html.twig', array());
    
}

function typeModifControleur($twig, $db){
    $form = array(); 
    if(isset($_GET['id'])){
        $type = new Type($db);
        $untype = $type->selectById($_GET['id']);
        if ($untype!=null){
            $form['type'] = $untype;
        }
        else{
            $form['message'] = 'Type incorrect';
        }
    }
    else{
        if(isset($_POST['btModifier'])){
            $libelle = $_POST['inputLibelle'];
            $id = $_POST['id'];

            if (empty($libelle)==false) {
                    $type = new Type($db);
                    $exec=$type->update($id, $libelle);
                    if(!$exec){
                        $form['valide'] = false;
                        $form['message'] = 'Echec de la modification';
                    }
                    else{
                        $form['valide'] = true;
                        $form['message'] = 'Modification réussie';
                    }
            }
            else{
                $form['valide'] = false;
                $form['message'] = 'Le champ est vide';
            }
        }
        else{
                $form['message'] = 'Type non précisé';
        }
    }
    echo $twig->render('type-modif.html.twig', array('form'=>$form));
}
?>