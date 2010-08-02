<?php

/*
Plugin Name: wordpress-carrinho-moip
Version:1.0
Plugin URI: http://www.andrewebmaster.com.br/internet/wp-content/plugins/wordpress-carrinho-moip
Author: AndrÈ Luiz de Oliveira, andre@andrewebmaster.com.br(Suporte Moip)
Author URI: http://www.andrewebmaster.com.br/internet/?p=206
Description: Plugin simples de carrinho de compras <a href="https://www.moip.com.br">MoIP</a>,para que vender produtos utilizando um carrinho simples.
Pela internet Baseado no plugin <a href="http://www.tipsandtricks-hq.com/wordpress-simple-paypal-shopping-cart-plugin-768">Simple Paypal Shopping Cart</a>.
*/

/*
    This program is free software; you can redistribute it
    under the terms of the GNU General Public License version 2,
    as published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.
*/

session_start();

function ps_shopping_cart_show($content)
{
	if (strpos($content, "<!--ativar_carrinho_MoIP-->") !== FALSE)
    {
    	if (ps_cart_not_empty())
    	{
        	$content = preg_replace('/<p>\s*<!--(.*)-->\s*<\/p>/i', "<!--$1-->", $content);
        	$matchingText = '<!--ativar_carrinho_MoIP-->';
        	$replacementText = ps_print_wp_shopping_cart();
        	$content = str_replace($matchingText, $replacementText, $content);
    	}
    }
    return $content;
}

if ($_POST['addcart'])
{
    $count = 1;    
    $products = $_SESSION['pssimpleCart'];
    
    if (is_array($products))
    {
        foreach ($products as $key => $item)
        {
            if ($item['name'] == $_POST['product'])
            {
                $count += $item['quantity'];
                $item['quantity']++;
                unset($products[$key]);
                array_push($products, $item);
            }
        }
    }
    else
    {
        $products = array();
    }
        
    if ($count == 1)
    {
        if (!empty($_POST[$_POST['product']]))
            $valor = $_POST[$_POST['product']];
 
        else
            $valor = $_POST['valor'];

        
        $product = array('name' => stripslashes($_POST['product']), 'valor' => $valor, 'quantity' => $count, 'cartLink' => $_POST['cartLink'], 'item_number' => $_POST['item_number']);
        array_push($products, $product);
    }
    
    sort($products);
    $_SESSION['pssimpleCart'] = $products;
}
else if ($_POST['cquantity'])
{
    $products = $_SESSION['pssimpleCart'];
    foreach ($products as $key => $item)
    {
        if (($item['name'] == $_POST['product']) && $_POST['quantity'])
        {
            $item['quantity'] = $_POST['quantity'];
            unset($products[$key]);
            array_push($products, $item);
        }
        else if (($item['name'] == $_POST['product']) && !$_POST['quantity'])
            unset($products[$key]);
    }
    sort($products);
    $_SESSION['pssimpleCart'] = $products;
}
else if ($_POST['delcart'])
{
    $products = $_SESSION['pssimpleCart'];
    foreach ($products as $key => $item)
    {
        if ($item['name'] == $_POST['product'])
            unset($products[$key]);
    }
    $_SESSION['pssimpleCart'] = $products;
}

function ps_print_wp_shopping_cart()
{
	if (!ps_cart_not_empty())
	{
		return;
	}
    $email = get_bloginfo('admin_email');
       
    $defaultEmail = get_option('cart_moip_email');
    $moip_symbol = 'R$';
$url_images = get_bloginfo('wpurl')."/wp-content/plugins/wordpress-carrinho-moip/images/";

    if (!empty($defaultEmail))
        $email = $defaultEmail;
     
    $decimal = '.';  
	$urls = '';
	  
	$title = get_option('wp_cart_title');
	if (empty($title)) $title = 'Suas compras';
    
    $output .= '<div class="shopping_cart" style=" padding: 5px;">';
    $output .= "<input type='image' src='".get_bloginfo('wpurl')."/wp-content/plugins/wordpress-carrinho-moip/images/moip_checkout.png' value='Carrinho' title='Carrinho' />";
    $output .= "<h2>";
    $output .= $title;  
    $output .= "</h2>";  
        
    $output .= "<br /><span id='pinfo' class='msg_alent' style='display: none; font-weight: bold;'><img src='".get_bloginfo('wpurl')."/wp-content/plugins/wordpress-carrinho-moip/images/info.png' />Pressione ENTER para atualizar a quantidade.</span>";
	$output .= '<table style="width: 100%;" border="0">';    
    
    $count = 1;
    $total_items = 0;
    $total = 0;
    $form = '';

    if ($_SESSION['pssimpleCart'] && is_array($_SESSION['pssimpleCart']))
    {   
        $output .= '
        <tr>
        <th>Produto</th><th><center>Qtde</center></th><th><center>Valor</center></th>
        </tr>';
    
    foreach ($_SESSION['pssimpleCart'] as $item)
    {
        $total += $item['valor'] * $item['quantity'];
        
        $total_items +=  $item['quantity'];
        
        $item['total'] == $total;

    }

    
    foreach ($_SESSION['pssimpleCart'] as $item)
    {
        $output .= "
                 
        <tr><td class='wp_cart_campo' style='overflow: hidden;'><a href='".$item['cartLink']."'>".$item['name']."</a></td>
        <td style='text-align: center'><form method=\"post\"  action=\"\" name='pcquantity' style='display: inline'>
        <input type='hidden' class='wp_cart_campo' name='product' value='".$item['name']."' />
        
        <input type='hidden' name='cquantity' value='1' /><input type='text' class='wp_cart_input' name='quantity' value='".$item['quantity']."' size='1' onchange='document.pcquantity.submit();' onkeypress='document.getElementById(\"pinfo\").style.display = \"\";' /></form></td>
        <td class='wp_cart_campo' style='text-align: center'>".ps_print_payment_currency(($item['valor'] * $item['quantity']), $moip_symbol, $decimal)."</td>
        <td><form method=\"post\"  action=\"\">
        <input type='hidden' name='product' value='".$item['name']."' />
        <!--<input type='hidden' name='product' value='".$pieces['0']."' />-->
        <input type='hidden' name='delcart' value='1' />
        <input type='image' class='wp_cart_button' src='".get_bloginfo('wpurl')."/wp-content/plugins/wordpress-carrinho-moip/images/btn_delete.png' value='Remove' title='Remover' />
         </form></td></tr>
        
        ";
        
        $form .= "
            <input type=\"hidden\" name=\"nome\" value=\"Total de [".$total_items."] produtos.\" />
            <input type=\"hidden\" name=\"valor\" value='".trata_valor($total)."' />            
            <input type='hidden' name='descricao' value='Adquiridos no site [".ps_cart_current_page_url()."], produto indicativo [".$item['name']."].' />
        ";
        $form .= "<input type=\"hidden\" name=\"frete\" value=\"0\" />";
        $count++;
    }
    }
    
       	$count--;
       	
       	if ($count)
       	{
       		$output .= '<tr><td></td><td></td><td></td></tr>';       
       		$output .= "
       		<tr><td colspan='2' style='font-weight: bold; text-align: right;'>Total: </td><td style='text-align: center'>".ps_print_payment_currency(($total), $moip_symbol, $decimal)."</td><td></td></tr>
       		<tr><td colspan='4'>";

       
              	$output .= "<form action=\"https://www.moip.com.br/PagamentoMoIP.do\" target=\"moip\" method=\"post\">$form";
    			if ($count)
            		$output .= '<input type="image" class="wp_cart_button" src="https://www.moip.com.br/imgs/buttons/bt_pagar_c01_e04.png" name="submit" alt="MoIP" />';
       
    			$output .= $urls.'
			    <input type="hidden" name="id_carteira" value="'.$email.'" />
			    </form>';          
       	}       
       	$output .= "
       
       	</td></tr>
    	</table></div>
    	";
    
    return $output;
}

function trata_valor($total)
{
    $total_valor = ps_print_payment_currency($total, "", "");
    return str_replace(',','',$total_valor);
    return str_replace('.','',$total_valor);
}

function ps_print_wp_cart_button($content)
{          
        $addcart = get_option('addToCartButtonName');
    
        if (!$addcart || ($addcart == '') )
            $addcart = 'Adicionar ao Carrinho';
        
        $pattern = '#\[moip=.+:valor=#';
        preg_match_all ($pattern, $content, $matches);
        
        foreach ($matches[0] as $match)
        {            
            $pattern = '[moip=';
            $m = str_replace ($pattern, '', $match);
            $pattern = ':valor=';
            $m = str_replace ($pattern, '', $m);
            
            $pieces = explode('|',$m);      
            
            if (sizeof($pieces) == 1)
            {      
                $replacement = '<object><form method="post"  action=""  style="display:inline">
                <input type="image" class="wp_cart_button_add" src="'.get_bloginfo('wpurl').'/wp-content/plugins/wordpress-carrinho-moip/images/add_moip.png" name="submit" value="Adicionar ao carrinho MoIP" title="Adicionar ['.$pieces['0'].'] ao carrinho MoIP" />

                <input type="hidden" name="product" value="'.$pieces['0'].
                '" /><input type="hidden" name="valor" value="';
                
                $content = str_replace ($match, $replacement, $content);
            }   
        }
    
        $forms = str_replace(':item_num:',    
        '" /><input type="hidden" name="shipping" value="',    
        $content);  
               
        $forms = str_replace(':end]',    
        '" /><input type="hidden" name="addcart" value="1" /><input type="hidden" name="cartLink" value="'.ps_cart_current_page_url().'" />
        </form></object>',    
        $forms);
    
    if (empty($forms))
        $forms = $content;
       
    return $forms;
}

function ps_cart_not_empty()
{
        $count = 0;
        if (isset($_SESSION['pssimpleCart']) && is_array($_SESSION['pssimpleCart']))
        {
            foreach ($_SESSION['pssimpleCart'] as $item)
                $count++;
            return $count;
        }
        else
            return 0;
}

function ps_print_payment_currency($valor, $symbol, $decimal)
{
    return $symbol.number_format($valor, 2, $decimal, ',');
}

function ps_cart_current_page_url() {
 $pageURL = 'http';
 if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
 $pageURL .= "://";
 if ($_SERVER["SERVER_PORT"] != "80") {
  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
 } else {
  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
 }
 return $pageURL;
}

function ps_show_ps_wp_cart_options_page () {
	
	$wp_simple_moip_shopping_cart_version = 1.1;
	
    $defaultEmail = get_option('cart_moip_email');
    if (empty($defaultEmail)) $defaultEmail = get_bloginfo('admin_email');
    
    $addcart = get_option('addToCartButtonName');
    if (empty($addcart)) $addcart = 'Adicionar ao Carrinho';           

	$title = get_option('wp_cart_title');
	if (empty($title)) $title = 'Suas compras';
      
	?>
 	<h2>Opcoes do Carrinho Simples WordPress MoIP v <?php echo $wp_simple_moip_shopping_cart_version; ?></h2>
 	
 	<p>Para se informar sobre suporte, por favor, visite:<br />
    <a href="http://www.andrewebmaster.com.br/internet/?p=206">http://www.andrewebmaster.com.br/internet/?p=206</a></p>
    
     <fieldset class="options">
    <legend>Como usar:</legend>

    <p>1. O primeiro passo sera <a href="http://www.moip.com.br/MainMenu.do?method=login" title="Cadastro conta Moip" target="_blank">Criar CONTA free Moip>></a>
    <hr>
    2- Coloque o e-mail cadstrado como principal no sitema MOIP no formulario abaixo!
    <hr>
    3-Para adicionar um botao 'Adicionar ao Carrinho' simplesmente insira o texto Exemplo:<strong>[moip=Camisa-verde-(M):valor=45.00:end]</strong> ao artigo ou pagina, proximo ao produto. Substitua NOME-DO-PRODUTO e VALOR-DO-PRODUTO pelo nome e valor reais, Exemplo: [moip=Camisa-verde-(M):valor=45.00:end].</p>
    <hr>
	<p>4- Para adicionar o carrinho de compras a um artigo ou pagina de checkout ou a um  sidebar simplesmente adicione o texto <strong> <!--ativar_carrinho_MoIP--> </strong> a um post, pagina ou sidebar. O carrinho sera visivel quando o comprador adicionar pelo menos um produto.
	<hr>
    </fieldset>
    - Ficariamos agradecidos pela sua doacao para melhorias deste plugin:
    em:<a href="http://www.siteflash.com.br/doacao" title="DoaÁıes Plugin" target="_blank"><img src="https://www.moip.com.br/imgs/buttons/bt_doar_c03_e01.png" border="0"></a>
    <a href="http://www.siteflash.com.br/doacao" title="DoaÁ„o Desenvolvimento" target="_blank">Doar para o Plugin>></a>
       <hr>
          - Para adquirir Loja e-Commerce ja com o sitema MOIP instalado e configurado acesse site de Webmaster<img src="http://www.siteflash.com.br/icone.jpg" border="0"> SIETFLASH S/A <a href="http://www.siteflash.com.br/servidor/?p=544" title="AquisiÁ„o de Loja e-Commerce" target="_blank"><b>Clicando AQUI>></b></a>
        <hr>
           - Chat para Discutir Plugin suporte <a href="http://www.siteflash.com.br/chat" title="discuss„o plugin moip" target="_blank"><img src="http://www.andrewebmaster.com.br/internet/wp-content/plugins/wordpress-carrinho-moip/images/discussao-moip.jpg" border="0"></a>:<a href="http://www.siteflash.com.br/chat" title="Discuss„o" target="_blank">Solicitar Suporte>></a>
           - <a href="http://www.andrewebmaster.com.br/internet/?p=206" title="Tutorial Imagens" target="_blank">Ver ==screenshot== Imagens>></a>
     <?php
 
    echo '
 <form method="post" action="options.php">';
 wp_nonce_field('update-options');
 echo '
<table class="form-table">
<tr valign="top">
<th scope="row">E-mail de cadastro MoIP</th>
<td><input type="text" name="cart_moip_email" value="'.$defaultEmail.'" /></td>
</tr>
<tr valign="top">
<th scope="row">T√≠tulo do carrinho de compras</th>
<td><input type="text" name="wp_cart_title" value="'.$title.'"  /></td>
</tr>

<tr valign="top">
<th scope="row">Texto do bot√£o de adicionar ao carrinho</th>
<td><input type="text" name="addToCartButtonName" value="'.$addcart.'" /></td>
</tr>

</table>

<p class="submit">
<input type="submit" name="Submit" value="Salvar Op√ß√µes &raquo;" />
<input type="hidden" name="action" value="update" />
<input type="hidden" name="page_options" value="cart_payment_currency,cart_currency_symbol,cart_moip_email,addToCartButtonName,wp_cart_title" />
</p>

 </form>
 ';
}

function ps_wp_cart_options()
{
     echo '<div class="wrap"><h2>Op√ß√µes do Carrinho MoIP</h2>';
     ps_show_ps_wp_cart_options_page();
     echo '</div>';
}

// Display The Options Page
function ps_wp_cart_options_page () 
{
     add_options_page('Carrinho MoIP', 'MoIP Pagamentos', 'manage_options', __FILE__, 'ps_wp_cart_options');  
}

function show_wp_moip_shopping_cart_widget()
{
    echo ps_print_wp_shopping_cart();
}

function wp_moip_shopping_cart_widget_control()
{
    ?>
    <p>
    <? _e("Configure as op√ß√µes do plugin no menu de op√ß√µes."); ?>
    </p>
    <?php
}

function widget_wp_moip_shopping_cart_init()
{
    $widget_options = array('classname' => 'widget_wp_moip_shopping_cart', 'description' => __( "Mostra o carrinho de compras MoIP.") );
    wp_register_sidebar_widget('wp_moip_shopping_cart_widgets', __('Carrinho MoIP'), 'show_wp_moip_shopping_cart_widget', $widget_options);
    wp_register_widget_control('wp_moip_shopping_cart_widgets', __('Carrinho MoIP'), 'wp_moip_shopping_cart_widget_control' );
}

function wp_cart_css()
{
    echo '<link type="text/css" rel="stylesheet" href="'.get_bloginfo('wpurl').'/wp-content/plugins/wordpress-moip-shopping-cart/moip_style.css" />'."\n";
}
// Insert the options page to the admin menu
add_action('admin_menu','ps_wp_cart_options_page');

add_action('init', 'widget_wp_moip_shopping_cart_init');

add_filter('the_content', 'ps_print_wp_cart_button');

add_filter('the_content', 'ps_shopping_cart_show');

add_action('wp_head', 'wp_cart_css');

?>
