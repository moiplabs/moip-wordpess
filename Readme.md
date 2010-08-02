wordpress-carrinho-moip
=======================

Contribucões: André Luiz de Oliveira, Vagner Fiuza Vieira (Suporte MoIP)

URL do autor: http://www.andrewebmaster.com.br/internet/?p=206 

URL DO PLUGIN: http://www.andrewebmaster.com.br/internet/wp-content/plugins/wordpress-carrinho-moip

URL readme:http://www.andrewebmaster.com.br/internet/wp-content/plugins/wordpress-carrinho-moip/readme.txt

Requirerimento: Theme com menu lateral e widgest Versão Wordpress 2.7 ou superior.

Descrição
---------


Plugin Shopping Cart Wordpress

Plugin baseado em Shopping-Cart (http://www.tipsandtricks-hq.com/wordpress-simple-paypal-shopping-cart-plugin-768) para integração com o sistema de Pagamento MoIP http://www.moip.com.br.
Integra carrinho de compras no menu lateral de wordpress.

Instalação
-----------

1- Faça o upload da pasta **wordpress-moip-shopping-cart** para o diretório **/wp-content/plugins/**

2- Crie uma conta gratuita para E-Commerce na URL: https://www.moip.com.br/CreateWallet.do

3- Ative o plugin em área administrativa Wordpress > configurações > Carrinho Moip 
configura o endereço de e-mail principal utilizado no sistema MoIP. 

4- Como integrar a seu Wordpress:

4.1- O Primeiro passo é criar uma conta free no site de www.moip.com.br acessando URL: http://www.moip.com.br/MainMenu.do?method=login

4.2- O e-mail configurado na area de configurações WordpRess em Moip Pagamentos deve ser o e-mail principal de sua conta Moip criada.

4.3- Para adicionar um botão 'Adicionar ao Carrinho' simplesmente insira o texto na pagina ou post  [moip=Camisa-verde-(M):valor=45.00:end] ao artigo ou página, próximo ao produto ou local a sua  escolha.

Substitua NOME-DO-PRODUTO e VALOR-DO-PRODUTO pelo nome e valor reais, assim: 

Exemplo: [moip=Camisa-verde-(M):valor=45.00:end]

 
4.2- Para adicionar o carrinho de compras a um artigo ou página de checkout ou à sidebar simplesmente adicione o texto <!--ativar_carrinho_MoIP--> a um post, página ou sidebar, caso não tenha windegst disponível em seu site será possível incluir o carrinho MoIP em qualquer post que você deseje.

Exemplo: <!--ativar_carrinho_MoIP-->
 

O carrinho só será visível quando o comprador adicionar pelo menos um produto.

F.A.Q
--------------------

**1-Como fazer se meu tema não suportar Slidebar?**

R:1- Basta colocar o codigo <!--ativar_carrinho_MoIP--> na mesma postagem ou pagina do produto ou produtos integrados.

**2-Como fazer se meu slidebar for estreito para imagem de carrinho?**

R:2- Colocamos 3 tipos e tamanho de imagens na pasta "wordpress-moip-shopping-cart" basta editar  a linha 129 do arquivo wp_shopping_cart.php e mudar a imagem. Sugerimos o formato .png

**3-Suporte Online para o plugin;**

URL: http://www.andrewebmaster.com.br/internet/?p=206

Chat suporte discussão: http://www.siteflash.com.br/chat

Doações através sitema MOIP: http://www.siteflash.com.br/doacao

Screenshot 
----------

URL de screenshot: http://www.andrewebmaster.com.br/internet/?p=206
