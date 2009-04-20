<div class="scaffold">
 
<table cellpadding="0" cellspacing="0" border="0">
 
<caption><span class="actions"><a href="<?php echo Scaffold::Url(array('action'=>'add')); ?>">Add</a></span><?php echo $this->title; ?></caption>
 
<thead><tr>
 
<?php foreach ($this->fields as $key => $value): ?>
<th><?php
	if (isset($this->sortable[$key])) echo '<a href="'.Scaffold::Url(array('sort'=>$this->sortable[$key]), true).'">';
	echo empty($value['label']) ? $key : $value['label'];
	if (isset($this->sortable[$key])) echo "</a>";
?></th>
<?php endforeach; ?>
 
<th colspan="2">Actions</th>
 
</tr></thead>
<tbody>
 
<?php foreach ($this->rows as $row): ?>
<tr>
<?php foreach ($row as $field): ?>
<td><?php echo $field; ?></td>
<?php endforeach; ?>
 
<td><a href="<?php echo Scaffold::Url(array('action'=>'edit', $this->primary=>$row[$this->primary])); ?>">Edit</a></td>
<td><a href="<?php echo Scaffold::Url(array('action'=>'delete', $this->primary=>$row[$this->primary])); ?>">Delete</a></td>
 
</tr>
<?php endforeach; ?>
 
</tbody>
 
</table>
 
<?php echo $this->pagination; ?>
 
</div>