
    

    function popularSelectUsuarios(usuarios) {
        let selectUsuarioSender = document.getElementById(
            "selectUsuarioSender"
        );
        let selectUsuarioReciever = document.getElementById(
            "selectUsuarioReciever"
        );

        // Limpa os selects antes de adicionar novas opções
        selectUsuarioSender.innerHTML = "";
        selectUsuarioReciever.innerHTML = "";

        // Adiciona uma opção para cada usuário em ambos os selects
        usuarios.forEach((usuario) => {
            const optionSender = document.createElement("option");
            const optionReciever = document.createElement("option");

            optionSender.value = usuario.id;
            optionSender.textContent = `${usuario.username} (ID: ${usuario.id})`;
            selectUsuarioSender.appendChild(optionSender);

            optionReciever.value = usuario.id;
            optionReciever.textContent = `${usuario.username} (ID: ${usuario.id})`;
            selectUsuarioReciever.appendChild(optionReciever);
        });
    }

    // Função para fazer a requisição GET e popular os selects
    async function carregarUsuarios() {
        try {
            const response = await fetch(`${ip}/chat/usuario.php`);
            const data = await response.json();

            // Verifica se a requisição foi bem-sucedida
            if (response.ok) {
                popularSelectUsuarios(data);
            } else {
                console.error("Erro ao carregar usuários:", data.msg);
            }
        } catch (error) {
            console.error("Erro ao processar requisição:", error);
        }
    }

    // Event listener para carregar usuários quando a página carregar
    document.addEventListener("DOMContentLoaded", () => {
        carregarUsuarios();
    });

    // Event listener para lidar com o envio do formulário de mensagem
    document
        .getElementById("formSelecionarUsuarios")
        .addEventListener("submit", (event) => {
            event.preventDefault(); // Evita o comportamento padrão de submissão do formulário

            const idUsuarioSender = document.getElementById(
                "selectUsuarioSender"
            ).value;
            const idUsuarioReciever = document.getElementById(
                "selectUsuarioReciever"
            ).value;
            const mensagem = document.getElementById("msgBox").value;

            enviarMensagem(idUsuarioSender, idUsuarioReciever, mensagem);
        });

    // Função para enviar uma mensagem
    function enviarMensagem(idUsuarioSender, idUsuarioReciever, mensagem) {
        const formData = new FormData();
        formData.append("idSender", idUsuarioSender);
        formData.append("idReciever", idUsuarioReciever);
        formData.append("msg", mensagem);

        fetch(`${ip}/chat/mensagem.php`, {
            method: "POST",
            body: formData,
        })
            .then((response) => {
                if (!response.ok) {
                    throw new Error(
                        `Erro na requisição: ${response.status} - ${response.statusText}`
                    );
                }
                return response.json();
            })
            .then((data) => {
                console.log("Resposta recebida:", data);
                // Atualiza as mensagens exibidas
                receberMensagens(idUsuarioSender, idUsuarioReciever);
            })
            .catch((error) => {
                console.error("Erro:", error);
            });
            atualizarMensagens(idUsuarioSender,idUsuarioReciever);
    }

    // Função para receber as mensagens entre dois usuários
    function receberMensagens(idUsuarioSender, idUsuarioReciever) {
        const formData = new FormData();
        formData.append("idSender", idUsuarioSender);
        formData.append("idReciever", idUsuarioReciever);
        formData.append("recuperarMensagem", true);

        fetch(`${ip}/chat/mensagem.php`, {
            method: "POST",
            body: formData,
        })
            .then((response) => {
                if (!response.ok) {
                    throw new Error(
                        `Erro na requisição: ${response.status} - ${response.statusText}`
                    );
                }
                return response.json();
            })
            .then((data) => {
                const mensagensDiv = document.getElementById("mensagens");
                mensagensDiv.innerHTML = "";

                data.forEach((mensagem) => {
                    const mensagemElement = document.createElement("div");
                    mensagemElement.textContent = `De: ${mensagem.sender_id}, Para: ${mensagem.receiver_id}, Mensagem: ${mensagem.mensagem}`;
                    mensagensDiv.appendChild(mensagemElement);
                });
            })
            .catch((error) => {
                console.error("Erro ao receber mensagens:", error);
            });
    }

    // Função para recarregar as mensagens
    function recarregarMensagens() {
        const idUsuarioSender = document.getElementById("selectUsuarioSender").value;
        const idUsuarioReciever = document.getElementById("selectUsuarioReciever").value;
        receberMensagens(idUsuarioSender, idUsuarioReciever);
    }

    // Adiciona um evento de clique ao botão de recarregar mensagens
    document.getElementById("reloadMessagesButton").addEventListener("click", recarregarMensagens);

    // Função para atualizar as mensagens a cada segundo
    function atualizarMensagens(idUsuarioSender, idUsuarioReciever) {
        setInterval(() => {
            receberMensagens(idUsuarioSender, idUsuarioReciever);
        }, 1000);
    }