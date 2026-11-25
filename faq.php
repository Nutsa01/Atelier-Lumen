<?php
$page = "faq";
include('commun/entete.com.php');
?>
<main class="page-texte page-faq">
	<header class="intro">
		<h1><?= $_["titrePage"]; ?></h1>
		<p class="chapeau"><?= $_["chapeau"]; ?></p>
	</header>

	<section class="questions">
		<?php foreach ($_["questions"] as $question) : ?>
			<details>
				<summary><?= $question["q"]; ?></summary>
				<p><?= $question["r"]; ?></p>
			</details>
		<?php endforeach; ?>
	</section>

	<p class="contact"><?= $_["contact"]; ?> <a href="mailto:aide@atelierlumen.ca">aide@atelierlumen.ca</a>.</p>
</main>
<?php include('commun/pied2page.com.php'); ?>
