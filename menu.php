<?php 
$logged = $_SESSION['logged'] ?? NULL;
?>

<nav class="navbar is-info" role="navigation" aria-label="main navigation">
  <!--
  <div class="navbar-brand">
    <a class="navbar-item">
      <img src="logo.jpg" width="60" height="80">
    </a>
  </div> -->

  <div id="navbar" class="navbar-menu">
    <div class="navbar-start">
      <a class="navbar-item" href="index.php">
        Home
      </a>

      <a class="navbar-item" href="faleconosco.php">
        Fale conosco
      </a>

      <a class="navbar-item" href="sobre.php">
        Sobre nós
      </a>

    </div>

    <div class="navbar-end">

      <div class="navbar-item">
        <div class="buttons">
          <a class="button is-primary" onclick="window.location.href='login.php'">
            Acesso restrito
          </a>
        </div>
      </div>
    </div>


  </div>
</nav>