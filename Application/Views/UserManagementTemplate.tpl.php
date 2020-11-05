<?php

$fileExt = '"pdf"';
// data
global $tplData;

require_once("PageTemplate.class.php");

$tmp = new PageTemplate();
// zacatek stranky
$tmp->getTop("test");
$tmp->getNavbar(isset($tplData["isLogged"]) ? $tplData["isLogged"] : false);
?>
<?php
// uzivatel musi byt prihlasen
if ((!isset($tplData["isLogged"])) || (isset($tplData["isLogged"]) && !$tplData["isLogged"])) {
    echo "uzivatel musi byt prihlasen";
} else {
    ?>
    <div class="container">
        <div class="d-flex justify-content-center">
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#text1">
                nový článek
            </button>
        </div>
        <div class="modal" id="text1">
            <div class="modal-dialog">
                <div class="modal-content">

                    <div class="modal-header text justify-content-center">
                        <h4 class="modal-title">Nový článek</h4>
                    </div>
                    <div class="modal-body">
                        <!-- forma -->
                        <form enctype="multipart/form-data" action="" method="POST">
                            <div class="form-group">
                                <label for="fabstract">Abstrakt</label>
                                <textarea id="fabstract" name="fabstract" class="form-control" rows="10"
                                          cols="50" required></textarea>
                            </div>
                            <div class="form-group">
                                <label for="fname">Autor</label>
                                <input type="text" name="fname" class="form-control" id="fname" required>
                            </div>
                            <div class="form-group">
                                <label for="ffile">Soubor</label>
                                <input type="hidden" name="MAX_FILE_SIZE" value="300000000">
                                <input type="file" name="ffile" class="form-control-file" id="ffile"
                                       onchange="checkFileExt()" required>
                                <small id="ffileValidationBlock" class="form-text text-muted text-warning"
                                       style="display: none">
                                    Soubor musí mít koncovku '.pdf'
                                </small>
                            </div>
                            <div class="d-flex justify-content-center">
                                <button type="submit" class="btn btn-primary" name="action" id="article" value="upload" disabled>
                                    Odeslat článek k ověření</button>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Zavřít</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
}
?>
<?php

$tmp->getBottom();

?>

<script>
    function checkFileExt() {
        var file = document.getElementById("ffile").value;
        const index = file.lastIndexOf(".");
        const fileExt = file.substring(index + 1, file.length);

        if (fileExt.toLowerCase() == <?php echo $fileExt ?>) {
            document.getElementById("ffileValidationBlock").style.display = "none";
            document.getElementById("article").disabled = false;
        } else {
            document.getElementById("ffileValidationBlock").style.display = "block";
            document.getElementById("article").disabled = true;
        }
    }
</script>
