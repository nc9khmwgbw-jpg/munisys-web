<?php
define('WP_USE_THEMES', false);
require('./wp-load.php');
$html = do_shortcode('[munisys_insights]');
echo "LENGTH: " . strlen($html) . "\n";
if (strpos($html, 'GITEX') !== false) { echo "FOUND GITEX\n"; } else { echo "NO GITEX\n"; }
if (strpos($html, 'UNIVERS') !== false) { echo "FOUND UNIVERS\n"; } else { echo "NO UNIVERS\n"; }
