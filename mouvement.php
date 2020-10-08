<?php
include "template/nav.php";
include "template/header.php";
?>

<?php
  $info_user = $_SESSION["user_email"];

  $con = $db->prepare(
    "SELECT account_type FROM Account WHERE user_id = :id_user"
  );
  $result = $con->execute([
    "id_user" => $info_user["id"]
  ]);
  $account_type = $con->fetchAll(PDO::FETCH_ASSOC);
  print_r($account_type);
 ?>

<div class="container">
  <div class="row">
    <form action="" method="POST" class="col-10 mx-auto">
      <div class="form-group">
        <label for="compte">Votre compte :</label>
        <select class="form-control" id="compte" name="compte">
          <option value="">--Choisissez votre de compte--</option>
          <option value="Compte courant">Compte courant</option>
          <option value="Livret A">Livret A</option>
          <option value="PEL">PEL</option>
          <option value="LivretJeune">Livret Jeune</option>
          <option value="Perp">PERP (retraite)</option>
          <option value="Perp">LEP (populaire)</option>
        </select>
      </div>
      <div class="form-group">
        <label for="mouvement">Retrait/Dépôt :</label>
        <select class="form-control" id="mouvement" name="mouvement">
          <option value="">--Retrait/Dépôt--</option>
          <option value="debit">Débit</option>
          <option value="credit">Crédit</option>
        </select>
      </div>
      <div class="form-group">
        <label for="amount">Montant (Minimum 20 euro) :</label>
        <input type="number" class="form-control" id="amount" name="amount" value="20" min="20" required>
      </div>
      <button type="submit" class="btn btn-primary mb-2" name="valider">Valider</button>
    </form>
  </div>
</div>

<?php
include "template/footer.php";
?>
