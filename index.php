<?php
require 'database.php';
$entered_code = trim($_SERVER['REQUEST_URI'], '/');
if ($_SERVER["REQUEST_METHOD"] == "GET" && $entered_code !== "") {
    $stmt = $db->prepare("SELECT user_url FROM urls WHERE short_url = :entered_code");
    $stmt->bindParam(':entered_code', $entered_code, PDO::PARAM_STR);
    $stmt->execute();
    $link = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($link){
        $updateStmt = $db->prepare("UPDATE urls SET count = count + 1 WHERE short_url = :entered_code");
        $updateStmt->bindParam(':entered_code', $entered_code, PDO::PARAM_STR);
        $updateStmt->execute();
        header('Location: ' . $link['user_url']);
        exit();
    } else {
        echo "Нет такой ссылки";
        exit();
    }
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $stmtShorUrls = $db->query("SELECT short_url FROM urls");
    $shortUrls = $stmtShorUrls->fetchAll(PDO::FETCH_COLUMN);

    $user_url = $_POST["user_url"];
    $permitted_chars = 'abcdefghijklmnopqrstuvwxyz';
    $flag = false;
    while (!$flag) {
        $short_url = substr(str_shuffle($permitted_chars), 0, 4) . random_int(1000, 9999);
        if (!in_array($user_url, $shortUrls)) {
            $flag = true;
        }
    }
    $stmt = $db->prepare("INSERT INTO urls (user_url, short_url) VALUES (:user_url, :short_url)");
    $stmt->bindParam(':user_url', $user_url);
    $stmt->bindParam(':short_url', $short_url);
    $stmt->execute();
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}

?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
</head>
<body>
<h1>Ввод ссылки:</h1>
 <form method="post">
     <input type="text" name="user_url" placeholder="Введите ссылку" required>
     <button type="submit">сохр</button>
 </form>
<br>
</body>
</html>
<?php
$result = $db->query('SELECT * FROM urls');

while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
    echo "ID: " . htmlspecialchars($row['id']) .
        ", user_url: <a href='" . htmlspecialchars($row['user_url']) . "'>" . htmlspecialchars($row['user_url']) . "</a>" .
        ", short_url: <a href='/" . htmlspecialchars($row['short_url']) . "'>" . htmlspecialchars($row['short_url']) . "</a>" .
        ", count: " . htmlspecialchars($row['count']) . "<br>";
}
?>