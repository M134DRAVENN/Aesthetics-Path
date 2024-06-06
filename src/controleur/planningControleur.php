<?php
function planningControleur($twig, $db){
    $form = array();
    if (isset($_SESSION['id'])) {
        $user_id = $_SESSION['id'];
    } else {
        $form['message'] = 'Vous devez être connecté pour accéder à cette page.';
        echo $twig->render('planning.html.twig', array('form' => $form));
        return;
    }

    $seance = new Seance($db);
    $liste = $seance->selectByUserId($user_id);

    if(isset($_POST['btSupprimer'])){
        $cocher = $_POST['cocher'];
        $form['valide'] = true;
        foreach ($cocher as $id){
            $exec = $seance->delete($id);
            if (!$exec){
                $form['valide'] = false;
                $form['message'] = 'Problème de suppression dans la table seance';
            }
        }
        header('Location: index.php?page=planning');
            exit;
    }

    echo $twig->render('planning.html.twig', array('form' => $form, 'liste' => $liste));
}
?>