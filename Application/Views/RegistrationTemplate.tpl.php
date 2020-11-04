<?php

require_once("PageTemplate.class.php");

$tmp = new PageTemplate();
// zacatek stranky
$tmp->getTop("test");
$tmp->getNavbar();
?>
    <div class="container">

        <h1 class="text-center py-5" style="color: #083B66">REGISTRACE</h1>

        <form action="" method="POST">
            <div class="form-group">
                <label for="femail" style="color: #1761A0">E-mail</label>
                <input type="email" class="form-control" id="femail" name="femail" placeholder="E-mail" required><br>

                <label for="ff_name" style="color: #1761A0">Křestní jméno</label>
                <input type="text" class="form-control" id="ff_name" name="ff_name" placeholder="Jméno" required><br>

                <label for="fl_name" style="color: #1761A0">Přijmení</label>
                <input type="text" class="form-control" id="fl_name" name="fl_name" placeholder="Přijmení" required><br>

                <label for="fpassword" style="color: #1761A0">Heslo</label>
                <input type="password" class="form-control" id="fpassword" name="fpassword" placeholder="Heslo"
                       required><br>

                <label for="fpassword2" style="color: #1761A0">Heslo</label>
                <input type="password" class="form-control" id="fpassword2" name="fpassword2"
                       placeholder="Zopakujte Heslo" required
                       onchange="comparePw()"><br>
                <p id="ctext" class="text-warning py-0" style="font-size: small; display: none"> *zadaná hesla musí být stejná </p>
            </div>
            <button type="submit" name="action" id="register" value="register" class="btn btn-light w-100 py-2" style="margin-bottom: 5rem" disabled>
                Registrovat se
            </button>
        </form>
    </div>
<?php

$tmp->getBottom();
?>

<script>
    function comparePw() {
        var str1 = document.getElementById("fpassword").value;
        var str2 = document.getElementById("fpassword2").value;

        if (str1 === str2) {
            document.getElementById("register").disabled = false;
            document.getElementById("ctext").style.display = "none";
        }
        else {
            document.getElementById("register").disabled = true;
            document.getElementById("ctext").style.display = "block";
        }
    }
</script>