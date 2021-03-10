<?php

	// GLOBAL REDIRECTION FROM ROOT TO PUBLICATIONS
	
	// GET SPECIFIC 'title' PARAMETER
	$input = isset($_GET["title"]) ? $_GET["title"] : null;
	$input = substr($input,1,4)==="test" ? $input : 'test-'.$input;
	if ( file_exists( $input.'.md' )) { header('Location: /blog?title='.$input); exit; }
	// GET GLOBAL CATCH
	if (count($_GET)>0) {
		$item = array_keys($_GET)[0];
		if (preg_match('/\..+/', $item)) { $item = explode('_',$item); array_pop($item); $item=implode('',$item); }
		$item = substr($item,1,4)==="test" ? $item : 'test-'.$item;
		header('Location: /blog?title='.$item);
		exit;
	}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>openblog</title>
        <link rel="stylesheet" type="text/css" href="static/blog.css"/>
    </head>
    <body>
        <section class="main">
        <a class="index" href="javascript:switch_sort()"><h1 id="index"><text id="prefix">open</text><text>BLOG</text></h1></a>
        <?php
	// JSON DATA TREE LOADING
	$fn = "data/data.json";
	$fd = fopen( $fn,"r");
	$fc = fread( $fd, filesize($fn) );
	fclose($fd);
	$items = json_decode($fc);
	// FOR EACH ITEM (THIS IS AN INTERNAL SYNCHRO BETWEEN JSON DATA AND EXISTING MARKDOWN FILES ON SERVER: THEY HAVE TO MATCH)
        foreach ($items as $item) {
		$title = explode(' ',$item->title);
		$title = implode('-',$title);
		$author = $item->author;
		$file_path = $author.'-'.$title.'.md';
		if (!file_exists( $file_path )) { continue; }
		$date = $item->pubdate;
		echo '<h3 class="item"><a href="/blog.php?title='.$author.'-'.$title.'"><text id="date">'.$date.'</text>&nbsp;<text id="title">'.$author.'-'.$title.'</text></a></h3>';        }
        ?>
        </section>
	<section class="link">
                <a href="/feed"      title="RSS Feed"><img  src="static/icon_feed_rss.svg"  width="20"/></a>
                <a href="/feed?json" title="JSON Feed"><img src="static/icon_feed_json.svg" width="20"/></a>
                <a href="/new"       title="New Log"><img   src="static/icon_new_log.svg"   width="20"/>new log</a>
	</section>
        <script>
            function switch_sort() {
                    var xitem = document.getElementsByTagName("h3");
                    var items = [];
                    for (var i=xitem.length-1;i>-1;i--) { items.push( xitem[i] ) };
                    for (var i=0;i<xitem.length;i++) { xitem[i].outerHTML = items[i].outerHTML };
            }
        </script>
        </body>
</html>

