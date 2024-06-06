<?php
function getPage($db){
    $lesPages['accueil'] = "accueilControleur;0";
    $lesPages['contact'] = "contactControleur;0";
    $lesPages['apropos'] = "aproposControleur;0";
    $lesPages['mention'] = "mentionControleur;0";
    $lesPages['politique'] = "politiqueControleur;0";
    $lesPages['inscrire'] = "inscrireControleur;0";
    $lesPages['maintenance'] = "maintenanceControleur;0";
    $lesPages['connexion'] = "connexionControleur;0";
    $lesPages['deconnexion'] = "deconnexionControleur;0";
    $lesPages['mdp_oublie'] = "mdpOublieControleur;0";
    $lesPages['mdp_modif'] = "mdpModifControleur;0";
    $lesPages['utilisateur'] = "utilisateurControleur;1";
    $lesPages['utilisateurModif'] = "utilisateurModifControleur;1";
    $lesPages['AddExercice'] = "addExerciceControleur;1";
    $lesPages['Exercice'] = "exerciceControleur;1";
    $lesPages['exerciceModif'] = "exerciceModifControleur;1";
    $lesPages['AddType'] = "addTypeControleur;1";
    $lesPages['Type'] = "typeControleur;1";
    $lesPages['typeModif'] = "typeModifControleur;1";
    $lesPages['recherche'] = "rechercheControleur;0";
    $lesPages['panier'] = "panierControleur;0";
    $lesPages['exercicefiche'] = "exerciceFicheControleur;0";
    $lesPages['planning'] = "planningControleur;0";
    $lesPages['Seance'] = "seanceControleur;0";
    $lesPages['AddSeance'] = "addSeanceControleur;0";
    $lesPages['AddExerciceSeance'] = "addExerciceSeanceControleur;0";
    $lesPages['seanceModif'] = "seanceModifControleur;0";
    

    if ($db!=null){
        if (isset($_GET['page'])){
            $page = $_GET['page'];
        }else{
            $page = 'accueil';
        }
        if (!isset($lesPages[$page])){
            $page = 'accueil';
        }
        $explose = explode(";",$lesPages[$page]);
        $role = $explose[1];
        if ($role != 0){
            if(isset($_SESSION['login'])){
                if(isset($_SESSION['role'])){
                    if($role!=$_SESSION['role']){
                        $contenu = 'accueilControleur';
                    }else{
                        $contenu = $explose[0];
                    }
                }else{
                $contenu = 'accueilControleur';;
                }
            }else{
            $contenu = 'accueilControleur';;
            }
        }else{
            $contenu = $explose[0];
        }
        }else{
            $contenu = $lesPages['maintenance'];
    }
    return $contenu;
}
?>