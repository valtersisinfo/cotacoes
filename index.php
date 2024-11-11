<?php
// API das Cotações https://docs.awesomeapi.com.br/

// Aumenta o tempo máximo de execução do script
set_time_limit(300); // 300 segundos = 5 minutos

// URL da API
$url = "https://economia.awesomeapi.com.br/xml/available";

// Inicializa uma sessão cURL
$ch = curl_init();

// Define a URL e outras opções apropriadas
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Executa a sessão cURL e armazena a resposta em uma variável
$response = curl_exec($ch);

// Fecha a sessão cURL
curl_close($ch);

// Verifica se a resposta não é falsa (indicando um erro)
if ($response !== false) {

  // Carrega o XML na variável $xml usando SimpleXML
  $xml = simplexml_load_string($response);

  // Converte o SimpleXML em um objeto JSON e depois em um objeto PHP
  $json = json_encode($xml);
  $data = json_decode($json, true);

  // Construo a STRING para obter todos as cotações
  $moedas = "";
  foreach ($data as $key => $value) {
    if ($moedas != "") $moedas .= ",";
    $moedas .= $key;
  }
  // URL da API
  $url = "https://economia.awesomeapi.com.br/json/last/" . $moedas;
  // Inicializa uma sessão cURL
  $ch = curl_init();
  // Define a URL e outras opções apropriadas
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  // Executa a sessão cURL e armazena a resposta em uma variável
  $response = curl_exec($ch);
  // Verifica se a resposta não é falsa (indicando um erro)
  if ($response !== false) {

    // Coloco a resposta no arrayCotacao
    $aCotacao = json_decode($response);
    // Foreach para exibir o resultado
    foreach ($aCotacao as $cotacao) {
      echo "Cotação " . $cotacao->name . " (" . $cotacao->code . "/" . $cotacao->codein . ")<br>Compra: " . $cotacao->bid . " Venda: " . $cotacao->ask . "<br><br>";
    }
  } else {
    echo "Erro ao acessar a API 2.";
  }
} else {
  echo "Erro ao acessar a API 1.";
}