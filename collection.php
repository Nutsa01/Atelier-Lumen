<?php
$page = "collection";
include('commun/entete.com.php');

include("lib/catalogue.lib.php");

$recherche = trim($_GET["q"] ?? "");
$categories = obtenirCategories($cnx, $codeLangue);
$produits = obtenirProduits($cnx, 'RAND()', 'tous', $recherche, $codeLangue);
$_cat = $textes["catalogue"];
?>
<main class="page-produits page-collection">
	<header class="amorce">
		<p class="surtitre"><?= $_["surtitre"]; ?></p>
		<h1><?= $_["titrePage"]; ?></h1>
		<?php if ($recherche !== '') : ?>
			<p class="sous-titre">
				<?= $_cat["resultatsPour"]; ?> « <?= h($recherche); ?> »
				· <a class="effacer-recherche" href="collection.php"><?= $_cat["effacerRecherche"]; ?></a>
			</p>
		<?php else : ?>
			<p class="sous-titre"><?= $_["sousTitre"]; ?></p>
		<?php endif; ?>
	</header>

	<!-- Filtre par catégorie et tri -->
	<form class="controle" action="" method="get">
		<input type="hidden" name="q" value="<?= h($recherche); ?>">
		<fieldset class="filtre">
			<legend class="visuellement-cache"><?= $_cat["filtreEtiquette"]; ?></legend>
			<label class="puce">
				<input type="radio" name="filtre" value="tous" checked>
				<span><?= $_cat["filtreTous"]; ?></span>
			</label>
			<?php foreach ($categories as $categorie) : ?>
				<label class="puce">
					<input type="radio" name="filtre" value="<?= $categorie["id"]; ?>">
					<span><?= h($categorie["nom"]); ?></span>
				</label>
			<?php endforeach; ?>
		</fieldset>

		<label class="tri">
			<span class="tri-etiquette"><?= $_cat["triEtiquette"]; ?></span>
			<span class="tri-select">
				<select name="tri">
					<option value="RAND()"><?= $_cat["triAlea"]; ?></option>
					<option value="ventes DESC"><?= $_cat["triVentes"]; ?></option>
					<option value="dac DESC"><?= $_cat["triNouveaute"]; ?></option>
					<option value="prix ASC"><?= $_cat["triPrixAsc"]; ?></option>
					<option value="prix DESC"><?= $_cat["triPrixDesc"]; ?></option>
					<option value="nom ASC"><?= $_cat["triNomAsc"]; ?></option>
					<option value="nom DESC"><?= $_cat["triNomDesc"]; ?></option>
				</select>
				<span class="material-icons" aria-hidden="true">expand_more</span>
			</span>
		</label>
	</form>

	<article class="principal liste-produits" data-message-vide="<?= h($_cat["aucunProduit"]); ?>">
		<?php if (count($produits) == 0) : ?>
			<p class="message-vide"><?= $_cat["aucunProduit"]; ?></p>
		<?php endif; ?>
		<?php foreach ($produits as $prod) : ?>
			<div class="produit" data-pid="<?= $prod["id"]; ?>">
				<span class="image">
					<img src="images/produits/<?= h($prod["image"]); ?>" alt="<?= h($prod["nom"]); ?>" loading="lazy">
				</span>
				<div class="prd-info">
					<span class="nom"><?= h($prod["nom"]); ?></span>
					<span class="prix montant"><?= number_format($prod["prix"], 2); ?></span>
				</div>
				<button class="btn-ajouter"><?= $_cat["btnAjouter"]; ?></button>
			</div>
		<?php endforeach; ?>
	</article>
</main>

<!-- Gabarit d'un produit (utilisé après un filtre ou un tri) -->
<template id="gabarit-produit">
	<div class="produit" data-pid="">
		<span class="image">
			<img src="images/produits/" alt="">
		</span>
		<div class="prd-info">
			<span class="nom"></span>
			<span class="prix montant"></span>
		</div>
		<button class="btn-ajouter"><?= $_cat["btnAjouter"]; ?></button>
	</div>
</template>

<?php include('commun/pied2page.com.php'); ?>
