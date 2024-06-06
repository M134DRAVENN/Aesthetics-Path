<?php
function accueilControleur($twig){
    echo $twig->render('accueil.html.twig', array());
}
function contactControleur($twig){
    echo $twig->render('contact.html.twig', array());
}
function mentionControleur($twig){
    echo $twig->render('mentionlegal.html.twig', array());
}
function politiqueControleur($twig){
    echo $twig->render('Politique.html.twig', array());
}
function maintenanceControleur($twig){
    echo $twig->render('maintenance.html.twig', array());
}
function rechercheControleur($twig, $db){
    $form = array();
    $exercice = new Exercice($db);

    $limite = 9;
    $nopage = isset($_GET['nopage']) ? (int)$_GET['nopage'] : 0;
    $inf = $nopage * $limite;

    if (isset($_GET['recherche'])) {
        $recherche = $_GET['recherche'];
        $resultats = $exercice->recherche($recherche);
        $nb = count($resultats);
        $resultats_pagination = array_slice($resultats, $inf, $limite);

        $form['nbpages'] = ceil($nb / $limite);
        $form['nopage'] = $nopage;
        
        echo $twig->render('recherche.html.twig', array(
            'resultats' => $resultats_pagination,
            'recherche' => $recherche,
            'form' => $form,
            'exercice' => $exercice
        ));
        return;
    }
}
?>