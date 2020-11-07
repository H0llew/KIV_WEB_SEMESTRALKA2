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
        <!-- novy clanek -->
        <div class="card">
            <div class="d-flex justify-content-center">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#text1">
                    nový článek
                </button>
            </div>
        </div>
        <!-- vytvoreni noveho clanku -->
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
                                <label for="fheading">Název</label>
                                <input type="text" name="fheading" class="form-control" id="fabstract" required>
                            </div>
                            <div class="form-group">
                                <label for="fabstract">Abstrakt</label>
                                <textarea id="fabstract" name="fabstract" class="form-control" rows="10"
                                          cols="50" required></textarea>
                            </div>
                            <div class="form-group">
                                <label for="fname">Autor</label>
                                <input type="text" name="fname" class="form-control"
                                       id="fname" placeholder="<?php echo $tplData["userName"] ?>" readonly>
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
                                <button type="submit" class="btn btn-primary" name="action" id="article" value="upload"
                                        disabled>
                                    Odeslat článek k ověření
                                </button>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Zavřít</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- moje clanky -->
        <div class="card">
            <!-- chvalene clanky -->
            <div class="card-title">Moje články</div>
            <div class="card">
                <div class="card-title">Schválené články</div>
            </div>
            <!-- neschvalene clanky -->
            <div class="card">
                <div class="card-title">Neschválené články</div>
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>Název</th>
                            <th>Datum vložení</th>
                            <th>Více informací</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        if (isset($tplData["not_approved"])) {
                            $count = 0;
                            foreach ($tplData["not_approved"] as $row) {
                                ?>
                                <tr>
                                    <td><?php echo $row["nazev"] ?></td>
                                    <td><?php echo $row["datum"] ?></td>
                                    <td>
                                        <button type="button" class="btn btn-primary" data-toggle="modal"
                                                data-target="#textArticle<?php echo $count ?>">
                                            Podrobnosti
                                        </button>
                                    </td>
                                </tr>
                                <div class="modal" id="textArticle<?php echo $count ?>">
                                    <div class="modal-dialog">
                                        <div class="modal-content">

                                            <div class="modal-header text justify-content-center">
                                                <h4 class="modal-title">Článek</h4>
                                            </div>
                                            <div class="modal-body">
                                                <!-- forma -->
                                                <form enctype="multipart/form-data" action="" method="POST">
                                                    <div class="form-group">
                                                        <label for="fheading">Název</label>
                                                        <input type="text" name="fheading" class="form-control"
                                                               id="fabstract" value="<?php echo $row["nazev"] ?>"
                                                               required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="fdate">Datum nahrání</label>
                                                        <input type="text" name="fdate" class="form-control"
                                                               id="fdate" value="<?php echo $row["datum"] ?>"
                                                               readonly>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="fabstract">Abstrakt</label>
                                                        <textarea id="fabstract" name="fabstract" class="form-control"
                                                                  rows="10"
                                                                  cols="50"
                                                                  required><?php echo $row["abstrakt"] ?></textarea>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="fname">Autor</label>
                                                        <input type="text" name="fname" class="form-control"
                                                               id="fname"
                                                               placeholder="<?php echo $tplData["userName"] ?>"
                                                               readonly>
                                                    </div>
                                                    <div class="form-group">
                                                        <input type="hidden" id="ffilePath" name="ffilePath"
                                                               value="<?php echo $row["soubor"] ?>">
                                                    </div>
                                                    <div class="form-group">
                                                        <?php $row["soubor"] = str_replace("C:/xampp/htdocs", "", $row["soubor"]) ?>
                                                        <a href="<?php echo $row["soubor"] ?>"
                                                           download="<?php echo $row["nazev"] ?>"> Stáhnout pdf
                                                            článek</a>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="ffile">Nový soubor</label>
                                                        <input type="hidden" name="MAX_FILE_SIZE" value="300000000">
                                                        <input type="file" name="ffile"
                                                               class="form-control-file"
                                                               id="ffile<?php echo $count ?>"
                                                               onchange="checkFileExt('<?php echo $count ?>')">
                                                        <small id="ffileValidationBlock<?php echo $count ?>"
                                                               class="form-text text-muted text-warning"
                                                               style="display: none">
                                                            Soubor musí mít koncovku '.pdf'
                                                        </small>
                                                    </div>
                                                    <div class="d-flex justify-content-center">
                                                        <button type="submit" class="btn btn-primary" name="action"
                                                                id="article" value="edit">
                                                            Editovat článek
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-danger" data-dismiss="modal">
                                                    Zavřít
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php
                                $count++;
                            }
                        }
                        ?>
                        </tbody>
                    </table>
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
    function checkFileExt(num = "") {
        var file = document.getElementById("ffile" + num).value;
        const index = file.lastIndexOf(".");
        const fileExt = file.substring(index + 1, file.length);

        if (fileExt.toLowerCase() == <?php echo $fileExt ?>) {
            document.getElementById("ffileValidationBlock" + num).style.display = "none";
            document.getElementById("article").disabled = false;
        } else {
            document.getElementById("ffileValidationBlock" + num).style.display = "block";
            document.getElementById("article").disabled = true;
        }
    }
</script>
