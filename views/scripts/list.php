<div class="scaffold">
 
<table cellpadding="0" cellspacing="0" border="0">
 
<caption><span class="actions"><a href="<?php /*echo url::site("scaffold/{$table}/add");*/ ?>">Add</a></span><?php echo $this->title; ?></caption>
 
<thead><tr>
 
<?php foreach ($this->fields as $key => $value): ?>
<th><?php if (isset($sortable[$key])) echo "<a href=\"?sort={$sortable[$key]}\">"; ?><?php echo empty($value['label']) ? $key : $value['label']; ?><?php if (isset($sortable[$key])) echo "</a>"; ?></th>
<?php endforeach; ?>
 
<th colspan="2">Actions</th>
 
</tr></thead>
<tbody>
 
<?php foreach ($this->rows as $row): ?>
<tr>
<?php foreach ($row as $field): ?>
<td><?php echo $field; ?></td>
<?php endforeach; ?>
 
<td><a href="?a=edit&amp;id=<?php echo $row[$this->primary]; ?>">Edit</a></td>
<td><a href="?a=delete&amp;id=<?php echo $row[$this->primary]; ?>">Delete</a></td>
 
</tr>
<?php endforeach; ?>
 
</tbody>
 
</table>
 
<?php echo $this->pagination; ?>
 
</div>