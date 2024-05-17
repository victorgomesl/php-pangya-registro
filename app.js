document.getElementById('registrationForm').addEventListener('submit', async function(event) {
    event.preventDefault();

    const nome = document.getElementById('nome').value;
    const sobrenome = document.getElementById('sobrenome').value;
    const email = document.getElementById('email').value;
    const senha = document.getElementById('senha').value;
    const senha2 = document.getElementById('senha2').value;
    const dataNascimento = document.getElementById('dataNascimento').value;
    const termos = document.getElementById('termos').checked;
    const recaptchaResponse = grecaptcha.getResponse();

    if (senha !== senha2) {
        alert("As senhas não coincidem!");
        return;
    }

    if (!recaptchaResponse) {
        alert("Por favor, confirme que você não é um robô.");
        return;
    }

    const data = {
        nome: nome,
        sobrenome: sobrenome,
        email: email,
        senha: senha,
        dataNascimento: dataNascimento,
        termos: termos,
        recaptchaResponse: recaptchaResponse
    };

    // Aplicar o dominio correto no lugar de localhost:8080 
    try {
        const response = await fetch('http://localhost:8080/register.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        });

        const result = await response.json();
        alert(result.message);
    } catch (error) {
        console.error('Erro ao cadastrar:', error);
    }
});
