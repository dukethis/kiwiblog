# kiwiblog : another KISS weblog
## Features
- lightweight
- no installation
- RSS Feed : XML 2.0 & JSON
- completely open : no security layer ..../o/

## Usage
| URI | ACTION
| ---- | ----
| index.php | Serves the log index
| weblog.php | Generates HTML content
| new.php | Access the 'new log' form
| feed.php | Generates a XML Feed
| feed.php?json | Generates a JSON Feed

### Publication access

Use the index to see all available publications.
You can click on any listed hyperlink to go read it.

You can access the publication directly using the following **title** parameter syntax :
```
/weblog?title=author-this-is-the-title
```
