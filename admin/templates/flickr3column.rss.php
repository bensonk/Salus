<? 
/*
Three Column Flickr Gallery Layout for Salus

Grabs a Flickr RSS feed and outputs a gallery compatible with Lightbox or Thickbox

Required CSS (adjust to taste):

ul.flickrGallery, ul.flickrGallery ul{
	list-style-type: none;
}

ul.flickrGallery ul li{
	display: inline;
	padding: .4em;
}
*/

function parseFlickrImage($item)
{

	/* this will be added to each image's link
	for lightbox: rel="lightbox"
	for thickbox: class="thickbox"
	to open the links in a new window: target="_blank"
	*/
	$imageLinkAddition = 'class="thickbox"';

	$image = $item->get_description();
    $image = substr($image,strpos($image,'<img')+13);
    $image = trim(substr($image,0,strpos($image,"\" width")));  // pulls the url of the picture out of the description area and ignores everything else
    $enc = array("%3A", "%2F");
    $unenc  = array(":", "/");
    $image = str_replace($enc, $unenc, $image);  // puts the "colon" and "slashes" back in
    $imagetn = str_replace("m.jpg", "s.jpg", $image);  // makes the medium size pic into the square one for thumbnail
    $image = str_replace("_m", "", $image);  //makes the images a nice size for viewing on one's computer
    echo '<li>' . "\n" . '<a href="htt' . $image. '" ' . $imageLinkAddition . ' title="' . $item->get_title() . '"><img src="htt' . $imagetn . '" alt="' . $item->get_title() . '" /></a>'."\n".'</li>' ."\n";
}

if ($feed->data) 
{
	echo '<ul class="flickrGallery">';
    
	foreach ($feed->get_items(0, $num) as $item) 
	{
		$counter++;
		
		if($counter == 1)
		{
			echo "<li>\n<ul>\n";
			parseFlickrImage($item);
		}
		
		if($counter == 2)
			parseFlickrImage($item);
		
		if($counter == 3)
		{
			parseFlickrImage($item);
			echo "</ul>\n</li>\n";
			$counter = 0;
		}
    }
	
	echo '</ul>';

}
?>