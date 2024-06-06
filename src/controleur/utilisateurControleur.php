<?php
function utilisateurControleur($twig, $db){
    $form = array();
    $utilisateur = new Utilisateur($db);

    if(isset($_POST['btSupprimer'])){
        $cocher = $_POST['cocher'];
        $form['valide'] = true;
        $etat = true;
        foreach ( $cocher as $id){
            $exec=$utilisateur->deleteWithOrders($id);
            if (!$exec){
                $etat = false;
            }
        }
        header('Location: index.php?page=utilisateur&etat='.$etat);
        exit;
    }

    if(isset($_GET['id'])){
        $exec = $utilisateur->deleteWithOrders($_GET['id']);
    
        if (!$exec){
            $form['valide'] = false;
            $form['message'] = 'Problème de suppression de l\'utilisateur et de ses commandes';
        } else {
            $form['valide'] = true;
            $form['message'] = 'Utilisateur et ses commandes supprimés avec succès';
        }
    }
    
    if(isset($_GET['etat'])){
        $form['etat'] = $_GET['etat'];
    }

    $liste = $utilisateur->select();
    echo $twig->render('utilisateur.html.twig', array('form'=>$form,'liste'=>$liste));
}


function utilisateurModifControleur($twig, $db){
    $form = array(); 
    if(isset($_GET['id'])){
        $utilisateur = new Utilisateur($db);
        $unUtilisateur = $utilisateur->selectById($_GET['id']);
        if ($unUtilisateur!=null){
            $form['utilisateur'] = $unUtilisateur;
            $role = new Role($db);
            $liste = $role->select();
            $form['roles']=$liste;
        }
        else{
            $form['message'] = 'Utilisateur incorrect';
        }
    }
    else{
        if(isset($_POST['btModifier'])){
            $email = $_POST['inputEmail'];
            $mdp = $_POST['inputPassword'];
            $mdp2 = $_POST['inputPassword2'];
            $nom = $_POST['nom'];
            $prenom = $_POST['prenom'];
            $role = $_POST['role'];
            $id = $_POST['id'];

            if (empty($mdp2)==false) {
                if ($mdp!=$mdp2){
                    $form['valide'] = false;
                    $form['message'] = 'Les mots de passe sont différents';
                }
                else{
                    $utilisateur = new Utilisateur($db);
                    $exec=$utilisateur->update($id, $role, $nom, $prenom, $email, password_hash($mdp, PASSWORD_DEFAULT));
                    if(!$exec){
                        $form['valide'] = false;
                        $form['message'] = 'Echec de la modification';
                    }
                    else{
                        $form['valide'] = true;
                        $form['message'] = 'Modification réussie';
                    }
                }
            }
            else{
                $form['valide'] = false;
                $form['message'] = 'Le champ Confirmation mot de passe est vide';
            }
        }
        else{
                $form['message'] = 'Utilisateur non précisé';
        }
    }
    echo $twig->render('utilisateur-modif.html.twig', array('form'=>$form));
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function mdpOublieControleur($twig, $db) {
    $form = array();
    if (isset($_POST['email'])) {
        $email = $_POST['email'];
        $userModel = new Utilisateur($db);
        $user = $userModel->selectByEmail($email);

        if ($user) {
            $token = bin2hex(random_bytes(32));
            $userModel->storeResetToken($user['id'], $token);

            $mail = new PHPMailer(true);

            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.office365.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'aestheticspath@outlook.fr';
                $mail->Password = '21042004norep@';
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;
                $mail->CharSet = 'UTF-8';

                $mail->setFrom('aestheticspath@outlook.fr', 'aesthetics path');
                $mail->addAddress($email);

                $mail->isHTML(true);
                $mail->Subject = 'Réinitialisation de mot de passe';
                $mail->Body    = 'Cliquez sur le lien suivant pour réinitialiser votre mot de passe : ';
                $mail->Body   .= '<a href="http://127.0.0.1/Salvador/AestheticsPath/public/index.php?page=mdp_modif&token=' . $token . '">Réinitialiser le mot de passe</a>';

                $mail->send();
                $form['message'] = "Un email de réinitialisation a été envoyé.";
            } catch (Exception $e) {
                $form['message'] = "Le message n'a pas pu être envoyé. Erreur de Mailer : {$mail->ErrorInfo}";
            }
        } else {
            $form['message'] = "Aucun utilisateur trouvé avec cet email.";
        }
    }
    echo $twig->render('mdp-oublie.html.twig', array('form' => $form));
}

function mdpModifControleur($twig, $db) {
    $form = array();
    $userModel = new Utilisateur($db);

    if (isset($_GET['token'])) {
        $token = $_GET['token'];
        $user = $userModel->verifyResetToken($token);

        if ($user) {
            if (isset($_POST['inputPassword']) && isset($_POST['inputPassword2'])) {
                $password = $_POST['inputPassword'];
                $password2 = $_POST['inputPassword2'];
                if ($password != $password2) {
                    $form['valide'] = false;
                    $form['message'] = 'Les mots de passe sont différents.';
                } else {
                    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                    $updateResult = $userModel->updateMdp($user['id'], $hashedPassword);

                    if ($updateResult) {
                        $form['message'] = "Votre mot de passe a été mis à jour.";
                    } else {
                        $form['message'] = "Erreur lors de la mise à jour du mot de passe.";
                    }
                }
            } else {
                $form['token'] = $token;
            }
        } else {
            $form['message'] = "Le lien de réinitialisation est invalide.";
        }
    } else {
        $form['message'] = "Aucun token de réinitialisation fourni.";
    }

    echo $twig->render('mdp-modif.html.twig', array('form' => $form));
}
?>