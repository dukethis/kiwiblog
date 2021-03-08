<?php
	// PHP WEBLOG GENERATOR
	// CATCH AN ITEM ......................... author-this-is-me-publication
	// RENDERS THE MARKDOWN SOURCE FILE ...... author-this-is-me-publication.md
	
	$item = isset($_GET["title"]) ? $_GET["title"] : null;
	
	// NICE TRY
	if (!$item) { header('Location: /'); exit; }
	
	// SPLIT AUTHOR/TITLE FROM ITEM
	$title  = strtr($item," ","-");
	$title  = strtr($title,"+","-");
	$author = explode('-',$title)[0];
	$title  = str_replace($author.'-','',$title);

	// NO MARKDOWN SOURCE FILE?
	if ( !file_exists( $author.'-'.$title.'.md' )) { header('Location: /'); exit; }

	// JSON DATA TREE LOADING
	$fn = "data/data.json";
	$fd = fopen( $fn,"r");
	$fc = fread( $fd, filesize($fn) );
	fclose($fd);
	$items = json_decode($fc);
	
	// UNIQUE KEY IDENTIFIER: AUTHOR-TITLE
	$key = base64_encode($author.'-'.$title);
	// DIRECT CATCH USING THE HOME-MADE INDEX
	$item = $items->$key;
	// CONTENT METADATA AFFECTATION
	$author = $item->author;
	$pubdate = $item->pubdate;
	$tags = implode(', ', $item->tags);
	$htmltags = '<text>'.implode('</text><text>', $item->tags).'</text>';
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8"/>
		<meta name="author" content="<?php echo $author; ?>"/>
		<meta name="pubdate" content="<?php echo $pubdate; ?>"/>
		<meta name="keywords" content="weblog, blog, publication<?php echo $tags ? ', '.$tags : null; ?>"/>
		<meta name="viewport" content="width=device-width,initial-scale=1"/>
		<link rel="stylesheet" type="text/css" href="static/blog.css"/>
		<title>openblog/<?php echo $title; ?></title>
	</head>
	<body>
		<section class="main">
			<section class="signature">
				<p><?php echo Date('l jS \of F, Y',strtotime($pubdate)); ?></p>
				<p><label>by&nbsp;</label><text><?php echo $author; ?></text></p>
			</section>
			<?php
				// CREDITS TO MICHEL FORTIN FOR THE MARKDOWN-HTML TRANSCRIPT
				// SOURCE: https://github.com/michelf/php-markdown
				include "lib/Michelf-markdown.php";
				use Michelf\MarkdownExtra;
				// TARGET FILE TO RENDER
				$fn = $author."-".$title.".md";
				$fd = fopen($fn,"r");
				// MD CONTENT
				$my_text = fread($fd, filesize($fn));
				fclose($fd);
				// TRANSCRIPT TO HTML
				$my_html = MarkdownExtra::defaultTransform($my_text);
				echo $my_html;
			?>
		</section>
		<hr/>
		<section class="foot">
			<p><?php echo $pubdate; ?></p>
			<p><?php echo $htmltags; ?></p>
		</section>
		<section class="link">
			<a href="/" title="Home page"><img src="static/icon_home.svg" width="30"/></a>
			<a href="#top" title="Top page"><img src="static/icon_up_arrow.svg" width="30"/></a>
		</section>
	</body>
</html>