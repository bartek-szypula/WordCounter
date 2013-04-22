<?php

class PDOWordCounter extends PDOClass
{

	/**
	 * @param string $address Url strony
	 * @param string $word Wyszukiwana fraza
	 * @param integer $words_found Ilosc znalezionych fraz
	 * @param array $sentences Tablica stringow znalezionych zadan
	 */
	public function insertResults($address, $word, $caseSensitive, $words_found, $sentences)
	{
		$this->_insertWord($address, $word, $caseSensitive, $words_found);

		if( $sentences )
			$this->_insertLastWordSentences($sentences);
	}

	//----------------------------------------------------------------
	// Zwracanie wyników poprzednich wyszukiwań
	//----------------------------------------------------------------
	public function getResults()
	{
		$query = "SELECT words.id, words.address, words.word, words.caseSensitive, words.found, 
		GROUP_CONCAT(sentences.sentence SEPARATOR '--|--') as `sentences` 
		FROM words, sentences 
		WHERE words.id=sentences.word_id 
		GROUP By words.id
		ORDER BY words.id DESC";

		$stmt = $this->_conn->prepare($query);
		$stmt->setfetchMode(PDO::FETCH_ASSOC);
		$stmt->execute();

		return $stmt->fetchAll();
	}

	private function _insertWord($address, $word, $caseSensitive, $words_found)
	{
		$query = 'INSERT INTO words values(NULL, :address, :word, :caseSensitive, :found)';
		$stmt = $this->_conn->prepare($query);

		$stmt->bindParam(':address', $address, PDO::PARAM_STR);
		$stmt->bindParam(':word', $word, PDO::PARAM_STR);
		$stmt->bindParam(':caseSensitive', $caseSensitive, PDO::PARAM_INT);
		$stmt->bindParam(':found', $words_found, PDO::PARAM_INT);

		$stmt->execute();
	}

	private function _getLastWordId()
	{
		$query2 = 'SELECT * FROM words ORDER BY id DESC LIMIT 1';
		$stmt = $this->_conn->prepare($query2);
		$stmt->setfetchMode(PDO::FETCH_ASSOC);

		$stmt->execute();

		$results = $stmt->fetch();

		return $results['id'];
	}

	private function _insertLastWordSentences($sentences)
	{
		$id = $this->_getLastWordId();

		$query3 = "INSERT INTO sentences values(NULL, $id, :sentence)";
		$stmt = $this->_conn->prepare($query3);
		$stmt->bindParam(':sentence', $sentence, PDO::PARAM_STR);

		foreach( $sentences as $sentence )
		{
			$stmt->execute();
		}
	}
}