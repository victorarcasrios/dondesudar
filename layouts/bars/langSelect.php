<select id="lang">
    <?php
    $langs = array(
        "es" => "Español",
        "en" => "English", 
        "ca" => "Catalán");
    
    foreach ($langs as $v => $l) {
        $status = ($_SESSION["lang"] === $v)? "selected" : "";
        echo "<option $status value='$v'>$l</option>";
    }
    
    ?>    
</select>