<!-- Default CSS stylesheet -->
<link href="assets/css/bootstrap.css" rel="stylesheet" type="text/css" media="all" />
<link href="assets/css/defaultStyle.css" rel="stylesheet" type="text/css" media="screen" />

<!-- Specific CSS stylesheet -->
<?php
if (isset($cssAdditionalStyles)) {
    foreach ($cssAdditionalStyles as $style)
        echo "<link href='assets/css/$style.css' rel='stylesheet' type='text/css' media='all' />";
}
?>