<?php
$page = "livraison";
include('commun/entete.com.php');
?>
<main class="page-texte page-livraison">
	<header class="intro">
		<h1><?= $_["titrePage"]; ?></h1>
		<p class="chapeau"><?= $_["chapeau"]; ?></p>
	</header>

	<?php foreach ($_["blocs"] as $bloc) : ?>
		<section class="bloc">
			<h2><?= $bloc["titre"]; ?></h2>
			<p><?= $bloc["texte"]; ?></p>
		</section>
	<?php endforeach; ?>

	<p class="contact"><?= $_["contact"]; ?> <a href="mailto:aide@atelierlumen.ca">aide@atelierlumen.ca</a>.</p>
</main>
<?php include('commun/pied2page.com.php'); ?>
