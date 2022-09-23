<?php if (session_status() !== PHP_SESSION_ACTIVE) session_start(); //inicia a sessão


$_SESSION['logged'] = $_SESSION['logged'] ?? false; //verifica se usuário está logado ou não, por padrão ele não estará logado
$_SESSION['usuario'] = $_SESSION['usuario'] ?? NULL; //login
$_SESSION['senha'] = $_SESSION['senha'] ?? NULL;; //senha
$_SESSION['idUser'] = $_SESSION['idUser'] ?? NULL;; //id
$_SESSION['nome'] = $_SESSION['nome'] ?? NULL; //nome
$_SESSION['tipoUsuario'] = $_SESSION['tipoUsuario'] ?? NULL; //classe

//Estilização da cor de barra de menu de acordo com o tipo de usuário logado (resgatado do banco de dados)
if ($_SESSION['logged']){
    switch($_SESSION['tipoUsuario']){
        case 'med':
            $bar = 'is-link';
            $tipoUser = 'Médico(a): ';
            $submenuUsuarioListar = 'style="display: none"';
            $submenuExameCadastro = 'style="display: none"';
            $submenuExameEdit = 'style="display: none"';
            $submenuExameDel = 'style="display: none"';
            $menuMedico = '';
            $submenuMedicoCadastro = 'style="display: none"';
            $submenuMedicoEdit = 'style="display: none"';
            $submenuMedicoDel = 'style="display: none"';
            $menuAnalista = 'style="display: none"';
            $submenuAnalistaCadastro = 'style="display: none"';
            $submenuAnalistaEdit = 'style="display: none"';
            $submenuAnalistaDel = 'style="display: none"';
            $menuPaciente = 'style="display: none"';
            break;
        case 'pac':
            $bar = 'is-light';
            $tipoUser = 'Paciente: ';
            $submenuUsuarioListar = 'style="display: none"';
            $submenuExameCadastro = 'style="display: none"';
            $submenuExameEdit = 'style="display: none"';
            $submenuExameDel = 'style="display: none"';
            $menuMedico = 'style="display: none"';
            $submenuMedicoCadastro = 'style="display: none"';
            $submenuMedicoEdit = 'style="display: none"';
            $submenuMedicoDel = 'style="display: none"';
            $menuAnalista = 'style="display: none"';
            $submenuAnalistaCadastro = 'style="display: none"';
            $submenuAnalistaEdit = 'style="display: none"';
            $submenuAnalistaDel = 'style="display: none"';
            $menuPaciente = 'style="display: none"';
            break;
        case 'ana':
            $bar = 'is-warning';
            $tipoUser = 'Analista: ';
            $submenuUsuarioListar = 'style="display: none"';
            $submenuExameCadastro = '';
            $submenuExameEdit = '';
            $submenuExameDel = '';
            $menuMedico = 'style="display: none"';
            $submenuMedicoCadastro = 'style="display: none"';
            $submenuMedicoEdit = 'style="display: none"';
            $submenuMedicoDel = 'style="display: none"';
            $menuAnalista = '';
            $submenuAnalistaCadastro = 'style="display: none"';
            $submenuAnalistaEdit = 'style="display: none"';
            $submenuAnalistaDel = 'style="display: none"';
            $menuPaciente = 'style="display: none"';
            break;
        case 'sup':
            $bar = 'is-black';
            $tipoUser = 'Supervisor(a): ';
            $submenuUsuarioListar = '';
            $submenuExameCadastro = '';
            $submenuExameEdit = '';
            $submenuExameDel = '';
            $menuMedico = '';
            $submenuMedicoCadastro = '';
            $submenuMedicoEdit = '';
            $submenuMedicoDel = '';
            $menuAnalista = '';
            $submenuAnalistaCadastro = '';
            $submenuAnalistaEdit = '';
            $submenuAnalistaDel = '';
            $menuPaciente = '';
            break;
    }
}

//Logout
if (isset($_GET['logout']) && $_GET['logout'] == 1){
    session_unset(); //elimino todas as chaves criadas
    session_destroy(); //destruo a sessão
    header('Location: index.php'); //redireciono para a página principal
}

?>