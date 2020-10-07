<?php
  include "template/nav.php";
  include "template/header.php";
 ?>
<?php
  if (!empty($_GET["id"]) && isset($_GET["id"])):
    $account = test_input($_GET["id"]);
  endif;
?>
<?php
  include "template/footer.php";
 ?>
