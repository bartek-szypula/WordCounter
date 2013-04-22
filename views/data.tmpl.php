<?php include '_partials/header.php';

if( empty($results) ) : ?>

	<h1>Brak pozycji do wyświetlenia</h1>

<?php else : ?>

	<?php foreach($results as $row) : ?>
		<h2 class="data">Wyszukiwana fraza: <?= $row['word'] ?></h2>
		<p>Adres strony: <?= $row['address'] ?></p>
		<p>Uwzględnij wielkość liter: <?php echo ($row['caseSensitive']) ? 'tak' : 'nie' ?></p>
		<p>Ilość znalezionych: <?= $row['found'] ?></p>

		<?php $sentences = explode('--|--', $row['sentences']) ?>
		
		<ul>
			<?php foreach( $sentences as $sentence ) : ?>
				<li><?= $sentence ?></li>
			<?php endforeach; ?>
		</ul>
	<?php endforeach;

endif; ?>

<?php include '_partials/footer.php'; ?>