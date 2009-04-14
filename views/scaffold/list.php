<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $title; ?></title>
<style type="text/css">
.scaffold {
font-family:Arial,Helvetica,sans-serif;
font-size:80%;
background:#fff;
color:#000;
}

.scaffold caption {
font-size:140%;
font-weight:normal;
margin:0;padding:0;
background:#5ae ;
color:#fff;
text-align:left;
padding:2px 10px;
}

.scaffold .actions {
float:right;
}
.scaffold .actions a {
color:#fff;
text-decoration:none;
}

.scaffold table {
border-collapse:separate;
border-spacing:1px;
background:#eee;
}



.scaffold th, .scaffold td {
padding:2px 10px;
background:#fff;
}

.scaffold th {
text-transform:uppercase;
font-size:90%;
color:#666;
padding:5px 10px;
}

.scaffold td a {
text-decoration:none;
font-size:90%;
}

</style>
</head>
<body>

<div class="scaffold">

<table cellpadding="0" cellspacing="0" border="0">

<caption><span class="actions"><a href="<?php echo url::site("scaffold/{$table}/add"); ?>">Add</a></span><?php echo $title; ?></caption>

<thead><tr>

<?php foreach ($fields as $key => $value): ?>
<th><?php if (isset($sortable[$key])) echo "<a href=\"?sort={$sortable[$key]}\">"; ?><?php echo empty($value['label']) ? $key : $value['label']; ?><?php if (isset($sortable[$key])) echo "</a>"; ?></th>
<?php endforeach; ?>

<th colspan="2">Actions</th>

</tr></thead>
<tbody>

<?php foreach ($rows as $row): ?>
<tr>
<?php foreach ($row as $field): ?>
<td><?php echo $field; ?></td>
<?php endforeach; ?>

<td><a href="<?php echo url::site("scaffold/{$table}/edit/{$row[$primary]}"); ?>">Edit</a></td>
<td><a href="<?php echo url::site("scaffold/{$table}/delete/{$row[$primary]}"); ?>">Delete</a></td>

</tr>
<?php endforeach; ?>

</tbody>

</table>

<?php echo $pagination; ?>

</div>

</body>
</html>