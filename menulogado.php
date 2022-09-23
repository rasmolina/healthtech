<?php include 'session.php';

//se o usuário não estiver logado não acessará este menu
$logged = $_SESSION['logged'] ?? NULL;
if (!$logged) die("Acesso Negado!");

?>

<?php echo '<nav class="navbar ' . $bar .'" role="navigation" aria-label="main navigation" id="loggedNavbar">'; ?>

  <div id="userNavbar" class="navbar-menu">
    <div class="navbar-start">
      <a class="navbar-item" href="index.php">
        Home
      </a>
      <div class="navbar-item has-dropdown is-hoverable">
        <a class="navbar-link">
          Usuário
        </a>
        <div class="navbar-dropdown">
          <a class="navbar-item" href="alterarsenha.php">
            Alterar minha senha
          </a>
          <a class="navbar-item" href="alterardadoscadastrais.php">
            Alterar meus dados pessoais
          </a>
          <?php echo '<a class="navbar-item"'. $submenuUsuarioListar.'href="listarusuarios.php"> '; ?>
            Listar usuários
          </a>
        </div>
      </div>
    </div>

    <div id="examesNavbar" class="navbar-menu">
    <div class="navbar-start">
      <div class="navbar-item has-dropdown is-hoverable">
        <a class="navbar-link">
          Exames
        </a>
        <div class="navbar-dropdown">
        <a class="navbar-item" href="listarexames.php">
            Lista de Exames Disponíveis
          </a>
          <a class="navbar-item" href="visualizarlaudo.php">
            Visualizar laudo
          </a>
          <hr class="navbar-divider">
          <?php echo '<a class="navbar-item"'. $submenuExameCadastro.' href="cadastrarexame.php">'; ?>
            Cadastrar novo exame
          </a>
          <?php echo '<a class="navbar-item"'. $submenuExameEdit.'href="editarexame.php">'; ?>                    
            Editar exame
          </a>
          <?php echo '<a class="navbar-item"'. $submenuExameDel.'href="deletarexame.php">'; ?>
            Excluir exame
          </a>
        </div>
      </div>
    </div>

    <div id="medicosNavbar" class="navbar-menu">
    <div class="navbar-start">
    <?php echo  
      '<div class="navbar-item has-dropdown is-hoverable" '.$menuMedico.'>'; ?>
        <a class="navbar-link">
          Médico
        </a>
        <div class="navbar-dropdown">
          <a class="navbar-item" href="solicitarExame.php">
            Solicitar exame
          </a>
          <a class="navbar-item" href="gerenciarpedidos.php">
            Gerenciar Solicitações de exames
          </a>
          <hr class="navbar-divider">
          <?php echo '<a class="navbar-item"'. $submenuMedicoCadastro.'href="cadastrarmedico.php">'; ?>
            Cadastrar Médico
          </a>
          <?php echo '<a class="navbar-item"'. $submenuMedicoEdit.'href="editarmedico.php">'; ?>
            Editar Médico
          </a>
          <?php echo '<a class="navbar-item"'. $submenuMedicoDel.'href="deletarmedico.php">'; ?>
            Excluir Médico
          </a>
        </div>
      </div>
    </div>

    <div id="analistaNavbar" class="navbar-menu">
    <div class="navbar-start">
    <?php echo  
      '<div class="navbar-item has-dropdown is-hoverable" '.$menuAnalista.'>'; ?>
        <a class="navbar-link">
          Analista
        </a>
        <div class="navbar-dropdown">
          <a class="navbar-item" href="incluirlaudo.php">
            Laudar/Excluir exames
          </a>
          <hr class="navbar-divider">
          <?php echo '<a class="navbar-item"'. $submenuAnalistaCadastro.' href="cadastraranalista.php">'; ?>
            Cadastrar Analista
          </a>
          <?php echo '<a class="navbar-item"'. $submenuAnalistaEdit.'href="editaranalista.php">'; ?>
            Editar Analista
          </a>
          <?php echo '<a class="navbar-item"'. $submenuAnalistaDel.'href="deletaranalista.php">'; ?>
            Excluir Analista
          </a>
        </div>
      </div>
    </div>

    <div id="pacientesNavbar" class="navbar-menu">
    <div class="navbar-start">
    <?php echo  
      '<div class="navbar-item has-dropdown is-hoverable" '.$menuPaciente.'>'; ?>
        <a class="navbar-link">
          Paciente
        </a>
        <div class="navbar-dropdown">
          <a class="navbar-item" href="cadastrarpaciente.php">
            Cadastrar Paciente
          </a>
          <a class="navbar-item" href="editarpaciente.php">
            Editar Paciente
          </a>
          <a class="navbar-item" href="deletarpaciente.php">
            Excluir Paciente
          </a>
        </div>
      </div>
    </div>
    
    <div class="navbar-end">
        <a class="navbar-item">
            <?php echo $tipoUser . $_SESSION['nome']; ?>
        </a>
        

      <div class="navbar-item">
        <div class="buttons">
          <a class="button is-primary" onclick="window.location.href='?logout=1'">
            <strong>Log out</strong>
          </a>
        </div>
      </div>
    </div>
  </div>
</nav>