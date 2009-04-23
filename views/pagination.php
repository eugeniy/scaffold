<p class="pagination">

	<?php if ($previous): ?>
		<a href="<?php echo Scaffold::Url('page', $previous) ?>">&laquo;&nbsp;previous</a>
	<?php else: ?>
		&laquo;&nbsp;previous
	<?php endif ?>


	<?php if ($pageCount < 13): /* « Previous  1 2 3 4 5 6 7 8 9 10 11 12  Next » */ ?>

		<?php for ($i = 1; $i <= $pageCount; $i++): ?>
			<?php if ($i == $page): ?>
				<strong><?php echo $i ?></strong>
			<?php else: ?>
				<a href="<?php echo str_replace('{page}', $i, $url) ?>"><?php echo $i ?></a>
			<?php endif ?>
		<?php endfor ?>

	<?php elseif ($page < 9): /* « Previous  1 2 3 4 5 6 7 8 9 10 … 25 26  Next » */ ?>

		<?php for ($i = 1; $i <= 10; $i++): ?>
			<?php if ($i == $page): ?>
				<strong><?php echo $i ?></strong>
			<?php else: ?>
				<a href="<?php echo str_replace('{page}', $i, $url) ?>"><?php echo $i ?></a>
			<?php endif ?>
		<?php endfor ?>

		&hellip;
		<a href="<?php echo str_replace('{page}', $pageCount - 1, $url) ?>"><?php echo $pageCount - 1 ?></a>
		<a href="<?php echo str_replace('{page}', $pageCount, $url) ?>"><?php echo $pageCount ?></a>

	<?php elseif ($page > $pageCount - 8): /* « Previous  1 2 … 17 18 19 20 21 22 23 24 25 26  Next » */ ?>

		<a href="<?php echo str_replace('{page}', 1, $url) ?>">1</a>
		<a href="<?php echo str_replace('{page}', 2, $url) ?>">2</a>
		&hellip;

		<?php for ($i = $pageCount - 9; $i <= $pageCount; $i++): ?>
			<?php if ($i == $page): ?>
				<strong><?php echo $i ?></strong>
			<?php else: ?>
				<a href="<?php echo str_replace('{page}', $i, $url) ?>"><?php echo $i ?></a>
			<?php endif ?>
		<?php endfor ?>

	<?php else: /* « Previous  1 2 … 5 6 7 8 9 10 11 12 13 14 … 25 26  Next » */ ?>

		<a href="<?php echo str_replace('{page}', 1, $url) ?>">1</a>
		<a href="<?php echo str_replace('{page}', 2, $url) ?>">2</a>
		&hellip;

		<?php for ($i = $page - 5; $i <= $page + 5; $i++): ?>
			<?php if ($i == $page): ?>
				<strong><?php echo $i ?></strong>
			<?php else: ?>
				<a href="<?php echo str_replace('{page}', $i, $url) ?>"><?php echo $i ?></a>
			<?php endif ?>
		<?php endfor ?>

		&hellip;
		<a href="<?php echo str_replace('{page}', $pageCount - 1, $url) ?>"><?php echo $pageCount - 1 ?></a>
		<a href="<?php echo str_replace('{page}', $pageCount, $url) ?>"><?php echo $pageCount ?></a>

	<?php endif ?>


	<?php if ($next): ?>
		<a href="<?php echo str_replace('{page}', $next, $url) ?>">next&nbsp;&raquo;</a>
	<?php else: ?>
		next&nbsp;&raquo;
	<?php endif ?>

</p>