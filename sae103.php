<?php
// Script qui permet de récupérer les données d'un fichier texte et de les stocker dans nos fichiers
// Récupération du contenu de tout les fichiers txt du dossier utilisation de Joker qui reperent juste le .txt et qui execute sur tout les .txt
$fileToTreat = glob(__DIR__.'/fichiers.txt/*.txt');
// Récupération du contenu du fichier
foreach($fileToTreat as $file){
    $content = file_get_contents($file);
    // Récuperation du nom du fichier pris en compte
    $fileName = pathinfo(basename($file), PATHINFO_FILENAME);
    // Récupération du titre de la page 1 compris entre les balises DEBUT_TITRE et FIN_TITRE
    $debutTitre = strpos($content,'DEBUT_TITRE') + strlen('DEBUT_TITRE');
    $finTitre = strpos($content,'FIN_TITRE');
    $titre = trim(substr($content,$debutTitre,$finTitre-$debutTitre));
    // Récupération du texte de la page 1 compris entre les premières balises DEBUT_TEXTE et FIN_TEXTE
    $premierDebutTexte = strpos($content,'DEBUT_TEXTE') + strlen('DEBUT_TEXTE');
    $premierFinTexte = strpos($content,'FIN_TEXTE');
    $descriptionTitre = trim(substr($content,$premierDebutTexte,$premierFinTexte-$premierDebutTexte));
    // Récupération du titre du texte 2 compris entre les balises DEBUT_TITRE et FIN_TITRE
    $debutTitre2 = strpos($content,'DEBUT_TITRE',$premierFinTexte) + strlen('DEBUT_TITRE');  // on commence à la fin du premier texte
    $finTitre2 = strpos($content,'FIN_TITRE',$debutTitre2);
    $titre2 = trim(substr($content,$debutTitre2,$finTitre2-$debutTitre2));
    // Récupération du texte de la page 1 compris entre les balises DEBUT_TEXTE et FIN_TEXTE
    $debutTexte = strpos($content,'DEBUT_TEXTE',$premierFinTexte) + strlen('DEBUT_TEXTE');  // on commence à la fin du premier texte 
    $FinTexte = strpos($content,'FIN_TEXTE',$debutTexte);
    $description = trim(substr($content,$debutTexte,$FinTexte-$debutTexte));

    //Ajoute dans notre fichier $fileName_texte.dat toute les données récupérées précédemment portant les noms de variables $titre, $descriptiontitre, $titre2, $description

    file_put_contents(__DIR__.'/data_extraite/textes/'.$fileName.'_texte.dat',json_encode([
        'titre' => $titre,
        'descriptionTitre' => $descriptionTitre,
        'titre2' => $titre2,
        'description' => $description
    ]));

   // Récupération des stats
   $debutStats = strpos($content,'DEBUT_STATS') + strlen('DEBUT_STATS');
   $finStats = strpos($content,'FIN_STATS');
   $stats = trim(substr($content,$debutStats,$finStats-$debutStats));



    // Ajoute dans notre fichier $fileName_stats.dat toute les données récupérées précédemment portant le nom de variable $stats avec une meilleure possibilité de travail des données donc séparée en plusieurs colonnes et titres
    file_put_contents(__DIR__.'/data_extraite/tableaux/'.$fileName.'_tableau.dat',json_encode([
        // Pour chaque antislash on crée une nouvelle colonne
        'stats' => explode('\\',$stats)
    ]));

    // Récupération des commerciaux
    $debutMeilleursCommerciaux = strpos($content, 'MEILLEURS:') + strlen('MEILLEURS:');
    $finMeilleursCommerciaux = strpos($content, PHP_EOL, $debutMeilleursCommerciaux);

    $meilleurs = trim(substr($content,$debutMeilleursCommerciaux,$finMeilleursCommerciaux-$debutMeilleursCommerciaux));

    // Ajoute dans notre fichier $fileName_commerciaux.dat toute les données récupérées précédemment portant le nom de variable $meilleurs avec une meilleure possibilité de travail des données donc séparée en plusieurs colonnes et titres
    file_put_contents(__DIR__.'/data_extraite/comm/'.$fileName.'_comm.dat',json_encode([
        // Pour chaque antislash on crée une nouvelle colonne
        'meilleurs' => explode('\\',$meilleurs)
    ]));

}

?>
