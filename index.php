<!DOCTYPE html>
<html>
    <head>
        <title>openblog</title>
        <link rel="stylesheet" type="text/css" href="static/blog.css"/>
    </head>
    <body>
        <section class="index">
        <h1><a class="index" href="javascript:switch_sort()"><text style="text-transform:uppercase;opacity:0.5">open</text>BLOG</h1>
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
		echo '<h3 class="item flex row"><a href="/weblog.php?title='.$author.'-'.$title.'"><text style="font-size:1rem;font-weight:700;margin-right:30px">'.$date.'</text><text>'.$author.'-'.$title.'</text></a></h3>';        }
        ?>
        </section>
	<section class="link">
                <a href="/feed" title="RSS Feed"><img src="static/icon_feed_rss.svg" width="20"/></a>
                <a href="/feed?json" title="JSON Feed"><img src="static/icon_feed_json.svg" width="20"/></a>
                <a href="/new" title="New Log" style="display: inline-flex;align-items: center;color:cornflowerblue"><img src="static/icon_new_log.svg" width="20"/>new log</a>
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
