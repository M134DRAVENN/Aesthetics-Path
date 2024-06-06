<?php
class Upload {

    private $extensions;
    private $chemin;
    private $taille;

    public function __construct($extensions, $chemin, $taille) {
        $this->extensions = $extensions;
        $this->chemin = $chemin;
        $this->taille = $taille;
    }

    public function enregistrer($data) {
        $fichier = array();
        $fichier['nom'] = null;
        $fichier['erreur'] = null;
        $msg = null;
        
        if (isset($_FILES[$data])) {
            if (!empty($_FILES[$data]['name'])) {
                if (!in_array(pathinfo($_FILES[$data]['name'], PATHINFO_EXTENSION), $this->extensions)) {
                    $msg = 'Veuillez sélectionner un fichier de type : ' . implode(', ', $this->extensions);
                } else {
                    if (file_exists($_FILES[$data]['tmp_name']) && filesize($_FILES[$data]['tmp_name']) > $this->taille) {
                        $msg = 'Votre fichier doit faire moins de ' . $this->taille . 'Ko !';
                    } else {
                        $identifiantUnique = uniqid();
                        $extension = pathinfo($_FILES[$data]['name'], PATHINFO_EXTENSION);
                        $nouveauNom = $identifiantUnique . '.' . $extension;
                        $fichier['nom'] = $nouveauNom;
                        move_uploaded_file($_FILES[$data]['tmp_name'], $this->chemin . '/../images/' . $fichier['nom']);
                    }
                }
            }
        }
        $fichier['erreur'] = $msg;
        return $fichier;
    }
}
?>