<?
	if ($feed->data)
	{
		$max = $feed->get_item_quantity($num);
		for ($x = 0; $x < $max; $x++) 
		{
			$item = $feed->get_item($x);
?>
	<div>
        <h3>
                <?php echo $item->get_title(); ?>
        </h3>

        <div><?php echo $item->get_date('j M Y'); ?></div>

        <p><?php echo $item->get_description(); ?></p>
	</div>
<? }} ?>