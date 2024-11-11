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

  // Exibe os dados em um objeto
  foreach ($data as $key => $value) {

    // URL da API
    $url = "https://economia.awesomeapi.com.br/json/last/" . $key;

    // Inicializa uma sessão cURL
    $ch = curl_init();

    try {
      // Define a URL e outras opções apropriadas
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_TIMEOUT, 30); // Tempo limite de 30 segundos

      // Executa a sessão cURL e armazena a resposta em uma variável
      $response = curl_exec($ch);

      if (curl_errno($ch)) {
        throw new Exception(curl_error($ch));
      } else {

        // Suponha que você tenha um objeto com um único atributo
        $obj = json_decode($response);

        // Obtém os atributos do objeto como um array associativo
        $attributes = get_object_vars($obj);

        // Obtém o nome do único atributo
        $attributeName = key($attributes);

        // Obtém o valor do único atributo
        $attributeValue = $attributes[$attributeName];

        echo "Cotação " . $value . " (" . $key . ")<br>Compra: " . $attributeValue->bid . " Venda: " . $attributeValue->ask . "<br><br>";
      }
    } catch (Exception $e) {
      echo "Erro na cotação " . $value . " (" . $key . ")<br><br>";
    }

    // Fecha a sessão cURL
    curl_close($ch);
}

} else {
  echo "Erro ao acessar a API.";
}
?>
