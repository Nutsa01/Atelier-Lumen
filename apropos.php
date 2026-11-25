<?php
$page = "apropos";
include('commun/entete.com.php');
?>
<main class="page-apropos">

	<!-- Introduction -->
	<header class="intro">
		<p class="etiquette"><?= $_["etiquette"]; ?></p>
		<h1><?= $_["titrePage"]; ?></h1>
		<p class="chapeau"><?= $_["intro"]; ?></p>
	</header>

	<!-- Notre histoire -->
	<section class="histoire">
		<p><?= $_["histoire1"]; ?></p>
		<p><?= $_["histoire2"]; ?></p>
	</section>

	<!-- Notre façon de faire -->
	<section class="facon">
		<h2><?= $_["faconTitre"]; ?></h2>
		<div class="etapes">
			<div class="etape">
				<h3><?= $_["etape1Titre"]; ?></h3>
				<p><?= $_["etape1Texte"]; ?></p>
			</div>
			<div class="etape">
				<h3><?= $_["etape2Titre"]; ?></h3>
				<p><?= $_["etape2Texte"]; ?></p>
			</div>
			<div class="etape">
				<h3><?= $_["etape3Titre"]; ?></h3>
				<p><?= $_["etape3Texte"]; ?></p>
			</div>
		</div>
	</section>

	<!-- Citation -->
	<figure class="citation">
		<blockquote><?= $_["citation"]; ?></blockquote>
		<figcaption>— <?= $_["citationAuteur"]; ?></figcaption>
	</figure>

	<!-- Appel vers la collection -->
	<section class="cta">
		<h2><?= $_["ctaTitre"]; ?></h2>
		<a class="btn btn-principal" href="collection.php"><?= $_["ctaBtn"]; ?></a>
	</section>

</main>
<?php include('commun/pied2page.com.php'); ?>
