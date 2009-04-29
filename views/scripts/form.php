<form action="?action=save" method="post">
<ul>
 
<?php foreach ($fields as $key => $field): ?>

<li>
	<label for="<?php echo $key; ?>"><?php echo empty($field['label']) ? $key : $field['label']; ?>: </label>
	
	<?php

	switch ($this->fields[$key]['type'])
	{
		/*case 'password':
			echo $this->formPassword($key, $this->escape($this->data[$key]));
			break;

		case 'select':
			$options = isset($this->fields[$key]['options']) ? $this->fields[$key]['options'] : array();
			echo $this->formSelect($key, $this->escape($this->data[$key]), null, $options);
			break;

		case 'radio':
			$options = isset($this->fields[$key]['options']) ? $this->fields[$key]['options'] : array();
			echo $this->formRadio($key, $this->escape($this->data[$key]), null, $options);
			break;
		
		case 'checkbox':
			$options = isset($this->fields[$key]['options']) ? $this->fields[$key]['options'] : array();
			echo $this->formMultiCheckbox($key, $this->escape($this->data[$key]), null, $options);
			break;
		
		case 'hidden':
			echo $this->formHidden($key, $this->escape($this->data[$key]));
			break;
		
		case 'textarea':
		case 'auto_text':
			echo $this->formTextarea($key, $this->escape($this->data[$key]), array('cols'=>45,'rows'=>7));
			break;*/

		default:
			//echo $this->formText($key, $this->escape($this->data[$key]));
			echo '<input type="text" name="'.$key.'" id="'.$key.'" value="'.$this->Escape($data[$key]).'" />';
	}
	
	?>

</li>

<?php endforeach; ?>

<input type="text" name="bogus" value="bogus value" />

<li><input type="submit" value="Save" /></li>
 
</ul>
</form>