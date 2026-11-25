<?php
/**
 * Protège un texte avant de l'afficher dans le HTML (caractères spéciaux).
 *
 * @param mixed $texte : texte à afficher.
 *
 * @return String : texte prêt à être inséré dans la page.
 */
function h($texte) {
  return htmlspecialchars((string) $texte, ENT_QUOTES, 'UTF-8');
}

/**
 * Colonne de la base de données qui contient le nom dans la langue voulue.
 * Les noms des produits et des catégories existent en français (nom)
 * et en anglais (nom_en).
 *
 * @param String $langue : code de langue ('fr', 'en').
 *
 * @return String : 'nom_en' pour l'anglais, sinon 'nom'.
 */
function colonneNom($langue) {
  return ($langue == 'en') ? 'nom_en' : 'nom';
}

/**
 * Langue choisie par le visiteur (témoin HTTP), pour les requêtes asynchrones.
 *
 * @return String : code de langue ('fr' par défaut).
 */
function langueDuVisiteur() {
  return ($_COOKIE["langueChoisie"] ?? "fr") == "en" ? "en" : "fr";
}
