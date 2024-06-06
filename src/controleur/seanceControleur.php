<?php

function seanceControleur($twig, $db){
    $form = array();

    if (isset($_SESSION['id'])) {
        $user_id = $_SESSION['id'];
        $seanceModel = new Seance($db);
        $seanceDetailModel = new SeanceDetail($db);
        $seanceRepsModel = new SeanceReps($db);
        $exercice = new Exercice($db);

        if (isset($_GET['id'])) {
            $seance_id = $_GET['id'];
        } else {
            $form['message'] = 'Séance non spécifiée.';
            echo $twig->render('seance.html.twig', array('form' => $form));
            return;
        }

        $seance = $seanceModel->selectById($seance_id);

        if (!$seance || $seance['user_id'] != $user_id) {
            $form['message'] = 'Séance non trouvée ou non autorisée.';
            echo $twig->render('seance.html.twig', array('form' => $form));
            return;
        }

        $details = $seanceDetailModel->selectBySeanceId($seance_id);
        $maxSets = 0;

        foreach ($details as &$detail) {
            $exerciceInfo = $exercice->selectById($detail['exercice_id']);
            $detail['designation'] = $exerciceInfo['designation'];
            $reps = $seanceRepsModel->selectBySeanceDetailId($detail['id']);
            $detail['reps'] = $reps;
            $maxSets = max($maxSets, count($reps));
        }

        echo $twig->render('seance.html.twig', array(
            'form' => $form,
            'seanceDetails' => array('seance' => $seance, 'details' => $details),
            'maxSets' => $maxSets
        ));
    } else {
        $form['message'] = 'Vous devez être connecté pour accéder à cette page.';
        echo $twig->render('seance.html.twig', array('form' => $form));
        return;
    }

    if(isset($_POST['btSupprimer'])){
        $cocher = $_POST['cocher'];
        $form['valide'] = true;
        foreach ($cocher as $id){
            $exec = $seanceDetailModel->delete($id);
            if (!$exec){
                $form['valide'] = false;
                $form['message'] = 'Problème de suppression dans la table seance';
            }
        }
        header('Location: index.php?page=seance');
        exit;
    }
}

function addSeanceControleur($twig, $db) {
    $form = array();
    $seance = new Seance($db);
    if (isset($_POST['btAjouter'])) {
        if (isset($_SESSION['id'])) {
            $user_id = $_SESSION['id'];
            $name = $_POST['inputName'];
            $exec = $seance->insert($user_id, $name);
            if (!$exec) {
                $form['valide'] = false;
                $form['message'] = 'Problème d\'insertion';
            } else {
                $form['valide'] = true;
                $form['message'] = 'Séance ajoutée avec succès';
            }
        } else {
            $form['valide'] = false;
            $form['message'] = 'Utilisateur non connecté';
        }
    }
    echo $twig->render('addSeance.html.twig', array('form' => $form));
}

function seanceModifControleur($twig, $db){
    $form = array();
    if(isset($_GET['id'])){
        $seance = new Seance($db);
        $uneSeance = $seance->selectById($_GET['id']); 
        if ($uneSeance != null){
            $form['seance'] = $uneSeance;
        } else {
            $form['message'] = 'Séance incorrecte';
        }
    } else {
        if(isset($_POST['btModifier'])){
            $id = $_POST['id'];
            $name = $_POST['inputName'];
            if(empty($name)){
                $form['valide'] = false;
                $form['message'] = 'Le champ est vide';
            } else {
                $seance = new Seance($db);
                $exec = $seance->update($id, $name);
                if(!$exec){
                    $form['valide'] = false;
                    $form['message'] = 'Echec de la modification';
                } else {
                    $form['valide'] = true;
                    $form['message'] = 'Modification réussie';
                }
            }
        } else {
            $form['message'] = 'Séance non précisée';
        }
    }
    echo $twig->render('seance-modif.html.twig', array('form' => $form));
}

function addExerciceSeanceControleur($twig, $db) {
    $form = array();

    if (isset($_SESSION['id'])) {
        $user_id = $_SESSION['id'];
    } else {
        $form['message'] = 'Vous devez être connecté pour accéder à cette page.';
        echo $twig->render('addExerciceSeance.html.twig', array('form' => $form));
        return;
    }

    if (isset($_GET['seance_id'])) {
        $seance_id = $_GET['seance_id'];

        $exerciceModel = new Exercice($db);
        $exercices = $exerciceModel->selectAll();

        if (isset($_POST['btAjouter'])) {
            $seanceDetailModel = new SeanceDetail($db);
            $seanceRepsModel = new SeanceReps($db);

            $exercice_id = $_POST['exerciceId'];
            $sets = $_POST['inputSets'];
            $reps = $_POST['inputReps'];
            $weight = $_POST['inputCharge'];
            $rest_time = $_POST['inputRest_time'];

            $seance_detail_id = $seanceDetailModel->insert($seance_id, $exercice_id, $sets);
            
            if ($seance_detail_id) {
                for ($i = 0; $i < $sets; $i++) {
                    $seanceRepsModel->insert($seance_detail_id, $i + 1, $reps[$i], $weight[$i], $rest_time[$i]);
                }
                $form['message'] = 'Exercice ajouté avec succès.';
            } else {
                $form['message'] = 'Erreur lors de l\'ajout de l\'exercice.';
            }
        }

        echo $twig->render('addExerciceSeance.html.twig', array(
            'form' => $form,
            'seance_id' => $seance_id,
            'exercices' => $exercices
        ));
    } else {
        $form['message'] = 'Aucune séance spécifiée.';
        echo $twig->render('addExerciceSeance.html.twig', array('form' => $form));
    }
}
?>