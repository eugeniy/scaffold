<div class="scaffold">
 
<table cellpadding="0" cellspacing="0" border="0">
 
<caption><span class="actions"><a href="<?php echo Scaffold::Url(array('action'=>'add'), true); ?>">Add</a></span><?php echo $title; ?></caption>
 
<thead><tr>
 
<?php foreach ($fields as $key => $value): ?>
<th><?php
	if ($value['sortable']) echo '<a href="'.Scaffold::Url(array('sort'=>$value['sort']), true).'">'.$value['label'].'</a>';
	else echo $value['label'];
?></th>
<?php endforeach; ?>
 
<th colspan="2">Actions</th>
 
</tr></thead>
<tbody>
 
<?php foreach ($this->rows as $row): ?>
<tr>
<?php foreach ($row as $key=>$field): ?>
<td><?php

	if (isset($parents[$key][$field]))
		echo '<a href="'.Scaffold::Url(array('table'=>$fields[$key]['parent'], 'action'=>'edit', 'id'=>$field))."\">{$parents[$key][$field]}</a>";
	else echo $field;

?></td>
<?php endforeach; ?>
 
<td><a href="<?php echo Scaffold::Url(array('action'=>'edit', 'id'=>$row[$primary])); ?>">Edit</a></td>
<td><a href="<?php echo Scaffold::Url(array('action'=>'delete', 'id'=>$row[$primary])); ?>">Delete</a></td>
 
</tr>
<?php endforeach; ?>
 
</tbody>
 
</table>
 
<?php echo $this->pagination; ?>
 
</div>