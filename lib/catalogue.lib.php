<?php 
/**
 * Obtenir toutes les catégories, avec leur nom dans la langue voulue.
 * 
 * @param Object $cnx : objet de connexion BD.
 * @param String $langue : code de langue ('fr' par défaut).
 * 
 * @return Array : tableau numérique contenant les catégories, chacune sous 
 *                 forme d'un tableau associatif (id, nom).
 */
function obtenirCategories($cnx, $langue='fr') {
  $colNom = colonneNom($langue);
  return lire($cnx, "SELECT id, $colNom AS nom FROM categorie ORDER BY id");
}

/**
 * Obtenir les produits, éventuellement filtrés, cherchés et triés.
 * 
 * @param Object $cnx : objet de connexion BD.
 * @param String $tri : colonne du tri ou 'RAND()' (valeur par défaut).
 * @param Int|String $filtre : identifiant de la catégorie ou 'tous' (valeur par défaut).
 * @param String $recherche : mots à chercher dans le nom du produit ('' = aucun).
 * @param String $langue : code de langue des noms retournés ('fr' par défaut).
 * 
 * @return Array : tableau numérique contenant les produits, chacun sous 
 *                 forme d'un tableau associatif.
 */
function obtenirProduits($cnx, $tri='RAND()', $filtre='tous', $recherche='', $langue='fr') {
  $colNom = colonneNom($langue);
  $conditions = [];

  // Filtre par catégorie (sauf pour « tous »)
  if ($filtre != 'tous') {
    $conditions[] = "id_categorie = " . (int) $filtre;
  }

  // Recherche dans le nom français et anglais (caractères spéciaux de LIKE neutralisés)
  $recherche = trim($recherche);
  if ($recherche !== '') {
    $motif = addcslashes(mysqli_real_escape_string($cnx, $recherche), '%_');
    $conditions[] = "(nom LIKE '%$motif%' OR nom_en LIKE '%$motif%')";
  }

  $clauseWhere = count($conditions) > 0 ? " WHERE " . implode(" AND ", $conditions) : "";

  // Seuls ces tris sont acceptés (protection contre l'injection SQL).
  $tris = [
    'RAND()'      => 'RAND()',
    'prix ASC'    => 'prix ASC',
    'prix DESC'   => 'prix DESC',
    'nom ASC'     => "$colNom ASC",
    'nom DESC'    => "$colNom DESC",
    'dac DESC'    => 'dac DESC',
    'ventes DESC' => 'ventes DESC'
  ];
  $ordre = $tris[$tri] ?? 'RAND()';

  return lire($cnx, "SELECT id, $colNom AS nom, prix, ventes, dac, image, id_categorie
                       FROM produit $clauseWhere ORDER BY $ordre");
}
