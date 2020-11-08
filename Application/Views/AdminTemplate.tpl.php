<?php

// data
global $tplData;

require_once("PageTemplate.class.php");

$tmp = new PageTemplate();
// zacatek stranky
$tmp->getTop("test");
$tmp->getNavbar(isset($tplData["isLogged"]) ? $tplData["isLogged"] : false);
?>
<?php
if ($tplData["isAdmin"]) {
    ?>
    <div class="container">
        <div class="card">
            <div class="card-title">Uživatelé</div>
            <div class="card-body">
                <table class="table">
                    <thead>
                    <tr>
                        <th>Jméno</th>
                        <th>Přijmení</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>počet napsaných článku</th>
                        <th>pořet zrecenzovaných článků</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    if (isset($tplData["users"])) {
                        foreach ($tplData["users"] as $row) {
                            ?>
                            <tr>
                                <td><?php echo $row["jmeno"] ?></td>
                                <td><?php echo $row["prijmeni"] ?></td>
                                <td><?php echo $row["email"] ?></td>
                                <td><?php echo $row["id_pravo"] ?></td>
                                <td>TODO</td>
                                <td>TODO</td>
                            </tr>
                            <?php
                        }
                    }
                    ?>
                    </tbody>
                </table>
                <ul class="pagination">

                </ul>
            </div>
        </div>
        <div class="card">
            <div class="card-title">Články bez přiděleného hodnocení</div>
            <div class="card-body">
                <table class="table">
                    <thead>
                    <tr>
                        <th>Název</th>
                        <th>Autor</th>
                        <th>Datum nahrání</th>
                        <th>Podrobnosti</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    if (isset($tplData["notAArticles"])) {
                        $count = 0;
                        foreach ($tplData["notAArticles"] as $row) {
                            ?>
                            <tr>
                                <td><?php echo $row["nazev"] ?></td>
                                <td><?php echo $row["id_uzivatel"] ?></td>
                                <td><?php echo $row["datum"] ?></td>
                                <td>TODO</td>
                                <td>
                                    <form action="" method="POST">
                                        <div class="form-group">
                                            <input hidden id="id" name="id" value="<?php echo $row["id_clanek"] ?>">
                                            <button type="button" class="btn btn-primary" data-toggle="modal"
                                                    data-target="#myModal" <?php echo $count ?>>
                                                Přirad recenzenta
                                            </button>
                                            <button type="submit" name="deny" value="deny" class="btn btn-primary">
                                                Zamítnout
                                            </button>
                                        </div>
                                    </form>
                                    <div class="modal" id="myModal" <?php echo $count ?>>
                                        <div class="modal-dialog">
                                            <div class="modal-content">

                                                <!-- Modal Header -->
                                                <div class="modal-header">
                                                    <h4 class="modal-title">Vybrat recenzenta</h4>
                                                    <button type="button" class="close" data-dismiss="modal">&times;
                                                    </button>
                                                </div>

                                                <!-- Modal body -->
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label for="frecenzent">Recenzent</label>
                                                        <select id="frecenzent" class="form-control">
                                                            <?php
                                                            foreach ($tplData["reviewers"] as $row) {
                                                                ?>
                                                                <option> <?php echo $row["jmeno"] . $row["prijmeni"] ?> </option>
                                                                <?php
                                                            }
                                                            ?>
                                                        </select>
                                                        <button type="submit" name="assign" value="assign"
                                                                class="btn btn-primary">
                                                            Přiradit
                                                        </button>
                                                    </div>
                                                </div>

                                                <!-- Modal footer -->
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-danger" data-dismiss="modal">
                                                        Close
                                                    </button>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <?php
                            echo ++$count;
                        }
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card">
            <div class="card-title">Zamítnuté články</div>
            <div class="card-body">
                <table class="table">
                    <thead>
                    <tr>
                        <th>Název</th>
                        <th>Autor</th>
                        <th>Datum nahrání</th>
                        <th>Podrobnosti</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    if (isset($tplData["deniedArticles"])) {
                        foreach ($tplData["deniedArticles"] as $row) {
                            ?>
                            <tr>
                                <td><?php echo $row["nazev"] ?></td>
                                <td><?php echo $row["id_uzivatel"] ?></td>
                                <td><?php echo $row["datum"] ?></td>
                                <td>TODO</td>
                            </tr>
                            <?php
                        }
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php
}
?>
<?php

$tmp->getBottom();
