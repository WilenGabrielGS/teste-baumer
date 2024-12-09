<?php

session_start();

require('conexao.php');

function carregaColaboradores(){
    // URL da API
    $apiUrl = "http://localhost/teste-baumer/API/api.php?acao=listar-colaboradores";

    // Fazendo a requisição HTTP
    $response = @file_get_contents($apiUrl);

    // Verificando se a requisição foi bem-sucedida
    if ($response === false) {
        echo "
            <tr>Erro ao conectar com a API</tr>
        ";
        return;
    }

    // Decodificando a resposta JSON
    $colaboradores = json_decode($response, true);

    // Verificando se houve erro na decodificação
    if ($colaboradores === null) {
        echo "
            <tr>Erro ao processar dados da API</tr>
        ";
        return;
    }

    // Gerando a tabela de colaboradores
    if (empty($colaboradores)) {
        echo "
            <tr>Nenhum colaborador encontrado</tr>
        ";
    } else {
        foreach ($colaboradores as $colaborador) {
            echo "
                <tr class='row'>
                    <td class='col-8'><h4>{$colaborador['nome']}<h4></td>
                    <td class='col-2'>
                        <button type='button' class='btn btn-secondary' 
                                data-bs-toggle='modal' 
                                data-bs-target='#cadastroColaboradorModal' 
                                data-idColaborador='{$colaborador['id']}' 
                                id='btnEditarColaborador{$colaborador['id']}'>
                            <i class='bi bi-pencil-fill'></i>
                        </button>
                    </td>
                    <td class='col-2'>
                        <button type='button' class='btn btn-danger' data-bs-toggle='tooltip' data-bs-placement='top' data-bs-title='Excluir' data-idColaborador='{$colaborador['id']}' id='btnExcluirColaborador{$colaborador['id']}'>
                        <i class='bi bi-trash-fill'></i>
                        </button>
                    </td>
                </tr>
            ";
        }
    }
}

function carregaTarefas(){
    // URL da API
    $apiUrl = "http://localhost/teste-baumer/API/api.php?acao=listar-tarefas";

    // Fazendo a requisição HTTP
    $response = @file_get_contents($apiUrl);

    // Verificando se a requisição foi bem-sucedida
    if ($response === false) {
        echo "
            <tr>Erro ao conectar com a API</tr>
        ";
        return;
    }

    // Decodificando a resposta JSON
    $tarefas = json_decode($response, true);

    // Verificando se houve erro na decodificação
    if ($tarefas === null) {
        echo "
            <tr>Erro ao processar dados da API</tr>
        ";
        return;
    }

    // Gerando a tabela de tarefas
    if (empty($tarefas)) {
        echo "
            <tr>Nenhuma tarefa encontrado</tr>
        ";
    }else {
        foreach ($tarefas as $tarefa) {
            $dataAtual = new DateTime(); // Objeto DateTime para a data atual
        
            // Converter as datas de string para DateTime, se existirem
            $dataExecucao = isset($tarefa['data_execucao']) ? new DateTime($tarefa['data_execucao']) : null;
            $dataLimite = isset($tarefa['data_limite']) ? new DateTime($tarefa['data_limite']) : null;
            
            $status = '';
            // Verificar as condições
            if ($dataExecucao) {
                $status = '(concluído)'; // Verde para tarefas concluídas
            } elseif ($dataAtual >= $dataLimite) {
                $status = '(atrasado)'; // Vermelho para tarefas atrasadas
            }

            $conclusao = '';
            if(isset($tarefa['data_execucao'])){
                $conclusao = $tarefa['data_execucao'];
            }else{
                $conclusao = 'não concluído';
            }

            echo "
                <tr class='row' data-colaborador='{$tarefa['responsavel']}' 
                data-prioridade='{$tarefa['prioridade']}' 
                data-limite='{$tarefa['data_limite']}'>
                    <td class='col-9' data-bs-toggle='collapse' data-bs-target='#collapse{$tarefa['id']}' aria-expanded='false' aria-controls='collapse{$tarefa['id']}'>
                        <h4>{$tarefa['descricao']} $status</h4>
                    </td>
                    <td class='col-1'>
                        <button type='button' class='btn btn-success' data-bs-toggle='tooltip' data-bs-placement='top' data-bs-title='Concluir' data-idtarefa='{$tarefa['id']}' id='btnConcluirTarefa{$tarefa['id']}'>
                            <i class='bi bi-clipboard-check-fill'></i>
                        </button>
                    </td>
                    <td class='col-1'>
                        <button type='button' class='btn btn-secondary' data-bs-toggle='modal' data-bs-target='#cadastroTarefaModal' data-bs-toggle='tooltip' data-bs-placement='top' data-bs-title='Editar' data-idtarefa='{$tarefa['id']}' id='btnEditarTarefa{$tarefa['id']}'>
                            <i class='bi bi-pencil-fill'></i>
                        </button>
                    </td>
                    <td class='col-1'>
                        <button type='button' class='btn btn-danger' data-bs-toggle='tooltip' data-bs-placement='top' data-bs-title='Excluir' data-idtarefa='{$tarefa['id']}' id='btnExcluirTarefa{$tarefa['id']}'>
                            <i class='bi bi-trash-fill'></i>
                        </button>
                    </td>
                    <div class='collapse' id='collapse{$tarefa['id']}'>
                        <div class='card card-body my-3'>
                            <div class='row'>
                                <div class='mb-3 col-6'>
                                    <p><strong>Responsável:</strong> {$tarefa['nome_responsavel']}</p>
                                </div>
                                
                                <div class='mb-3 col-6'>
                                    <p><strong>Prioridade:</strong> {$tarefa['prioridade']}</p>
                                </div>

                                <div class='mb-3 col-6'>
                                    <p><strong>Data de Cadastro:</strong> {$tarefa['data_cadastro']}</p>
                                </div>

                                <div class='mb-3 col-6'>
                                    <p><strong>Data de Expiração:</strong> {$tarefa['data_limite']}</p>
                                </div>

                                <div class='mb-3 col-6'>
                                    <p><strong>Data de Conclusão:</strong> {$conclusao}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </tr>
            ";
        }        
    }
}

function listarResponsaveis(){
    // URL da API
    $apiUrl = "http://localhost/teste-baumer/API/api.php?acao=listar-colaboradores";

    // Fazendo a requisição HTTP
    $response = @file_get_contents($apiUrl);

    // Verificando se a requisição foi bem-sucedida
    if ($response === false) {
        echo "
            <tr>Erro ao conectar com a API</tr>
        ";
        return;
    }

    // Decodificando a resposta JSON
    $colaboradores = json_decode($response, true);

    // Verificando se houve erro na decodificação
    if ($colaboradores === null) {
        echo "
            <tr>Erro ao processar dados da API</tr>
        ";
        return;
    }

    foreach($colaboradores as $colaborador){
        echo "
            <option value='{$colaborador['id']}'>{$colaborador['nome']}</option>
        ";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listagem de tarefas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="CSS/index.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="JS/index.js" defer></script>

    <!-- habilitando tooltips -->
    <script>
        // Inicializa todos os tooltips da página
        document.addEventListener('DOMContentLoaded', function () {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
</head>
<body>
    
    <div id='pagina' class="container-fluid row mt-3">
        <!-- container lateral (colaboradores)-->
        <div id='lateral' class="container col-4">
            <h3 class="text-center">Colaboradores: </h3>
            <div id='listagemColaboradores' style="width: 90%;" class="mx-auto">
                <table class="table table-hover my-2" id="tblColaboradores">
                    <tr>
                        <button class="btn btn-primary" id='btnNovoColaborador' data-bs-toggle="modal" data-bs-target="#cadastroColaboradorModal">Novo <i class="bi bi-person-plus-fill"></i></button>
                    </tr>
                    <?php carregaColaboradores()?>
                </table>
            </div>
        </div>
        
        <!-- container principal (tarefas) -->
        <div id='principal' class="container col-8">
            <h3 class="text-center">Tarefas: </h3>
            <div id='listagemTarefas' style="width: 90%;" class="mx-auto">

            <div id="filtrosOrdenacao" class="d-flex justify-content-between my-3">
                <!-- Filtro por Colaborador -->
                <div>
                    <label for="filtroColaborador" class="form-label">Filtrar por Colaborador:</label>
                    <select id="filtroColaborador" class="form-select">
                        <option value="todos">Todos</option>
                        <?php listarResponsaveis(); ?>
                    </select>
                </div>
                <!-- Filtro por Prioridade -->
                <div>
                    <label for="filtroPrioridade" class="form-label">Filtrar por Prioridade:</label>
                    <select id="filtroPrioridade" class="form-select">
                        <option value="todas">Todas</option>
                        <option value="alta">Alta</option>
                        <option value="media">Média</option>
                        <option value="baixa">Baixa</option>
                    </select>
                </div>
                <!-- Filtro por Data -->
                <div>
                    <label for="filtroData" class="form-label">Filtrar por Data de Prazo:</label>
                    <input type="date" id="filtroData" class="form-control">
                </div>
                <!-- Ordenação -->
                <div>
                    <label for="ordenarTarefas" class="form-label">Ordenar por:</label>
                    <select id="ordenarTarefas" class="form-select">
                        <option value="descricao">Descrição</option>
                        <option value="prioridade">Prioridade</option>
                        <option value="data_limite">Data de Prazo</option>
                    </select>
                </div>
            </div>

                <table class="table table-hover my-2" id="tblTarefas">
                    <tr>
                        <button class="btn btn-primary" id='btnNovaTarefa' data-bs-toggle="modal" data-bs-target="#cadastroTarefaModal">Novo <i class="bi bi-file-earmark-plus-fill"></i></button>
                    </tr>
                    <?php carregaTarefas()?>
                </table>
            </div>
        </div>
    </div>


    <!-- ESPAÇO DESTINADO A MODAIS (JÁ QUE NÃO PRECISAM TER UMA ORDEM DEFINIDA) -->
    <!-- Modal para cadastro de colaborador-->
    <div class="modal fade" id="cadastroColaboradorModal" tabindex="-1" aria-labelledby="cadastroColaboradorLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cadastroColaboradorLabel">Cadastro de Colaborador</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formCadastroColaborador">
                        <div class="mb-3">
                            <label for="nome" class="form-label">Nome</label>
                            <input type="text" class="form-control" id="nome" name="nome" required>
                        </div>
                        <div class="mb-3">
                            <label for="cpf" class="form-label">CPF</label>
                            <input type="text" class="form-control" id="cpf" name="cpf" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">E-mail</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" data-acao="cadastrar" id="btnSalvarColaborador">Salvar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para cadastro de tarefa-->
    <div class="modal fade" id="cadastroTarefaModal" tabindex="-1" aria-labelledby="cadastroTarefaLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cadastroTarefaLabel">Cadastro de Tarefa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formCadastroTarefa">
                        <div class="mb-3">
                            <label for="descricao" class="form-label">Descrição</label>
                            <input type="text" class="form-control" id="descricao" name="descricao" required>
                        </div>
                        <div class="mb-3">
                            <label for="responsavel" class="form-label">Responsável</label>
                            <select class="form-control" id="responsavel" name="responsavel" required>
                                <?php listarResponsaveis()?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="prioridade" class="form-label">Prioridade</label>
                            <select name="prioridade" id="prioridade" class="form-control" required>
                                <option value="alta">Alta</option>
                                <option value="media">Média</option>
                                <option value="baixa">Baixa</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="data_limite" class="form-label">Data de Expiração</label>
                            <input type="datetime-local" class="form-control" id='data_limite'>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" data-acao="cadastrar" id="btnSalvarTarefa">Salvar</button>
                </div>
            </div>
        </div>
    </div>

</body>
</html>