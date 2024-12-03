<?php
require('../conexao.php');

$action = $_REQUEST['acao'];

if ($action == 'listar-colaboradores') {
    $conn = conect();
    $result = $conn->query("SELECT * FROM colaborador");

    $colaboradores = array();

    while ($row = $result->fetch_assoc()) {
        $colaboradores[] = [
            'id' => $row['id'],
            'nome' => $row['nome'],
            'cpf' => $row['cpf'],
            'email' => $row['email']
        ];
    }

    echo json_encode($colaboradores);
    $result->close();
}

if ($action == 'select-unico-colaborador'){
    $conn = conect();

    $dadosReq = json_decode(file_get_contents('php://input'), true);

    // Verificar se o ID é válido
    if (!isset($dadosReq['id']) || !is_numeric($dadosReq['id'])) {
        echo json_encode(['sucesso' => false, 'mensagem' => 'ID inválido.']);
        exit;
    }

    $id = (int)$dadosReq['id'];
    
    $result = $conn->query("select * from colaborador where id = $id");

    $dados = array();

    while($row = $result->fetch_assoc()){
        $dados = [
            'nome' => $row['nome'],
            'cpf' => $row['cpf'],
            'email' => $row['email']
        ];
    }

    echo json_encode($dados);
}

if ($action == 'cadastrar-colaborador') {
    $conn = conect();
    $dadosForm = json_decode(file_get_contents('php://input'), true);

    $nome = $dadosForm['nome'];
    $cpf = $dadosForm['cpf'];
    $email = $dadosForm['email'];

    $sql = $conn->prepare("insert into colaborador (nome, cpf, email) values (?, ?, ?)");
    $sql->bind_param("sss", $nome, $cpf, $email);

    if ($sql->execute()) {
        echo json_encode(['sucesso' => true]);
    } else {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao inserir dados no banco.']);
    }

    $sql->close();
}

if ($action == 'excluir-colaborador') {
    $conn = conect();

    $dadosReq = json_decode(file_get_contents('php://input'), true);

    // Verificar se o ID é válido
    if (!isset($dadosReq['id']) || !is_numeric($dadosReq['id'])) {
        echo json_encode(['sucesso' => false, 'mensagem' => 'ID inválido.']);
        exit;
    }

    $id = (int)$dadosReq['id'];

    $sql = $conn->prepare("DELETE FROM colaborador WHERE id = ?");
    $sql->bind_param('i', $id);

    if ($sql->execute()) {
        echo json_encode(['sucesso' => true]);
    } else {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao excluir colaborador.']);
    }

    $sql->close();
    exit;
}

if($action == 'editar-colaborador'){
    $conn = conect();
    $dadosForm = json_decode(file_get_contents('php://input'), true);

    $id = $dadosForm['id'];
    $nome = $dadosForm['nome'];
    $cpf = $dadosForm['cpf'];
    $email = $dadosForm['email'];

    $sql = $conn->prepare("update colaborador set nome = ?, cpf = ?, email = ? where id = ?");
    $sql->bind_param("sssi", $nome, $cpf, $email, $id);

    if ($sql->execute()) {
        echo json_encode(['sucesso' => true]);
    } else {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao inserir dados no banco.']);
    }

    $sql->close();
}

if ($action == 'listar-tarefas'){
    $conn = conect();
    $result = $conn->query("select *, (select nome from colaborador where id = responsavel) as nome_responsavel from tarefa");

    $tarefas = array();

    while ($row = $result->fetch_assoc()) {
        $tarefas[] = [
            'id' => $row['id'],
            'descricao' => $row['descricao'],
            'responsavel' => $row['responsavel'],
            'nome_responsavel' => $row['nome_responsavel'],
            'prioridade' => $row['prioridade'],
            'data_cadastro' => $row['data_cadastro'],
            'data_execucao' => $row['data_execucao'],
            'data_limite' => $row['data_limite'],
        ];
    }

    echo json_encode($tarefas);
    $result->close();
}

if ($action == 'cadastrar-tarefa') {
    $conn = conect();
    $dadosForm = json_decode(file_get_contents('php://input'), true);

    $descricao = $dadosForm['descricao'];
    $responsavel = $dadosForm['responsavel'];
    $prioridade = $dadosForm['prioridade'];
    $data_limite = $dadosForm['data_limite'];
    $dataAtual = date('Y-m-d H:i:s'); //data_cadastro

    $sql = $conn->prepare("INSERT INTO tarefa (descricao, responsavel, prioridade, data_limite, data_cadastro) VALUES (?, ?, ?, ?, ?)");
    $sql->bind_param("sisss", $descricao, $responsavel, $prioridade, $data_limite, $dataAtual);

    if ($sql->execute()) {
        echo json_encode(['sucesso' => true]);
    } else {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao inserir dados no banco.']);
    }

    $sql->close();
}

if ($action == 'excluir-tarefa') {
    $conn = conect();

    $dadosReq = json_decode(file_get_contents('php://input'), true);

    // Verificar se o ID é válido
    if (!isset($dadosReq['id']) || !is_numeric($dadosReq['id'])) {
        echo json_encode(['sucesso' => false, 'mensagem' => 'ID inválido.']);
        exit;
    }

    $id = (int)$dadosReq['id'];

    $sql = $conn->prepare("DELETE FROM tarefa WHERE id = ?");
    $sql->bind_param('i', $id);

    if ($sql->execute()) {
        echo json_encode(['sucesso' => true]);
    } else {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao excluir tarefa.']);
    }

    $sql->close();
    exit;
}

if ($action == 'concluir-tarefa') {
    $conn = conect();

    $dadosReq = json_decode(file_get_contents('php://input'), true);

    // Verificar se o ID é válido
    if (!isset($dadosReq['id']) || !is_numeric($dadosReq['id'])) {
        echo json_encode(['sucesso' => false, 'mensagem' => 'ID inválido.']);
        exit;
    }

    $id = (int)$dadosReq['id'];
    $dataAtual = date('Y-m-d H:i:s');

    $sql = $conn->prepare("update tarefa set data_execucao = ? where id = ?");
    $sql->bind_param('si', $dataAtual, $id);

    if ($sql->execute()) {
        echo json_encode(['sucesso' => true]);
    } else {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao concluir tarefa.']);
    }

    $sql->close();
    exit;
}

if ($action == 'select-unica-tarefa'){
    $conn = conect();

    $dadosReq = json_decode(file_get_contents('php://input'), true);

    // Verificar se o ID é válido
    if (!isset($dadosReq['id']) || !is_numeric($dadosReq['id'])) {
        echo json_encode(['sucesso' => false, 'mensagem' => 'ID inválido.']);
        exit;
    }

    $id = (int)$dadosReq['id'];
    
    $result = $conn->query("select * from tarefa where id = $id");

    $dados = array();

    while($row = $result->fetch_assoc()){
        $dados[] = [
            'id' => $row['id'],
            'descricao' => $row['descricao'],
            'responsavel' => $row['responsavel'],
            'prioridade' => $row['prioridade'],
            'data_cadastro' => $row['data_cadastro'],
            'data_execucao' => $row['data_execucao'],
            'data_limite' => $row['data_limite'],
        ];
    }

    echo json_encode($dados);
}

if($action == 'editar-tarefa'){
    $conn = conect();
    $dadosForm = json_decode(file_get_contents('php://input'), true);

    $id = $dadosForm['id'];
    $descricao = $dadosForm['descricao'];
    $responsavel = $dadosForm['responsavel'];
    $prioridade = $dadosForm['prioridade'];
    $data_limite = $dadosForm['data_limite'];

    $sql = $conn->prepare("update tarefa set descricao = ?, responsavel = ?, prioridade = ?, data_limite = ? where id = ?");
    $sql->bind_param("ssssi", $descricao, $responsavel, $prioridade, $data_limite, $id);

    if ($sql->execute()) {
        echo json_encode(['sucesso' => true]);
    } else {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao inserir dados no banco.']);
    }

    $sql->close();
}



?>
