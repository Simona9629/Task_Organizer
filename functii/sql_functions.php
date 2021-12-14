<?php
function conectareBD($host = 'localhost', $user = 'root', $password = '', $database = 'agenda') {
    return mysqli_connect($host, $user, $password, $database);
}

function clearData($input, $link)
{
    $input = trim($input);
    $input = htmlspecialchars($input);
    $input = stripcslashes($input);
    $input = mysqli_real_escape_string($link, $input);
    return $input;
}


function preiaUtilizatorDupaEmail($email)
{
    $link = conectareBD();
    $query = "SELECT * FROM utilizator WHERE email='$email'";
    $result = mysqli_query($link, $query);
    $user = mysqli_fetch_array($result, MYSQLI_ASSOC);
    return $user;
}

function inregistrareUtilizator($nume, $prenume, $email, $parola)
{
    $link = conectareBD();
    $nume = clearData($nume, $link);
    $prenume = clearData($prenume, $link);
    $email = clearData($email, $link);
    $parola = clearData($parola, $link);
    $parola = md5($parola);
    $user = preiaUtilizatorDupaEmail($email);
    
    if ($user) {
        return ['error' => true, 'message' => "Utilizatorul deja exista!"];
    }
    $query = "INSERT INTO utilizator VALUES(NULL, '$nume', '$prenume', '$email', '$parola', NULL)";
    $result = mysqli_query($link, $query);
    if ($result) {
        return ['error' => false, 'message' => "Cont creat cu succes!"];
    } else {
        return ['error' => true, 'message' => "A aparut o eroare"];
    }
}

function conectareUtilizator($email, $parola)
{
    $link = conectareBD();
    $email = clearData($email, $link);
    $parola = clearData($parola, $link);
    $user = preiaUtilizatorDupaEmail($email);
    if ($user) {
        return md5($parola) == $user['parola'];
    }
    return false;
}

function preiaUtilizatorDupaId($id)
{
    $link = conectareBD();
    $query = "SELECT * FROM utilizator WHERE id='$id'";
    $result = mysqli_query($link, $query);
    $user = mysqli_fetch_array($result, MYSQLI_ASSOC);
    return $user;
}
function updateUtilizatorDupaId($id, $imagine)
{
    $link = conectareBD();
    $query = "UPDATE utilizator SET poza_profil = '$imagine' WHERE id = $id";
    $rezultat = mysqli_query($link, $query);
    return $rezultat;
}

function adaugareTask($titlu, $data, $descriere, $tip,$id_utilizator)
{
    $link = conectareBD();
    $titlu = clearData($titlu, $link);
    $descriere = clearData($descriere, $link);
    $query = "INSERT INTO task VALUES(NULL, '$titlu', '$data', '$tip', '$descriere', 0, $id_utilizator )";
    
    $rezultat = mysqli_query($link, $query);
    return $rezultat;
}

function preluareTaskuriDupaIdUtilizator($id)
{
    $link = conectareBD();
    $query = "SELECT * FROM task WHERE id_utilizator = $id";
    $rezultat = mysqli_query($link, $query);
    $taskuri = mysqli_fetch_all($rezultat, MYSQLI_ASSOC);
    
    return $taskuri;
}

function actualizareStatusDupaIdTask($id)
{
    $link = conectareBD();
    $query = "UPDATE task SET status = 1 WHERE id = $id";
    $rezultat = mysqli_query($link, $query);
    return $rezultat;
}

function preluareTaskuriDupaKeywordIdUtilizator($keyword, $id)
{
    $link = conectareBD();
    $query = "SELECT * FROM task WHERE tip = '$keyword' AND id_utilizator = $id";
    $rezultat = mysqli_query($link, $query);
    $taskuri = mysqli_fetch_all($rezultat, MYSQLI_ASSOC);
    return $taskuri;
}