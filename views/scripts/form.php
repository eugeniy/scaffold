<?php if (isset($this->error)) echo "<div>{$this->error}</div>"; ?>
 
<form action="?action=<?php echo $this->escape($this->action); ?>" method="post">
<ul>
 
<?php foreach ($this->fields as $key => $field): ?>

<?php if ($key == $this->primary && empty($this->data[$key])) continue; ?>

<li><label for="<?php echo $key; ?>"><?php echo empty($field['label']) ? $key : $field['label']; ?>: </label><input type="text" name="<?php echo $key; ?>" id="<?php echo $key; ?>" value="<?php echo $this->escape($this->data[$key]); ?>" /></li>

<?php endforeach; ?>
 
<li><input type="submit" value="Save" /></li>
 
</ul>
</form>