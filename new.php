<?php

// JSON DATA TREE
$fn = "data/data.json";
$fd = fopen( $fn,"r");
$fc = fread( $fd, filesize($fn) );
fclose($fd);
$items = json_decode($fc);

// GET CURRENT VALUES
$title   = isset($_GET["title"]) ? implode(' ',explode('-',$_GET["title"])) : null;
$author  = isset($_GET["author"]) ? $_GET["author"] : null;
$content = isset($_GET["content"]) ? $_GET["content"] : null;
$date    = isset($_GET["date"]) ? $_GET["date"] : null;
$date    = $date ? $date : (isset($_GET["pubdate"]) ? $_GET["pubdate"] : Date('Y-m-d'));
$tags    = isset($_GET["tags"]) ? explode(' ',$_GET["tags"]) : null;

// DEFAULT CONTENT (FILE HAS TO EXIST:)
$textform_content_fn = 'example.md';

// WHEN VARIABLES ARE SET UP: GOGO MY GENERATION
if ($title && $date && $author && $content) {
	// TITLE CAN BE PASSED WITH ' ' SEPARATOR OR '-' BY DEFAULT
	$str_title = implode('-',explode(' ', strtolower($title)));
	// TARGET
	$fn = $str_title.'.md';
	// TARGET EXISTS
	if (file_exists($fn)) {
		echo 'Existing file: '.$fn;
		//exit;
		//$textform_content_fn = $fn;
	}
	// MARKDOWN SOURCE FILE GENERATION
	$fd = fopen( $fn,"w");
	$fc = fwrite( $fd, $content );
	fclose($fd);
	// NEW ITEM CREATION
	$item = array(
		"title"   => $title,
		"author"  => $author,
		"pubdate" => $date,
		"tags"    => $tags
	);
	// JSON TREE UPDATE
	// UNIQUE KEY GENERATION
	$key = implode('-',explode(' ',$title));
	$key = base64_encode($key);
	$items->$key = $item;
	$jsondata = json_encode($items);
	$fn = "data/data.json";
	$fd = fopen( $fn,"w");
	$fc = fwrite( $fd, $jsondata );
	fclose($fd);

	// VISIT THE NEW PAGE
	header('Location: /blog?title='.$str_title);
	exit;
}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>openblog/new</title>
		<link rel="stylesheet" type="text/css" href="static/blog.css"/>
	</head>
	<body>
		<section class="main">
			<h1>New Log</h1>
			<form class="form-object">
				<input type="date" id="date" name="date" value="<?php echo $date; ?>" />
				<input type="title" id="title" name="title" placeholder="log entry title" required autofocus value=""/>
				<input type="name" id="author" name="author" placeholder="author" value="" required />
				<textarea id="content" name="content" placeholder='{{include "<?php echo $textform_content_fn; ?>" }}' required></textarea>
				<input type="text" id="tags" name="tags" placeholder="<?php echo $tags; ?>"/>
				<p>
					<button id="publish" type="submit" style="font-size:1.30rem" title="&#128065;&nbsp;Veuillez relire la publication et corrigez un maximum les erreurs de frappe &#9997;. Merci &#9996;">publier</button>
				</p>
			</form>
			<hr/>
			<div id="output">{{include "<?php echo $textform_content_fn; ?>" | markdown}}</div>
		</section>
	</body>
</html>
