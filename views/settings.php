<?=form_open('C=addons_extensions'.AMP.'M=save_extension_settings'.AMP.'file=ce_kraken');?>

<p>This extension requires you to have <strong>CE Image</strong> installed. If not installed already, get it <a href="http://www.causingeffect.com/software/expressionengine/ce-image" target="_blank">here</a>.</p>
<p>This extension requires you to have a <strong>Kraken API account</strong>. If you don't have one already, you can sign up <a href="https://kraken.io/plans" target="_blank">here</a>.</p>

<?php

	if (isset($test_result))
	{

		echo "<p style='font-weight:bold;color:$test_result_color;'>$test_result</p>";

	}

	$this->table->set_template($cp_pad_table_template);

	$this->table->set_heading(
	    array('data' => lang('preference'), 'style' => 'width:50%;'),
	    lang('setting')
	);

	foreach ($settings as $key => $val)
	{

	    $this->table->add_row(lang($key, $key), $val);

	}

	echo $this->table->generate();

?>

<p><?=form_submit('submit', lang('submit'), 'class="submit"')?> <?=form_submit('submit_and_test', lang('submit_and_test'), 'class="submit"')?></p>

<?php $this->table->clear()?>

<?=form_close()?>

<p>This extension will only crush images that are being saved by CE Image. To reduce the size of previously CE sized images you will need to delete them from your "made" folder and re-visit the pages where they are rendered.</p>
<p>In some cases with heavy template caching CE Image may not be triggered to generate the sized iamges and you may see broken image links on the front end until the template cache is cleared.</p>

<?php
/* End of file index.php */
/* Location: ./system/expressionengine/third_party/ce_kraken/views/settings.php */