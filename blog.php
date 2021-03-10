<?php
	// PHP WEBLOG GENERATOR
	// CATCH AN ITEM ......................... author-this-is-me-publication
	// RENDERS THE MARKDOWN SOURCE FILE ...... author-this-is-me-publication.md
	// THIS ONLY WORKS WITH SPECIFIC PARAMETERS
	if( count($_GET) == 0) { header('Location: /'); exit; }
	// GET SPECIFIC 'title' PARAMETER
	$input = isset($_GET["title"]) ? $_GET["title"] : null;
	// NICE TRY
	if (!$input) { header('Location: /'); exit; }
	// NO MARKDOWN SOURCE FILE?
	if ( !file_exists( $input.'.md' )) { header('Location: /'); exit; }
	// JSON DATA TREE LOADING
	$fn = "data/data.json";
	$fd = fopen( $fn,"r");
	$fc = fread( $fd, filesize($fn) );
	fclose($fd);
	$items = json_decode($fc);
	// UNIQUE KEY IDENTIFIER: AUTHOR-TITLE
	$key = base64_encode($input);
	// DIRECT CATCH USING THE HOME-MADE INDEX
	$item = $items->$key;
	// CONTENT METADATA AFFECTATION
	$title   = $item->title;
	$author  = $item->author;
	$pubdate = $item->pubdate;
	$tags    = implode(', ', $item->tags);
	$htmltags = '<text>'.implode('</text><text>', $item->tags).'</text>';
?>
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
			<section class="index" onclick="do_index()">
				<p id="index-title">&#8681;&nbsp;index</p>
				<p id="index-items"></p>
			</section>
			<section class="content" id="main">
			<?php
				// CREDITS TO MICHEL FORTIN FOR THE MARKDOWN-HTML TRANSCRIPT
				// SOURCE: https://github.com/michelf/php-markdown
				include "lib/Michelf-markdown.php";
				use Michelf\MarkdownExtra	;
				// TARGET FILE TO RENDER
				$fn = $input.".md";
				$fd = fopen($fn,"r");
				// MD CONTENT
				$mdtext = fread($fd, filesize($fn));
				fclose($fd);
				// TRANSCRIPT TO HTML
				$my_html = MarkdownExtra::defaultTransform($mdtext);			
				echo $my_html;
			?>

			</section>
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
		<script>
			function do_index() {
				var from_index = document.getElementById('main')
				var to_index   = document.getElementById("index-items")
				if ( to_index.innerText != "" ) { to_index.innerText = ""; return; }
				to_index.innerText = "";
				var titles = from_index.children
				for (var i=0; i<titles.length; i++) {
					var style = ""
					switch ( titles[i].outerHTML.substring(1,3) ) {
						case "h1":
							style = "margin-left:30px;text-transform:uppercase";
							to_index.innerHTML += "<p style='"+style+"'><a href='#"+titles[i].id+"'>"+titles[i].innerHTML+"</a></p>"
						break;
						case "h2":
							style = "margin-left:50px;";
							to_index.innerHTML += "<p style='"+style+"'><a href='#"+titles[i].id+"'>"+titles[i].innerHTML+"</a></p>"
						break;
						case "h3":
							style = "margin-left:70px;";
							to_index.innerHTML += "<p style='"+style+"'><a href='#"+titles[i].id+"'>"+titles[i].innerHTML+"</a></p>"
						break;
					}
				}
			}
		</script>
	</body>
</html>
