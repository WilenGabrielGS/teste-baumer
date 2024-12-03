# teste-baumer
projeto de listagem de tarefas relacionadas com colaboradores

# Passos para instalação e execução: 

## Banco de dados
* Para rodar o projeto é necessário ter um banco de dados MySql configurado. No desenvolvimento, utilizei o MySql workbench para manipular o banco de dados mas você pode usar outro gerenciador.
<br>link para download do MySql Workbench: <a href="https://dev.mysql.com/downloads/installer/">MySql Workbench</a>
<br>link do passo a passo de instalação e configuração do MySql Workbench caso necessário : <a href="https://www.alura.com.br/artigos/mysql-do-download-e-instalacao-ate-sua-primeira-tabela?srsltid=AfmBOoo_LDgZKMkMjAS72q4Ias6H3lOl6ovQaijyX3neDTyt5OxXmuLC">passo a passo workbench</a>
* Após escolher o seu gerenciador, execute o script dentro do arquivo <a href="https://github.com/WilenGabrielGS/teste-baumer/blob/main/script%20banco.sql">script banco.sql</a> para criar a base de dados do projeto que estará vazia inicialmente
* ***ATENÇÃO:*** verifique o arquivo <a href="https://github.com/WilenGabrielGS/teste-baumer/blob/main/conexao.php">conexao.php</a> para confirmar se o seu banco de dados está corretamente conectado.

## Servidor PHP Local
* Com o banco de dados configurado, agora é necessário ter um servidro local para rodar o PHP. Para isso utilizei o XAMPP mas você pode escolher qualquer outro servidor
* ***ATENÇÃO:*** o projeto precisa ser rodado em localhost pois as URL's da API foram escritas contendo o nome do servidor. Se você escolher um servidor que não seja o localhost, certifique-se de modificar as URL's de comunicação com a API em ***TODO*** o código.
<br>link para download do XAMPP: <a href="https://www.apachefriends.org/download.html">xampp download</a>
<br>link para passo a passo de instalação e configuração do XAMPP: <a href="https://pt.wikihow.com/Instalar-o-XAMPP-para-Windows">configar xampp</a>
* após instalar o XAMPP, inicie o serviço *apache* para que seja possível rodar o PHP. Caso use outro servidor, certifique-se de que o mesmo consegue rodar PHP.
* agora, insira esse repositório dentro da pasta *htdocs* do XAMPP ou na pasta que você deseja rodar o projeto no seu servidor


## Executando o projeto
* Com tudo instalado, configurado e rodando, abra o navegador e digite http://localhost/teste-baumer/

 
