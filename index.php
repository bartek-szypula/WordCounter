<?php

require 'functions.php';

// tablica z powiadomieniami o błędnym wypełnieniu pól
$notices = array();
// bool - "Uwzględnij wielkość liter"
$caseSensitive = false;
// bool - "Wyświetl zdania zawierające szukany wyraz"
$getSentences = false;
// bool - "Wyświetl zrodlo strony"
$getSource = false;

//----------------------------------------------------------------
// Walidacja formularza
//----------------------------------------------------------------
if( $_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['address']) && isset($_POST['word']) )
{
	$address = filter_var($_POST['address'], FILTER_VALIDATE_URL); // walidacja wpisanego w formunarzu url
	$word = $_POST['word'];
	$caseSensitive = isset($_POST['caseSensitive']) ? true : false;
	$getSentences = isset($_POST['getSentences']) ? true : false;
	$getSource = isset($_POST['getSource']) ? true : false;
	$saveDB = isset($_POST['saveDB']) ? true : false;

	if( $address === false )
		$notices[] = 'Błędny adres strony www';

	if( $word === '' )
		$notices[] = 'Wpisz szukane słowo';

	if( empty($notices) )
	{
		$wordCounter = new WordCounter($address);
		$wordCounter->prepareSource();

		$words_found = $wordCounter->wordCount($word, $caseSensitive);

		$sentences = ( $getSentences ) ? $wordCounter->getSentences() : false;

		if( $getSource )
			$source_text = $wordCounter->getSourceText();

		// wstawianie danych do bazy
		if( $saveDB )
		{
			try {
				$pdo = new PDOWordCounter();

				// wstawianie wyników do bazy
				$pdo->insertResults($address, $word, $caseSensitive, $words_found, $sentences);

			} catch (PDOException $e) {
				echo 'ERROR: ' . $e->getMessage();

				if(isset($conn)):
					echo '<p>errorCode: ' . $conn->errorCode() . '</p>';
					pp($conn->errorInfo(), 'errorInfo(): ');
				endif;
			}
		}
	}
}

include 'views/index.tmpl.php';