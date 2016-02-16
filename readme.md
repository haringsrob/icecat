INTRODUCTION
------------

 * REQUIREMENTS
 * INSTALLATION
 * USAGE

REQUIREMENTS
------------

This class requires php 5.3.

INSTALLATION
------------

Add the file to your system using an autoloader.
Or you can include the file like:

require_once '/classesIcecat/Icecat.php';

Then you ened to Use it.
use Icecat\Icecat;

USAGE
-----
To use it, please look at the Icecat.php file.

Basic guidance:

// HERE ICECAT STARTS TO WORK.
// Start the class
$icecat = new Icecat\Icecat();

// Init our icecat config.
$icecat->setConfig(
  'icecat_username',
  'icecat_password'
);

// Set the language.
$icecat->setLanguage('icecat_language');

// Set our product data.
$icecat->setProductInfo(
  'icecat_ean',
  'icecat_sku',
  'icecat_brand'
);

// If we have an error, we should stop.
if ($icecat->hasErrors()) {
  // Set our error session.
  $errors = $icecat->hasErrors();
} else {
  // Continue to work with data.
  $producttitle = $icecat->getAttribute('Title');
}
