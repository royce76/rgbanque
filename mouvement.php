<?php
include "template/nav.php";
include "template/header.php";
?>

<?php
  $info_user = $_SESSION["user_email"];

  $con = $db->prepare(
    "SELECT a.id, a.amount, a.account_type
    FROM Account as a
    WHERE user_id = :id_user"
  );
  $result = $con->execute([
    "id_user" => $info_user["id"]
  ]);
  $account_type = $con->fetchAll(PDO::FETCH_ASSOC);
  print_r($account_type);

  function search_account_id() {
    global $account_type;
    foreach ($account_type as $key => $accounts) {
      foreach ($accounts as $key => $account) {
        if ($account == test_input($_POST["compte"])) {
          return $accounts["id"];
        }
      }
    }
  }

  function search_account_amount() {
    global $account_type;
    foreach ($account_type as $key => $accounts) {
      foreach ($accounts as $key => $account) {
        if ($account == test_input($_POST["compte"])) {
          return $accounts["amount"];
        }
      }
    }
  }

  function search_account_type() {
    global $account_type;
    foreach ($account_type as $key => $accounts) {
      foreach ($accounts as $key => $account) {
        if ($account == test_input($_POST["compte"])) {
          return $account;
        }
      }
    }
  }

  if (isset($_POST["valider"])) {
    $query = $db->prepare(
      "UPDATE Account
      SET amount = :new_amount + :old_amount
      WHERE id = :a_id"
    );
    $result = $query->execute([
      "new_amount" => test_input($_POST["amount"]),
      "old_amount" => search_account_amount(),
      "a_id" => search_account_id()
    ]);
  }
 ?>

<div class="container">
  <div class="row">
    <form action="" method="POST" class="col-10 mx-auto">
      <div class="form-group">
        <label for="compte">Votre compte :</label>
        <select class="form-control" id="compte" name="compte">
          <option value="">--Choisissez votre de compte--</option>
          <?php foreach ($account_type as $key => $accounts): ?>
            <?php foreach ($accounts as $key => $account): ?>
              <?php if ($key === "account_type" ): ?>
                <option value="<?php echo $account; ?>"> <?php echo $account; ?></option>
              <?php endif; ?>
            <?php endforeach; ?>
          <?php endforeach; ?>

          <!-- <option value="Livret A">Livret A</option>
          <option value="PEL">PEL</option>
          <option value="LivretJeune">Livret Jeune</option>
          <option value="Perp">PERP (retraite)</option>
          <option value="Perp">LEP (populaire)</option> -->
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
