/******** Icônes (menu, panier) utilisables au clavier *********/

// Entrée ou Espace sur une icône = clic (ouvre ou ferme le menu / le panier).
document.querySelectorAll(".bouton-icone").forEach(function (icone) {
  icone.addEventListener("keydown", function (evt) {
    if (evt.key === "Enter" || evt.key === " ") {
      evt.preventDefault();
      icone.click();
    }
  });
});


/******** Collection : filtre, recherche et tri *********/

const formControle = document.querySelector('form.controle');

if (formControle) {
  formControle.addEventListener('change', function () {
    const parametres = new URLSearchParams({
      filtre: this.filtre.value,
      tri: this.tri.value,
      q: this.q.value
    });
    fetch("async/catalogue.async.php?" + parametres).then(afficherProduits);
  });
}

/**
 * Remplace la liste de produits par ceux reçus en JSON.
 * @param {Response} reponse : réponse de la requête asynchrone.
 */
function afficherProduits(reponse) {
  reponse.json().then(function (produits) {
    const conteneur = document.querySelector("main.page-collection .liste-produits");
    const gabarit = document.getElementById("gabarit-produit");
    conteneur.innerHTML = "";

    if (produits.length == 0) {
      const message = document.createElement("p");
      message.className = "message-vide";
      message.textContent = conteneur.dataset.messageVide;
      conteneur.appendChild(message);
      return;
    }

    for (const prod of produits) {
      const clone = gabarit.content.cloneNode(true);
      clone.firstElementChild.dataset.pid = prod.id;
      clone.querySelector('img').src += prod.image;
      clone.querySelector('img').alt = prod.nom;
      clone.querySelector('.nom').textContent = prod.nom;
      clone.querySelector('.montant').textContent = Number(prod.prix).toFixed(2);
      conteneur.appendChild(clone);
    }
  });
}


/******** Panier d'achats *********/

/**
 * Envoie une action au panier (POST) et retourne le détail du panier mis à jour.
 * @param {Object} donnees : action et paramètres (ex. {action: 'ajouter', pid: 3}).
 * @returns {Promise<Array>} articles du panier.
 */
function actionPanier(donnees) {
  return fetch("async/panier.async.php", {
    method: "POST",
    body: new URLSearchParams(donnees)
  }).then(reponse => reponse.json());
}

/**
 * Met à jour le sommaire du panier (badge, entête et bas de la page panier)
 * après chaque action asynchrone.
 * @param {Array} detailPanier : articles du panier.
 */
function gererAffichageActionsPanier(detailPanier) {
  let nombreArticles = 0;
  let sommeSousTotal = 0;
  detailPanier.forEach(function (article) {
    nombreArticles += Number(article.qte);
    sommeSousTotal += Number(article.prix) * Number(article.qte);
  });

  // Nombre d'articles (entête et page panier)
  document.querySelectorAll(".sommaire-panier .nombre").forEach(function (element) {
    element.textContent = nombreArticles;
  });

  // Sous-total (entête et page panier)
  document.querySelectorAll(".sommaire-panier .sous-total.montant, .sommaire-panier .sous-total .montant").forEach(function (element) {
    element.textContent = sommeSousTotal.toFixed(2);
  });

  // Badge de l'icône du panier
  const badge = document.querySelector(".badge-panier");
  if (badge) {
    badge.textContent = nombreArticles;
    badge.classList.toggle('actif', nombreArticles > 0);
  }
}

/***** Ajouter un produit (accueil et collection) *****/
const listeProduits = document.querySelector(".liste-produits");

if (listeProduits) {
  listeProduits.addEventListener('click', function (evt) {
    if (evt.target.classList.contains("btn-ajouter")) {
      const pid = evt.target.closest("div.produit").dataset.pid;
      actionPanier({ action: "ajouter", pid: pid }).then(gererAffichageActionsPanier);
    }
  });
}

/***** Modifier la quantité ou supprimer un article (page panier) *****/
const listePanier = document.querySelector("main.page-panier .liste-panier");

if (listePanier) {
  listePanier.addEventListener('click', function (evt) {
    const cible = evt.target;
    const article = cible.closest("article.item-panier");
    if (!article) return;

    const aid = article.dataset.aid;

    if (cible.classList.contains("btn-modifier")) {
      const qte = article.querySelector("input[name='quantite']").value;
      actionPanier({ action: "modifier", aid: aid, qte: qte }).then(afficherArticlesPanier);
    } else if (cible.classList.contains("btn-supprimer")) {
      actionPanier({ action: "supprimer", aid: aid }).then(afficherArticlesPanier);
    }
  });
}

/**
 * Réaffiche les articles de la page panier, puis met à jour le sommaire.
 * @param {Array} detailPanier : articles du panier.
 */
function afficherArticlesPanier(detailPanier) {
  const gabarit = document.getElementById("gabarit-article");
  listePanier.innerHTML = "";

  detailPanier.forEach(function (article) {
    const clone = gabarit.content.cloneNode(true);
    clone.firstElementChild.dataset.aid = article.id;
    clone.querySelector('img').src += article.image;
    clone.querySelector('img').alt = article.nom;
    clone.querySelector('.nom').textContent = article.nom;
    clone.querySelector('.valeur-prix').textContent = Number(article.prix).toFixed(2);
    clone.querySelector("input[name='quantite']").value = article.qte;
    clone.querySelector('.valeur-soustotal').textContent = (article.qte * article.prix).toFixed(2);
    listePanier.appendChild(clone);
  });

  if (detailPanier.length == 0) {
    const message = document.createElement("div");
    message.className = "message-vide";
    message.textContent = listePanier.dataset.messageVide;
    listePanier.appendChild(message);
  }

  gererAffichageActionsPanier(detailPanier);
}
