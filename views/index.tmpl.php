<?php include '_partials/header.php'; ?>

<h2>Wyszukiwanie słów na stronie WWW</h2>
<form action="index.php" method="post">
	<fieldset>
		<legend>Wypełnij pola</legend>

		<?php foreach( $notices as $notice ) : ?>
			<p class="notice"><?= $notice ?></p>
		<?php endforeach; ?>
		
		<ul>
			<li>
				<label for="address">Wpisz adres strony:</label>
				<input class="text" type="text" name="address" id="address" value="<?= old('address', 'http://'); ?>" />
			</li>
			<li>
				<label for="word">Szukany wyraz:</label>				
				<input class="text" type="text" name="word" id="word" value="<?= old('word') ?>" />
			</li>
			<li>
				<label for="caseSensitive">Uwzględnij wielkość liter</label>
				<input type="checkbox" name="caseSensitive" id="caseSensitive" <?php if( old('caseSensitive') == 'on' ) echo 'checked'; ?>/>
			</li>
			<li>
				<label for="getSentences">Szukaj zdań zawierających podany wyraz</label>
				<input type="checkbox" name="getSentences" id="getSentences" <?php if( old('getSentences') == 'on' ) echo 'checked'; ?> />
			</li>
			<li>
				<label for="getSource">Wyświetl źródło strony</label>
				<input type="checkbox" name="getSource" id="getSource" <?php if( old('getSource') == 'on' ) echo 'checked'; ?> />
			</li>
			<li>
				<label for="saveDB">Zapisz wyniki do bazy</label>
				<input type="checkbox" name="saveDB" id="saveDB" <?php if( old('saveDB') == 'on' ) echo 'checked'; ?> />
			</li>
			<li>
				<button type="submit">Wyślij</button>
			</li>
		</ul>	
	</fieldset>
</form>

<div id="db">
	<h2>Zapisane wyniki</h2>
	<ul>
		<li><a href="data.php" target="_blank">
				<button>Przeglądaj wyniki z bazy</button>
			</a>
		</li>
	</ul>
</div>

<?php if( isset($words_found) ) : ?>
<div id="results">
	<p>
		<h3>Ilość znalezionych słów</h3>
		<?= $words_found; ?>
	</p>

	<p>
		<?php if( isset($sentences) && !empty($sentences) ) : ?>
			<h3>Zdania, w których występowało słowo</h3>
			<ul>
				<?php foreach( $sentences as $sentence ) : ?>
					<li><?= $sentence ?></li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>
	</p>

	<p>
		<?php if( isset($source_text) ) : ?>
			<h3>Zawartość źródła strony</h3>
			<?= $source_text; ?>
		<?php endif; ?>
	</p>
</div>
<?php endif; ?>

<?php include '_partials/footer.php'; ?>