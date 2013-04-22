<?php

require 'functions.php';

	try {
		$pdo = new PDOWordCounter();

		//----------------------------------------------------------------
		// Zwracanie wyników poprzednich wyszukiwań
		//----------------------------------------------------------------
		$results = $pdo->getResults();

	} catch (PDOException $e) {
		echo 'ERROR: ' . $e->getMessage();

		if(isset($conn)):
			echo '<p>errorCode: ' . $conn->errorCode() . '</p>';
			pp($conn->errorInfo(), 'errorInfo(): ');
		endif;
	}

include 'views/data.tmpl.php';