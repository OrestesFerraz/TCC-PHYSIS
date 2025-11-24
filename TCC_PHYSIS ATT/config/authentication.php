<?php
function autenticado()
{
    if (isset($_SESSION["email"])) {
        return true;
    } else {
        return false;
    }
}

function admin()
{
    if (isset($_SESSION["admin"]) && $_SESSION["admin"] == 1) {
        return true;
    } else {
        return false;
    }
}

function esp()
{
    if (isset($_SESSION["esp"]) && $_SESSION["esp"] == 1) {
        return true;
    } else {
        return false;
    }
}

function nome_usuario()
{
    return $_SESSION["nome"];
}

function email_usuario()
{
    return $_SESSION["email"];
}

function foto_usuario()
{
    return $_SESSION["urlperfil"];
}

function id_usuario()
{
    return $_SESSION["id_usuario"];
}

function redireciona($pagina = null)
{
    if (empty($pagina)) {
        $pagina = "../index.php";
    }
    header("Location: " . $pagina);
}
?>