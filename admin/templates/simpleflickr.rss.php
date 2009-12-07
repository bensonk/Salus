<? 

if ($feed->data) 
{
    foreach ($feed->get_items(0, $num) as $item) 
	{
        $image = $item->get_description();
        $image = substr($image,strpos($image,'<img')+13);
        $image = trim(substr($image,0,strpos($image,"\" width")));  // pulls the url of the picture out of the description area and ignores everything else
        $healthy = array("%3A", "%2F");
        $yummy  = array(":", "/");
        $image = str_replace($healthy, $yummy, $image);  // puts the "colon" and "slashes" back in
        $imagetn = str_replace("m.jpg", "s.jpg", $image);  // makes the medium size pic into the square one for thumbnail
        $image = str_replace("_m", "", $image);  //makes the images a nice size for viewing on one's computer
        echo "<p><a href=\"htt".$image."\" rel=\"lightbox\" title=\"".$item->get_title()."\"><img src=\"htt".$imagetn."\" alt=\"".$item->get_title()."\" /></a></p>\r\n";
    }
}

?>