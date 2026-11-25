<?php
$page = "index";
include('commun/entete.com.php');

// Produits vedettes : les 4 meilleurs vendeurs.
include("lib/catalogue.lib.php");
$vedettes = array_slice(obtenirProduits($cnx, 'ventes DESC', 'tous', '', $codeLangue), 0, 4);
?>
<main class="page-accueil">

	<!-- Section principale -->
	<section class="hero">
		<p class="surtitre"><?= $_["heroSurtitre"]; ?></p>
		<h1><?= $_["heroTitre"]; ?></h1>
		<p class="hero-texte"><?= $_["heroTexte"]; ?></p>
		<div class="hero-actions">
			<a class="btn btn-principal" href="collection.php"><?= $_["heroBtnPrincipal"]; ?></a>
			<a class="btn btn-secondaire" href="apropos.php"><?= $_["heroBtnSecondaire"]; ?></a>
		</div>
	</section>

	<!-- L'atelier -->
	<section class="atelier">
		<div>
			<p class="etiquette"><?= $_["atelierEtiquette"]; ?></p>
			<h2><?= $_["atelierTitre"]; ?></h2>
		</div>
		<div>
			<p><?= $_["atelierTexte1"]; ?></p>
			<p><?= $_["atelierTexte2"]; ?></p>
			<p class="signature"><?= $_["atelierSignature"]; ?></p>
		</div>
	</section>

	<!-- Produits vedettes -->
	<section class="vedettes">
		<div class="entete-section">
			<h2><?= $_["vedettesTitre"]; ?></h2>
			<a class="lien-voir-tout" href="collection.php"><?= $_["voirTout"]; ?> <span class="material-icons" aria-hidden="true">arrow_forward</span></a>
		</div>
		<div class="liste-produits">
			<?php foreach ($vedettes as $prod) : ?>
				<div class="produit" data-pid="<?= $prod["id"]; ?>">
					<span class="image">
						<img src="images/produits/<?= h($prod["image"]); ?>" alt="<?= h($prod["nom"]); ?>" loading="lazy">
					</span>
					<div class="prd-info">
						<span class="nom"><?= h($prod["nom"]); ?></span>
						<span class="prix montant"><?= number_format($prod["prix"], 2); ?></span>
					</div>
					<button class="btn-ajouter"><?= $_["btnAjouter"]; ?></button>
				</div>
			<?php endforeach; ?>
		</div>
	</section>

	<!-- Bandeau d'appel -->
	<section class="bandeau">
		<h2><?= $_["bandeauTitre"]; ?></h2>
		<p><?= $_["bandeauTexte"]; ?></p>
		<a class="btn btn-principal" href="collection.php"><?= $_["bandeauBtn"]; ?></a>
	</section>

</main>
<?php include('commun/pied2page.com.php'); ?>
