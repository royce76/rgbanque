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

  function search_account() {
    global $account_type;
    foreach ($account_type as $key => $accounts) {
      foreach ($accounts as $key => $account) {
        if ($account == test_input($_POST["compte"])) {
          return $account;
        }
      }
    }
  }

  if (isset($_POST["valider"]) && !search_account()) {
    $query = $db->prepare(
      "INSERT INTO Account(amount,opening_date,account_type,user_id)
      VALUES(:amount, NOW(), :account_type, :user_id)"
    );
    $result = $query->execute([
      "amount" => test_input($_POST["depot"]),
      "account_type" => test_input($_POST["compte"]),
      "user_id" => $info_user["id"]
    ]);
  }

 ?>
<div class="container">
  <div class="row">
    <form action="" method="POST" class="col-10 mx-auto my-4">
      <div class="form-group">
        <label for="compte">Type de compte</label>
        <select class="form-control" id="compte" name="compte">
          <option value="">--Choisissez un type de compte--</option>
          <option value="Compte courant">Compte courant</option>
          <option value="Livret A">Livret A</option>
          <option value="PEL">PEL</option>
          <option value="Livret Jeune">Livret Jeune</option>
          <option value="Perp">PERP (retraite)</option>
          <option value="Lep">LEP (populaire)</option>
        </select>
      </div>
      <div class="form-group">
        <label for="depot">Depôt d'argent (Minimum 50 euro)</label>
        <input type="number" class="form-control" id="depot" name="depot" value="50" min="50">
      </div>
      <button id="buttonNewAccount" type="submit" class="btn btn-primary mb-2" name="valider">Valider</button>
    </form>
  </div>
</div>



<?php
  include "template/footer.php";
?>
