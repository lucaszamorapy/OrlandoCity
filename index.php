<?php

require 'inc/configuration.php';

require 'inc/Slim-2.x/Slim/Slim.php';

\Slim\Slim::registerAutoloader();


$app = new \Slim\Slim();


// GET route
$app->get(
    '/',
    function () {

        require_once("view/index.php");

        
    }
);

$app->get(
    '/videos.html',
    function () {

        require_once("view/videos.php");

        
    }
);

$app->get(
    '/shop',
    function () {

        require_once("view/shop.php");

        
    }
);

$app->get('/produtos', function(){

    
    $sql = new Sql();

    $data = $sql->select("SELECT * FROM tb_produtos where preco_promorcional > 0 order by preco_promorcional desc limit 3 ;");

    foreach ($data as &$produto) {
        $preco = $produto['preco'];
        $centavos = explode(".", $preco);
        $produto['preco'] = number_format($preco, 0, ",", ".");
        $produto['centavos'] = end($centavos);
        $produto['parcelas'] = 10;
        $produto['parcela'] = number_format($preco/$produto['parcelas'], 2, ",", ".");
        $produto['total'] = number_format($preco, 2, ",", ".");
        $produto['nome_prod_longo'] = utf8_decode($produto['nome_prod_longo']);
    }
    
    

    echo json_encode($data);
    
});

$app->get('/produtos-mais-buscados', function(){

    
    $sql = new Sql();

    $data = $sql->select("SELECT 
    tb_produtos.id_prod,
    tb_produtos.nome_prod_curto,
    tb_produtos.nome_prod_longo,
    tb_produtos.codigo_interno,
    tb_produtos.id_cat,
    tb_produtos.preco,
    tb_produtos.peso,
    tb_produtos.largura_centimetro,
    tb_produtos.altura_centimetro,
    tb_produtos.quantidade_estoque,
    tb_produtos.preco_promorcional,
    tb_produtos.foto_principal,
    tb_produtos.visivel,
    cast(avg(review) as dec(10,2)) as media, 
    count(id_prod) as total_reviews
    FROM tb_produtos 
    INNER JOIN tb_reviews USING(id_prod) 
    GROUP BY 
    tb_produtos.id_prod,
    tb_produtos.nome_prod_curto,
    tb_produtos.nome_prod_longo,
    tb_produtos.codigo_interno,
    tb_produtos.id_cat,
    tb_produtos.preco,
    tb_produtos.peso,
    tb_produtos.largura_centimetro,
    tb_produtos.altura_centimetro,
    tb_produtos.quantidade_estoque,
    tb_produtos.preco_promorcional,
    tb_produtos.foto_principal,
    tb_produtos.visivel
    LIMIT 4;
    ");

    foreach ($data as &$produto) {
        $preco = $produto['preco'];
        $centavos = explode(".", $preco);
        $produto['preco'] = number_format($preco, 0, ",", ".");
        $produto['centavos'] = end($centavos);
        $produto['parcelas'] = 10;
        $produto['parcela'] = number_format($preco/$produto['parcelas'], 2, ",", ".");
        $produto['total'] = number_format($preco, 2, ",", ".");
        $produto['nome_prod_longo'] = utf8_decode($produto['nome_prod_longo']);
    }
    
    

    echo json_encode($data);
});

$app->run();
