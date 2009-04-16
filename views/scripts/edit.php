<?php if (isset($error)) echo "<div>{$error}</div>"; ?>
 
<form action="<?php //echo url::site('/scaffold/products/update'); ?>" method="post">
<ul>
 
<?php foreach ($this->fields as $key => $field): ?>

<li><label for="<?php echo $key; ?>"><?php echo empty($field['label']) ? $key : $field['label']; ?>: </label><input type="text" name="<?php echo $key; ?>" id="<?php echo $key; ?>" value="<?php echo $this->escape($this->data[$key]); ?>" /></li>

<?php endforeach; ?>
 
<li><input type="submit" value="Save" /></li>
 
</ul>
</form>