
//INTERAÇÕES DE COLABORADORES
//inserirndo novo colaborador
document.getElementById('btnSalvarColaborador').addEventListener('click', function () {
    // Obtém os dados do formulário
    const nome = document.getElementById('nome').value;
    const cpf = document.getElementById('cpf').value;
    const email = document.getElementById('email').value;

    // Validação básica
    if (!nome || !cpf || !email) {
        alert('Por favor, preencha todos os campos.');
        return;
    }

    var acao;
    if(this.getAttribute('data-acao') == 'cadastrar'){
        fetch(`http:/teste-baumer/API/api.php?acao=cadastrar-colaborador`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ "nome": nome, "cpf": cpf, "email": email }),
        })
        .then(response => response.json())
        .then(data => {
            if (data.sucesso) {
                alert('Colaborador cadastrado com sucesso!');
                document.getElementById('formCadastroColaborador').reset();
                // Fechar o modal
                var modal = bootstrap.Modal.getInstance(document.getElementById('cadastroColaboradorModal'));
                modal.hide();
                // Recarregar a lista de colaboradores
                location.reload();
            } else {
                alert('Erro ao cadastrar colaborador: ' + data.mensagem);
            }
        })
        .catch(error => {
            console.error('Erro ao enviar os dados:', error);
            alert('Ocorreu um erro ao cadastrar o colaborador.');
        });
    }else if(this.getAttribute('data-acao') == 'editar'){
        fetch(`http:/teste-baumer/API/api.php?acao=editar-colaborador`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({"id": parseInt(this.getAttribute('data-idColaborador')), "nome": nome, "cpf": cpf, "email": email }),
        })
        .then(response => response.json())
        .then(data => {
            if (data.sucesso) {
                alert('Colaborador editado com sucesso!');
                document.getElementById('formCadastroColaborador').reset();
                // Fechar o modal
                var modal = bootstrap.Modal.getInstance(document.getElementById('cadastroColaboradorModal'));
                modal.hide();
                // Recarregar a lista de colaboradores
                location.reload();
            } else {
                alert('Erro ao cadastrar colaborador: ' + data.mensagem);
            }
        })
        .catch(error => {
            console.error('Erro ao enviar os dados:', error);
            alert('Ocorreu um erro ao cadastrar o colaborador.');
        });
    }

});


//adicionando interação de exclusão e edição para cada botão de colaborador
const tabelaColaboradores = document.getElementById('tblColaboradores');
let listaColaboradores = tabelaColaboradores.children[0].children;

Array.from(listaColaboradores).forEach(tr => {
    if(tr.rowIndex !== 0){ //a primeira linha da tabela não deve entrar nessa interação pois não se trata de um colaborador
        let btnColaborador = tr.children[2].children[0];
        var idColaborador = btnColaborador.getAttribute('data-idColaborador');
        let btnEditar = tr.children[1].children[0];
        // console.log(btnEditar)
        
        //exluir
        btnColaborador.addEventListener('click', () => {
            console.log('excluindo colaborador de id', idColaborador);

            fetch(`http:/teste-baumer/API/api.php?acao=excluir-colaborador`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({"id": parseInt(idColaborador)}),
            })
            .then(response => response.json())
            .then(data => {
                if (data.sucesso) {
                    alert('Colaborador excluido com sucesso!');
                    location.reload();
                } else {
                    alert('Erro ao excluir colaborador: ' + data.mensagem);
                }
            })
            .catch(error => {
                console.error('Erro ao enviar os dados:', error);
                alert('Ocorreu um erro ao excluir o colaborador.');
            });
        });

        //editar
        btnEditar.addEventListener('click', () => {
            console.log(`editando colaborador de id ${idColaborador}`);

            fetch(`http:/teste-baumer/API/api.php?acao=select-unico-colaborador`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({"id": parseInt(idColaborador)})
            })
            .then(response => response.json())
            .then(data => {
                console.log(data);

                let formColaborador = document.getElementById('formCadastroColaborador');
                console.log(formColaborador);

                let inputNome = formColaborador.children[0].children[1];
                inputNome.value = data.nome;

                let inputCpf = formColaborador.children[1].children[1];
                inputCpf.value = data.cpf;

                let inputEmail = formColaborador.children[2].children[1];
                inputEmail.value = data.email;

                let btnSalvarColaborador = document.getElementById('btnSalvarColaborador');
                btnSalvarColaborador.setAttribute('data-acao', 'editar');
                btnSalvarColaborador.setAttribute('data-idColaborador', idColaborador);
            })
            .catch(error => {
                console.error('Erro ao enviar os dados:', error);
                alert('Ocorreu um erro ao editar o colaborador.');
            });
        });
    }
});


//INTERAÇÕES COM TAREFAS
//inserirndo nova tarefa
document.getElementById('btnSalvarTarefa').addEventListener('click', function () {
    console.log('salvando tarefa...');

    // Obtém os dados do formulário
    const descricao = document.getElementById('descricao').value;
    const responsavel = document.getElementById('responsavel').value;
    const prioridade = document.getElementById('prioridade').value;
    const data_limite = document.getElementById('data_limite').value;
    //data de criação da tarefa será capturada na api no momento do insert
    //data de conclusão será capturada através do botão na listagem posteriormente


    // Validação básica
    if (!descricao || !responsavel || !prioridade || !data_limite) {
        alert('Por favor, preencha todos os campos.');
        return;
    }

    var acao;
    if(this.getAttribute('data-acao') == 'cadastrar'){
        fetch(`http:/teste-baumer/API/api.php?acao=cadastrar-tarefa`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ 
                "descricao": descricao, 
                "responsavel": responsavel, 
                "prioridade": prioridade,
                "data_limite": data_limite,
            }),
        })
        .then(response => response.json())
        .then(data => {
            if (data.sucesso) {
                alert('Tarefa cadastrada com sucesso!');
                document.getElementById('formCadastroTarefa').reset();
                // Fechar o modal
                var modal = bootstrap.Modal.getInstance(document.getElementById('cadastroTarefaModal'));
                modal.hide();
                // Recarregar a lista de colaboradores
                location.reload();
            } else {
                alert('Erro ao cadastrar tarefa: ' + data.mensagem);
            }
        })
        .catch(error => {
            console.error('Erro ao enviar os dados:', error);
            alert('Ocorreu um erro ao cadastrar a tarefa.');
        });
    }else if(this.getAttribute('data-acao') == 'editar'){
        fetch(`http:/teste-baumer/API/api.php?acao=editar-tarefa`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({"id": parseInt(this.getAttribute('data-idTarefa')), 
                                  "descricao": descricao,
                                  "responsavel": responsavel,
                                  "prioridade": prioridade,
                                  "data_limite": data_limite
                                }),
        })
        .then(response => response.json())
        .then(data => {
            if (data.sucesso) {
                alert('Tarefa editada com sucesso!');
                document.getElementById('formCadastroTarefa').reset();
                // Fechar o modal
                var modal = bootstrap.Modal.getInstance(document.getElementById('cadastroTarefaModal'));
                modal.hide();
                // Recarregar a lista de colaboradores
                location.reload();
            } else {
                alert('Erro ao editar tarefa: ' + data.mensagem);
            }
        })
        .catch(error => {
            console.error('Erro ao enviar os dados:', error);
            alert('Ocorreu um erro ao editar a tarefa.');
        });
    }
});

//adicionando interação de exclusão e edição para cada botão de tarefa
const tabelaTarefas = document.getElementById('tblTarefas');
let listaTarefas = tabelaTarefas.children[0].children;

Array.from(listaTarefas).forEach(tr => {
    if(tr.rowIndex !== 0){ //a primeira linha da tabela não deve entrar nessa interação pois não se trata de uma tarefa
        let btnExcluir = tr.children[3].children[0];
        var idTarefa = btnExcluir.getAttribute('data-idTarefa');
        let btnEditar = tr.children[2].children[0];
        let btnConcluir = tr.children[1].children[0];
        
        //exluir
        btnExcluir.addEventListener('click', () => {
            console.log('excluindo Tarefa de id', idTarefa);

            fetch(`http:/teste-baumer/API/api.php?acao=excluir-tarefa`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({"id": parseInt(idTarefa)}),
            })
            .then(response => response.json())
            .then(data => {
                if (data.sucesso) {
                    alert('Tarefa excluida com sucesso!');
                    location.reload();
                } else {
                    alert('Erro ao excluir tarefa: ' + data.mensagem);
                }
            })
            .catch(error => {
                console.error('Erro ao enviar os dados:', error);
                alert('Ocorreu um erro ao excluir a tarefa.');
            });
        });

        //editar
        btnEditar.addEventListener('click', () => {
            console.log(`editando colaborador de id ${idTarefa}`);

            fetch(`http:/teste-baumer/API/api.php?acao=select-unica-tarefa`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({"id": parseInt(idTarefa)})
            })
            .then(response => response.json())
            .then(data => {
                console.log(data);

                let formTarefa = document.getElementById('formCadastroTarefa');
                console.log(formTarefa);

                let inputDescricao = formTarefa.children[0].children[1];
                inputDescricao.value = data[0].descricao;

                let inputResponsavel = formTarefa.children[1].children[1];
                inputResponsavel.value = data[0].responsavel;

                let inputPrioridade = formTarefa.children[2].children[1];
                inputPrioridade.value = data[0].prioridade;

                let inputDataExpiracao = formTarefa.children[3].children[1];
                inputDataExpiracao.value = data[0].data_limite;

                let btnSalvarTarefa = document.getElementById('btnSalvarTarefa');
                btnSalvarTarefa.setAttribute('data-acao', 'editar');
                btnSalvarTarefa.setAttribute('data-idTarefa', idTarefa);
            })
            .catch(error => {
                console.error('Erro ao enviar os dados:', error);
                alert('Ocorreu um erro ao editar o tarefa.');
            });
        });

        //concluir
        btnConcluir.addEventListener('click', () => {
            console.log('concluindo Tarefa de id', idTarefa);

            fetch(`http:/teste-baumer/API/api.php?acao=concluir-tarefa`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({"id": parseInt(idTarefa)}),
            })
            .then(response => response.json())
            .then(data => {
                if (data.sucesso) {
                    alert('Tarefa concluida com sucesso!');
                    location.reload();
                } else {
                    alert('Erro ao concluir tarefa: ' + data.mensagem);
                }
            })
            .catch(error => {
                console.error('Erro ao enviar os dados:', error);
                alert('Ocorreu um erro ao concluir a tarefa.');
            });
        });
    }
});


document.addEventListener("DOMContentLoaded", function () {
    const filtroColaborador = document.getElementById("filtroColaborador");
    const filtroPrioridade = document.getElementById("filtroPrioridade");
    const filtroData = document.getElementById("filtroData");
    const ordenarTarefas = document.getElementById("ordenarTarefas");
    const tabelaTarefas = document.getElementById("tblTarefas");

    function aplicarFiltros() {
        const colaboradorFiltro = filtroColaborador.value;
        const prioridadeFiltro = filtroPrioridade.value;
        const dataFiltro = filtroData.value ? new Date(filtroData.value) : null;

        const rows = tabelaTarefas.querySelectorAll("tr.row");

        rows.forEach((row) => {
            const colaborador = row.getAttribute("data-colaborador");
            const prioridade = row.getAttribute("data-prioridade");
            const dataLimite = row.getAttribute("data-limite") ? new Date(row.getAttribute("data-limite")) : null;

            const colaboradorMatch = colaboradorFiltro === "todos" || colaboradorFiltro === colaborador;
            const prioridadeMatch = prioridadeFiltro === "todas" || prioridadeFiltro === prioridade;
            const dataMatch = !dataFiltro || (dataLimite && dataFiltro.toDateString() === dataLimite.toDateString());

            if (colaboradorMatch && prioridadeMatch && dataMatch) {
                row.style.display = "";
            } else {
                row.style.display = "none";
            }
        });
    }

    function organizarTarefas() {
        const criterio = ordenarTarefas.value;
        const rows = Array.from(tabelaTarefas.querySelectorAll("tr.row"));

        rows.sort((a, b) => {
            let valorA, valorB;

            switch (criterio) {
                case "descricao":
                    valorA = a.querySelector("h4").textContent.trim().toLowerCase();
                    valorB = b.querySelector("h4").textContent.trim().toLowerCase();
                    break;
                case "prioridade":
                    const prioridades = { alta: 1, media: 2, baixa: 3 };
                    valorA = prioridades[a.getAttribute("data-prioridade")];
                    valorB = prioridades[b.getAttribute("data-prioridade")];
                    break;
                case "data_limite":
                    valorA = new Date(a.getAttribute("data-limite"));
                    valorB = new Date(b.getAttribute("data-limite"));
                    break;
            }

            return valorA > valorB ? 1 : -1;
        });

        rows.forEach((row) => tabelaTarefas.querySelector("tbody").appendChild(row));
    }

    // Eventos
    filtroColaborador.addEventListener("change", aplicarFiltros);
    filtroPrioridade.addEventListener("change", aplicarFiltros);
    filtroData.addEventListener("change", aplicarFiltros);
    ordenarTarefas.addEventListener("change", organizarTarefas);
});
