<?php if ($this->columns): ?>

	<table>
		<tr>
			<?php foreach ($this->columns as $key => $val): ?>
			<th><?php echo $this->escape($val['COLUMN_NAME']) ?></th>
			<?php endforeach; ?>
		</tr>

		<?php foreach ($this->rows as $row): ?>
		<tr>
			<?php foreach ($row as $value): ?>
			<td><?php echo $this->escape($value) ?></td>
			<?php endforeach; ?>
		</tr>
		<?php endforeach; ?>

	</table>

<?php else: ?>

	<p>There are no columns to display.</p>

<?php endif; ?>