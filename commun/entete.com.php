<?php
session_start();

// Configuration, accès à la base de données et fonctions utilitaires
include("config/config.php");
include("lib/sql.lib.php");
include("lib/outils.lib.php");
$cnx = connexion();

/*
	Langues disponibles : un fichier « code-nom.json » par langue dans i18n/
*/
$languesDispos = [];
foreach (scandir("i18n") as $nomFichier) {
	if ($nomFichier != '.' && $nomFichier != '..') {
		// « fr-français.json » -> code « fr », nom « français »
		[$code, $nom] = explode("-", substr($nomFichier, 0, -5));
		$languesDispos[$code] = $nom;
	}
}

/*
	Langue choisie : français par défaut, sinon celle gardée dans le témoin
	HTTP, ou celle demandée dans l'URL (?lan=en), qu'on garde alors 90 jours.
*/
$codeLangue = "fr";
if (isset($_COOKIE["langueChoisie"]) && isset($languesDispos[$_COOKIE["langueChoisie"]])) {
	$codeLangue = $_COOKIE["langueChoisie"];
}
if (isset($_GET["lan"]) && isset($languesDispos[$_GET["lan"]])) {
	$codeLangue = $_GET["lan"];
	setcookie("langueChoisie", $codeLangue, time() + 3600 * 24 * 90);
}

$textes = json_decode(file_get_contents("i18n/" . $codeLangue . "-" . $languesDispos[$codeLangue] . ".json"), true);

/*
	Panier d'achats
*/
include("lib/panier.lib.php");

// Identifiant du panier : session, sinon témoin HTTP, sinon nouveau panier
$idPanier = etablirPanier($cnx);

// Contenu du panier (utilisé dans l'entête de toutes les pages et sur la page panier)
$detailPanier = obtenirDetailPanier($cnx, $idPanier, $codeLangue);
$nbArticles = calculerNombreArticles($detailPanier);
$sum = calculerSousTotal($detailPanier);

// Raccourcis vers les textes : page courante, entête, pied de page
$_ = $textes[$page];
$_ent = $textes["entete"];
$_pp = $textes["pp"];
?>
<!DOCTYPE html>
<html lang="<?= $codeLangue; ?>">

<head>
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Noto+Sans:wght@400;500;900&family=Noto+Serif:ital,wght@0,400;0,900;1,400&display=swap" rel="stylesheet">
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	<meta charset="UTF-8">
	<meta name="color-scheme" content="light only">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Atelier Lumen</title>
	<meta name="description" content="<?= h($_['metaDesc']); ?>">
	<link rel="stylesheet" href="css/theme.css">
	<link rel="stylesheet" href="css/sty.css">
	<link rel="icon" type="image/png" href="images/favicon-atelier-lumen.png" />
</head>

<body>
	<div class="conteneur">
		<header>
			<nav class="barre-haut">
				<span class="slogan"><?= $_ent["slogan"]; ?></span>
				<!-- Sélecteur de langue -->
				<div class="choix-langue" role="group" aria-label="Langue">
					<?php
					$languesAffichees = $languesDispos;
					krsort($languesAffichees); // FR avant EN
					foreach($languesAffichees as $code => $nom) : ?>
						<a 
							title="<?= mb_strtoupper($nom); ?>"
							href="?lan=<?= $code; ?>" 
							class="<?= ($codeLangue == $code) ? 'actif' : ''; ?>"
							<?= ($codeLangue == $code) ? 'aria-current="true"' : ''; ?>
						><?= mb_strtoupper($code); ?></a>
					<?php endforeach; ?>
				</div>
			</nav>
			<nav class="barre-logo">
				<label for="cc-btn-responsive" class="material-icons burger bouton-icone" tabindex="0" role="button" aria-label="<?= h($_ent["ouvrirMenu"]); ?>">menu</label>
				<a class="logo" href="index.php" aria-label="<?= $_ent["altLogo"]; ?>">
					<span class="logo-atelier">Atelier</span>
					<span class="logo-lumen">Lumen</span>
				</a>
				<div class="panier-icone">
					<label for="panier-cc" class="material-icons bouton-icone" tabindex="0" role="button" aria-label="<?= h($_ent["ouvrirPanier"]); ?>">shopping_cart</label>
					<input type="checkbox" id="panier-cc">
					<div class="badge-panier<?= ($nbArticles > 0) ? ' actif' : ''?>"><?= $nbArticles ?></div>
					<div class="sommaire-panier">
						<div class="ligne1">
							<span class="nb-articles">
								<span class="etiquette"><?= $_ent["panierArticles"]; ?></span>
								<span class="nombre"><?= $nbArticles; ?></span>
							</span>
							<label for="panier-cc" class="material-icons bouton-icone" tabindex="0" role="button" aria-label="<?= h($_ent["fermer"]); ?>">close</label>
						</div>
						<div class="ligne2">
							<span class="sous-titre"><?= $_ent["panierSousTotal"]; ?></span>
							<span class="sous-total montant"><?= number_format($sum, 2) ?></span>
						</div>
						<div class="ligne3 btn-afficher-panier">
							<a href="panier.php"><?= $_ent["panierVoir"]; ?></a>
						</div>
					</div>
				</div>
				<!-- Recherche : affiche les résultats dans la collection -->
				<form class="recherche" action="collection.php" method="get" role="search">
					<span class="material-icons" aria-hidden="true">search</span>
					<input type="search" name="q" value="<?= h($_GET["q"] ?? ""); ?>" placeholder="<?= $_ent["placeholderRecherche"]; ?>" aria-label="<?= $_ent["placeholderRecherche"]; ?>">
				</form>
			</nav>
			<input type="checkbox" id="cc-btn-responsive">
			<nav class="principale">
				<label for="cc-btn-responsive" class="menu-controle material-icons bouton-icone" tabindex="0" role="button" aria-label="<?= h($_ent["fermer"]); ?>">close</label>
				<a href="index.php" class="<?= $page == 'index' ? 'actif' : ''; ?>"><?= $_ent["menuAccueil"]; ?></a>
				<a href="collection.php" class="<?= $page == 'collection' ? 'actif' : ''; ?>"><?= $_ent["menuCollection"]; ?></a>
				<a href="apropos.php" class="<?= $page == 'apropos' ? 'actif' : ''; ?>"><?= $_ent["menuAPropos"]; ?></a>
			</nav>
		</header>