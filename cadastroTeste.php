<?php

require_once "database.php";

    $userName = 'Miriam Bragança Lopes';
    $userLogin = 'miriam@medico.com';
    $userPassword = password_hash('12345',PASSWORD_BCRYPT,["cost"=>11]); //criptografia da senha
    $userClasse = 'med';
    
    try {
        $conexao = Conexao::getConexao();
        $stmt = $conexao->prepare('INSERT INTO medico(nomeCompleto,cpf,dataNascimento,login,senha,classeUsuario,crm,especialidade,permite_req) values(:p1,:p2,:p3,:p4,:p5,:p6,:p7,:p8,:p9)');
            $stmt->execute([
                "p1" => $userName,
                "p2" => '152.297.608-22',
                "p3" => '1996-03-22',
                "p4" => $userLogin,
                "p5" => $userPassword,
                "p6" => $userClasse,
                "p7" => '65731-SP',
                "p8" => 'Pediatra',
                "p9" => '1'
            ]);

    } catch (Exception $th) {
        echo json_encode(array("msg" => $th->getMessage()));
        exit;
    }

?>