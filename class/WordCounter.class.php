<?php

class WordCounter
{
	private $_pageSource;
	private $_positions = array();
	private $_sentences = array();

	public function __construct($address)
	{
		$this->_pageSource = file_get_contents($address);
	}

	//----------------------------------------------------------------
	// Funkcja wyciągająca z kodu html jedynie tekst
	//----------------------------------------------------------------
	public function prepareSource()
	{
		// htmlentities
		// $this->_pageSource = htmlentities($this->_pageSource);

		// usuwanie białych znaków
		$this->_pageSource = trim($this->_pageSource);

		// usuwanie tekstu przed "<body>"
		$this->_pageSource = stristr($this->_pageSource, '<body');

		// usuwanie tekstu za "</body>"
		$this->_pageSource = stristr($this->_pageSource, '</html', true);

		// usuwanie JS
		$this->_pageSource = preg_replace('/<script(.*)<\/script>/eisU', '', $this->_pageSource);

		// usuwanie html-a
		// $this->_pageSource = preg_replace('/<\/?+[0-9a-zA-Z ="-_:;!ążśźćęńłóĄŻŚŹĆĘŃÓŁ]+>/', '', $this->_pageSource);
		$this->_pageSource = preg_replace('/<\/?(.*)>/eisU', '', $this->_pageSource);

		// usuwanie komentarzy
		$this->_pageSource = preg_replace('/<!--+[0-9a-zA-Z ="-:;_]+-->/', '', $this->_pageSource);
		$this->_pageSource = preg_replace('/\/\*(.*)\*\//eisU', '', $this->_pageSource);
	}

	//----------------------------------------------------------------
	// Funkcja zwracająca ilość wyszukiwanych słów znalezionych w całym tekście strony
	//----------------------------------------------------------------
	public function wordCount($word, $cs = TRUE) // $cs - caseSensitive
	{
		$functionPos = 'strpos';
		$counter = 0;
		$position = -1;

		if( !$cs )
			$functionPos = 'stripos';

		while( ($position = $functionPos($this->_pageSource, $word, ++$position)) !== FALSE )
		{
			if( $this->_isWord($position, $word) )
			{
				$counter++;
				$this->_positions[] = $position;
			}
		}

		return $counter;
	}

	//----------------------------------------------------------------
	// Funkcja sprawdzająca, czy znaleziona fraza jest zdaniem, czy np. częścią wyrazu
	//----------------------------------------------------------------
	private function _isWord($position, $word)
	{
		$charBefore = $this->pluckChar($this->_pageSource, $position-1);
		$charAfter 	= $this->pluckChar($this->_pageSource, $position-1);

		return 	( 
					(	$charBefore == ' ' ||
						$charBefore == ':' ||
						$charBefore == '(' ||
						$charBefore == '{' ||
						$charBefore == '[' ||
						$charBefore == '"' ||
						$charBefore == '\''

					)
					&& 
					( 	$charAfter == ' ' ||
						$charAfter == ',' ||
						$charAfter == '.' ||
						$charAfter == ':' ||
						$charAfter == '!' ||
						$charAfter == ')' ||
						$charAfter == '}' ||
						$charAfter == ']' ||
						$charAfter == '"' ||
						$charAfter == '\'' ||
						$charAfter == '%'
					)
				);
	}

	//----------------------------------------------------------------
	// Funkcja zwracająca zdanie, w którym znajdowała się szukana fraza
	//----------------------------------------------------------------
	public function getSentences()
	{
		foreach($this->_positions as $pointerPos)
		{
			$to_options = array();

			// pozycja konca zdania
			$to_options['period'] = strpos($this->_pageSource, '. ', $pointerPos);
			$to_options['questionMark'] = strpos($this->_pageSource, '? ', $pointerPos);
			$to_options['exclamationMark'] = strpos($this->_pageSource, '! ', $pointerPos);
			$to_options = array_filter($to_options);

			if( !empty($to_options) ) $to = min($to_options); else break;

			$rpos = -(strlen($this->_pageSource) - $pointerPos);
			// pozycja począrku zdania
			$from = strrpos($this->_pageSource, '.', $rpos);

			// długość zdania
			$length = $to - $from;

			$this->_sentences[] = substr($this->_pageSource, $from + 1, $length);
		}

		return $this->_sentences;
	}

	//----------------------------------------------------------------
	// Funkcja wyciągająca pojedynczą literę ze stringa
	//----------------------------------------------------------------
	static function pluckChar($string, $position)
	{
		return substr($string, $position, 1);
	}

	// zwraca kod strony z zachowaniem oryginalnego formadowania
	public function showSource() {
		echo '<pre>' . htmlspecialchars($this->_pageSource) . '</pre>';
	}

	// zwraca czysty tekst z zrodla
	public function getSourceText() {
		return $this->_pageSource;
	}
}