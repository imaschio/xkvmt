<?php
#########################################
## Compressor option file ##############
#########################################
## Access control
$compress_options['username'] = "de03b43c7cbcd17c601df19642de9146";
$compress_options['password'] = "de03b43c7cbcd17c601df19642de9146";
## Path info
$compress_options['document_root'] = IA_HOME;
$compress_options['javascript_cachedir'] = IA_JSCSS_CACHEDIR;
$compress_options['css_cachedir'] = IA_JSCSS_CACHEDIR;
## Minify options
$compress_options['minify']['javascript'] = "1";
$compress_options['minify']['page'] = "1";
$compress_options['minify']['css'] = "1";
## Gzip options
$compress_options['gzip']['javascript'] = "1";
$compress_options['gzip']['page'] = "1";
$compress_options['gzip']['css'] = "1";
## Versioning
$compress_options['far_future_expires']['javascript'] = "1";
$compress_options['far_future_expires']['css'] = "1";
#########################################
?>
