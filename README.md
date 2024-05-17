# Sistema de Registro Pangya

Este é um sistema de registro de usuários para Pangya, que inclui confirmação por e-mail (JWT) e integração com o Google reCAPTCHA v2 para maior segurança. O sistema é construído usando PHP, Microsoft SQL Server e PHPMailer para envio de e-mails.

## Funcionalidades

- Registro de usuários com confirmação por e-mail.
- Integração com Google reCAPTCHA v2 para prevenção de spam.
- Envio de e-mails usando PHPMailer.
- Integração com banco de dados SQL Server.

## Pré-requisitos

- WampServer ou qualquer outro servidor local configurado com PHP e SQL Server.
- Composer para gerenciamento de pacotes PHP.
- Uma chave de site e uma chave secreta do Google reCAPTCHA v2.
- Um servidor SMTP para envio de e-mails.

## Instalação

1. **Clone o repositório:**

   ```sh
   git clone https://github.com/seuusuario/pangya-registration.git
   cd pangya-registration
   ```

2. **Instale as dependências do Composer:**

   ```sh
   composer install
   ```

3. **Configure o config.php:**

   Edite o arquivo config.php para definir suas configurações de banco de dados, e-mail e reCAPTCHA.

4. **Atualize o HTML para o Google reCAPTCHA:**

   Adicione sua chave de site reCAPTCHA ao formulário HTML em index.html:

   ```sh
   <div class="g-recaptcha" data-sitekey="SUA_SITE_KEY_DO_RECAPTCHA"></div>
   ```

5. **Execute o servidor local:**

   Inicie seu WampServer ou configuração de servidor local.

## Uso

1. **Acesse o formulário de registro:**

   Abra o seu navegador e navegue até http://seudominio.com/caminho-para-seu-projeto.

2. **Preencha o formulário de registro:**

   Complete o formulário com as informações necessárias e resolva o reCAPTCHA.

3. **Confirmação por e-mail:**

   Verifique seu e-mail para o link de confirmação e clique nele para ativar sua conta.

## Solução de Problemas

- Problemas de conexão com o banco de dados: Certifique-se de que o SQL Server está em execução e acessível. Verifique as credenciais em config.php.
- Problemas de envio de e-mail: Verifique suas configurações SMTP em config.php. Certifique-se de que seu servidor permite e-mails de saída.
- Erros do reCAPTCHA: Certifique-se de que as chaves do site e secretas estão corretamente configuradas em config.php.

## Solução de Problemas

Sinta-se à vontade para enviar problemas ou pull requests. Contribuições são bem-vindas!

## Licença

Este projeto está licenciado sob a Licença MIT. Veja o arquivo LICENSE para mais detalhes.

