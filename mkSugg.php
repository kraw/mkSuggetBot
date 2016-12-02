<?php
echo "###################################################\n";
echo "###             mkSuggestBot                    ###\n";
echo "###    Sviluppato da Michael di Pietro          ###\n";
echo "###    email: dpmika@hotmail.it                 ###\n";
echo "###    https://www.facebook.com/dpmika          ###\n";
echo "###################################################\n\n";


echo "Inserisci le parole chiavi:   ";
$keywords = trim(fgets(STDIN));
$keywords = htmlentities($keywords);
$nomeFile = str_ireplace(" ", "-", $keywords);
$file = fopen('txt/'.$nomeFile.".txt","a");

$keywords = suggests($keywords);
$elenco = recursive($keywords, $file);

fwrite($file, $elenco);

function recursive($keywords){
    if(!isset($testo)) {
      $testo = "";
    }
    if(count($keywords)>1) {
      foreach($keywords as $keyword) {
        $current = suggests($keyword);
        echo "###### Keywords: ".$keyword." ##################\n";
        foreach($current as $single){
          echo $single."\n";
          $testo.=$single."\n";
          recursive($single);
        }
        echo "\n\n";
      }
    } else {
    }
    return $testo;
}


function suggests($keywords) {

  $keywords = urlencode($keywords);
  $url = "http://suggestqueries.google.com/complete/search?output=toolbar&hl=it&q=".$keywords;
  $file = file_get_contents($url);
  $xml = simplexml_load_string($file, "SimpleXMLElement", LIBXML_NOCDATA);
  $json = json_encode($xml);
  $array = json_decode($json,TRUE);
  $result = array();
  if(isset($array['CompleteSuggestion'])) {
    foreach($array['CompleteSuggestion'] as $suggest) {
      if(array_key_exists('@attributes', $suggest)) {
        array_push($result, $suggest["@attributes"]["data"]);
      } else {
      array_push($result, $suggest['suggestion']['@attributes']['data']);
      }
    }
  } else {
    echo "Nessun risultato trovato.";
  }
  sleep(3);
  return $result;
}


?>
