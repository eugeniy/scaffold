<div class="scaffold">
 
<table cellpadding="0" cellspacing="0" border="0">
 
<caption><span class="actions"><a href="<?php echo Scaffold::Url(array('action'=>'add')); ?>">Add</a></span><?php echo $this->title; ?></caption>
 
<thead><tr>
 
<?php foreach ($this->fields as $key => $value): ?>
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

	if (isset($this->fields[$key]['parent'])) echo '<a href="'.Scaffold::Url(array('table'=>$this->fields[$key]['parent'], 'action'=>'edit', 'id'=>$field))."\">{$field}</a>";
	else echo $field;

?></td>
<?php endforeach; ?>
 
<td><a href="<?php echo Scaffold::Url(array('action'=>'edit', 'id'=>$row[$this->primary])); ?>">Edit</a></td>
<td><a href="<?php echo Scaffold::Url(array('action'=>'delete', 'id'=>$row[$this->primary])); ?>">Delete</a></td>
 
</tr>
<?php endforeach; ?>
 
</tbody>
 
</table>
 
<?php echo $this->pagination; ?>
 
</div>