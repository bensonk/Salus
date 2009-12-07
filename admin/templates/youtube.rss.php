<?/*
if ($feed->data)
{
 
    $max = $feed->get_item_quantity($num);
    
	for ($x = 0; $x < $max; $x++)
	{
        $item = $feed->get_item($x);
		
		$enclosure = $item->get_enclosure(0);
		
		if ($enclosure)
		{
        echo $enclosure->embed('');
		}
    }
}*/
// Create an instance of SimplePie

$item = $feed->get_item(0);
$enclosure = $item->get_enclosure(0);
echo $enclosure->embed('');
?>