document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('form-foto-perfil').addEventListener('submit', function(event) {
        event.preventDefault();

        const formData = new FormData(); // Cria um objeto FormData vazio

        // Adiciona o arquivo de foto_perfil ao FormData
        const fileField = document.querySelector('input[type="file"]');
        formData.append('foto_perfil', fileField.files[0]);

        const requestOptions = {
            method: 'POST',
            body: formData,
            redirect: 'follow'
        };

        fetch('http://localhost/Adotyx-1/upload-profile-picture.php', requestOptions)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erro ao enviar a foto de perfil');
                }
                return response.json();
            })
            .then(data => {
                alert(data.msg); // Mostra o resultado do envio da foto em um alerta

                // Redireciona para a página de conta se o envio for bem-sucedido
                if (data.msg === 'Foto de perfil enviada com sucesso') {
                    window.location.href = './account.html';
                }
            })
            .catch(error => {
                console.error('Erro ao enviar a foto de perfil:', error);
                alert('Erro ao enviar a foto de perfil');
            });
    });
});
