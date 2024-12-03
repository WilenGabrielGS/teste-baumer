<?php
//arquivo de conexão com o banco 

function conect(){
    // Configurações do banco de dados
    $host = 'localhost'; // Endereço do servidor
    $user = 'root';      // Usuário do banco de dados
    $password = 'root';      // Senha do banco de dados
    $database = 'baumer'; // Nome do banco de dados
    
    // Criando a conexão
    $conn = new mysqli($host, $user, $password, $database);
    
    // Verificando se a conexão foi bem-sucedida
    if ($conn->connect_error) {
        die('Erro na conexão: ' . $conn->connect_error);
    }
    
    // echo 'Conexão bem-sucedida!';
    return $conn;
}


// conect();

?>