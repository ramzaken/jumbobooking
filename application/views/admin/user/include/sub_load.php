<?php if (empty($subcategories)): ?>
	<option  value="0"><?php echo trans('no-data-found') ?></option>
<?php 	else: ?>
	<option value=""><?php echo trans('select') ?></option>
	<?php foreach ($subcategories as $value): ?>
		<option  value="<?php 	echo html_escape($value->id) ?>"><?php echo html_escape($value->name) ?></option>
	<?php endforeach ?>
<?php endif; ?>