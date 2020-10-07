<?php
  include "template/nav.php";
  include "template/header.php";
?>

<div class="container">
  <div class="row">
    <form action="" method="post" class="col-10 offset-1">
      <div class="form-group">
        <label for="compte">Type de compte</label>
        <select class="form-control" id="compte" name="compte">
          <option value="">--Choisissez un type de compte--</option>
          <option value="Compte courant">Compte courant</option>
          <option value="LivretA">Livret A</option>
          <option value="PEL">PEL</option>
          <option value="Livret Jeune">Livret Jeune</option>
          <option value="Perp">PERP (retraite)</option>
          <option value="Lep">LEP (populaire)</option>
        </select>
      </div>
      <div class="form-group">
        <label for="lastName">Entrez votre nom</label>
        <input type="text" class="form-control" id="lastName" name="lastName" placeholder="Votre nom...">
      </div>
      <div class="form-group">
        <label for="firstName">Entrez votre prénom</label>
        <input type="text" class="form-control" id="firstName" name="firstName" placeholder="Votre prénom...">
      </div>
      <div class="form-group">
        <label for="email">Adresse E-mail</label>
        <input type="email" class="form-control" id="email" name="email" placeholder="exemple@exemple.com">
      </div>
      <div class="form-group">
        <label for="phone">Votre numéro de téléphonne</label>
        <input type="tel" class="form-control" id="phone" name="phone" placeholder="0X XX XX XX XX">
      </div>
      <div class="form-group">
        <label for="deposit">Depôt d'argent (Minimum 50 euro)</label>
        <input type="number" class="form-control" id="deposit" name="deposit" value="50" min="50">
      </div>
      <button id="buttonNewAccount" type="submit" class="btn btn-primary mb-2">Valider</button>
    </form>
  </div>
</div>



<?php
  include "template/footer.php";
?>
