<?php
$page = "panier";
include('commun/entete.com.php');
?>
<main class="page-panier">
  <article class="amorce">
    <h1><?= $_["titrePage"]; ?></h1>
  </article>
  <article class="principal">
    <div class="liste-panier" data-message-vide="<?= h($_["panierVide"]); ?>">
      <?php if (count($detailPanier) == 0) : ?>
        <div class="message-vide"><?= $_["panierVide"]; ?></div>
      <?php endif; ?>
      <?php foreach ($detailPanier as $produit) : ?>
        <article class="item-panier" data-aid="<?= $produit['id']; ?>">
          <div class="image-nom">
            <div class="image">
              <img src="images/produits/<?= h($produit["image"]); ?>" alt="<?= h($produit["nom"]); ?>">
            </div>
            <div class="nom"><?= h($produit["nom"]); ?></div>
            <button class="btn-supprimer material-icons" title="<?= h($_["titreSupprimer"]); ?>" aria-label="<?= h($_["titreSupprimer"]); ?>">delete</button>
          </div>
          <div class="qte-prix">
            <div class="prix">
              <span class="etiquette-prix"><?= $_["prix"]; ?></span>
              <span class="valeur-prix montant"><?= number_format($produit["prix"], 2); ?></span>
            </div>
            <div class="quantite">
              <span class="etiquette-qte"><?= $_["quantite"]; ?></span>
              <span class="valeur-qte">
                <input type="number" name="quantite" value="<?= $produit["qte"]; ?>" min="0" max="99" aria-label="<?= h($_["quantite"]); ?>">
                <button class="btn-modifier material-icons" title="<?= h($_["titreModifier"]); ?>" aria-label="<?= h($_["titreModifier"]); ?>">update</button>
              </span>
            </div>
            <div class="total-article">
              <span class="etiquette-soustotal"><?= $_["sousTotal"]; ?></span>
              <span class="valeur-soustotal montant"><?= number_format($produit["qte"] * $produit["prix"], 2); ?></span>
            </div>
          </div>
        </article>
      <?php endforeach; ?>
    </div>

    <!-- Gabarit d'un article (utilisé après une modification) -->
    <template id="gabarit-article">
      <article class="item-panier" data-aid="">
        <div class="image-nom">
          <div class="image">
            <img src="images/produits/" alt="">
          </div>
          <div class="nom"></div>
          <button class="btn-supprimer material-icons" title="<?= h($_["titreSupprimer"]); ?>" aria-label="<?= h($_["titreSupprimer"]); ?>">delete</button>
        </div>
        <div class="qte-prix">
          <div class="prix">
            <span class="etiquette-prix"><?= $_["prix"]; ?></span>
            <span class="valeur-prix montant"></span>
          </div>
          <div class="quantite">
            <span class="etiquette-qte"><?= $_["quantite"]; ?></span>
            <span class="valeur-qte">
              <input type="number" name="quantite" value="" min="0" max="99" aria-label="<?= h($_["quantite"]); ?>">
              <button class="btn-modifier material-icons" title="<?= h($_["titreModifier"]); ?>" aria-label="<?= h($_["titreModifier"]); ?>">update</button>
            </span>
          </div>
          <div class="total-article">
            <span class="etiquette-soustotal"><?= $_["sousTotal"]; ?></span>
            <span class="valeur-soustotal montant"></span>
          </div>
        </div>
      </article>
    </template>

    <div class="sommaire-panier">
      <span class="nb-articles">(<?= $_["nbArticles"]; ?> <span class="nombre"><?= $nbArticles; ?></span>)</span>
      <span class="sous-total">
        <?= $_["sousTotal"]; ?>
        <span class="montant"><?= number_format($sum, 2); ?></span>
      </span>
      <span class="btn-commander" title="<?= h($_["noteCommander"]); ?>"><?= $_["btnCommander"]; ?></span>
    </div>
    <p class="note-demo"><?= $_["noteCommander"]; ?></p>
  </article>
</main>
<?php include('commun/pied2page.com.php'); ?>
