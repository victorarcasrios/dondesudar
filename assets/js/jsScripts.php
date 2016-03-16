<!-- Default JS/JQUERY scripts -->
<script type="text/javascript" src="assets/js/jquery.js"></script>
<script type='text/javascript' src="assets/js/bootstrap.js"></script>
<script type="text/javascript" src="assets/js/lang.js"></script>
<script type="text/javascript" src="assets/js/config.js"></script>

<!-- Specific JS/JQUERY scripts -->
<?php
if (isset($jsAdditionalScripts)) {
    foreach ($jsAdditionalScripts as $script)
        echo "<script type='text/javascript' src='assets/js/$script.js'></script>";
}

if (isset($jsExternalAdditionalScripts)) {
    foreach ($jsExternalAdditionalScripts as $script)
        echo "<script type='text/javascript' src='$script'></script>";
}
?>