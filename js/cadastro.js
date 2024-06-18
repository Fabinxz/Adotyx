document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('login-form').addEventListener('submit', function(event) {
        event.preventDefault();

        const formData = new FormData(this);
        const urlencoded = new URLSearchParams();

        formData.forEach((value, key) => {
            urlencoded.append(key, value);
        });

        const myHeaders = new Headers();
        myHeaders.append('Content-Type', 'application/x-www-form-urlencoded');

        const requestOptions = {
            method: 'POST',
            headers: myHeaders,
            body: urlencoded,
            redirect: 'follow'
        };

        fetch('http://localhost/Adotyx-1/usuario.php', requestOptions)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erro ao realizar login');
                }
                return response.json();
            })
            .then(data => {
                alert(data.msg); // Mostra o resultado do login em uma janela de alerta

                if (data.msg === 'Login realizado com sucesso') {
                    window.location.href = './account.html'; // Redireciona após login bem-sucedido
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                alert('Erro ao realizar login');
            });
    });
});
