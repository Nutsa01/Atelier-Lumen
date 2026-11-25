		<footer>
			<h2>Atelier Lumen</h2>
			<div class="contenu">
				<section class="achats">
					<h3><?= $_pp["achatsTitre"]; ?></h3>
					<nav>
						<a href="faq.php"><?= $_pp["navFaq"]; ?></a>
						<a href="livraison.php"><?= $_pp["navLivraison"]; ?></a>
					</nav>
				</section>
				<section class="apropos">
					<h3><?= $_pp["aProposTitre"]; ?></h3>
					<nav>
						<a href="collection.php"><?= $_ent["menuCollection"]; ?></a>
						<a href="apropos.php"><?= $_ent["menuAPropos"]; ?></a>
					</nav>
				</section>
				<section class="coordonnees">
					<h3><?= $_pp["joindreTitre"]; ?></h3>
					<nav>
						<a href="mailto:aide@atelierlumen.ca">aide@atelierlumen.ca</a>
						<span><?= $_pp["joindreLieu"]; ?></span>
					</nav>
				</section>
			</div>
			<p class="da">&copy; <?= $_pp["droits"]; ?> <?= date("Y"); ?></p>
		</footer>
	</div>
	<script src="js/main.js"></script>
</body>
</html>