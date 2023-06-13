<?php

if (isset($_GET['order'])) {
    $direction = $_GET['order'];
} else {
    $direction = 'desc';
}

if (isset($_GET['search_query'])) {
    $title = '%' . $_GET['search_query'] . '%';
    $contents = '%' . $_GET['search_query'] . '%';
} else {
    $title = '%%';
    $contents = '%%';
}

$dbUserName = 'root';
$dbPassword = 'password';
$pdo = new PDO(
    'mysql:host=mysql; dbname=tq_filter; charset=utf8',
    $dbUserName,
    $dbPassword
);

$sql = 'SELECT * FROM pages';
$statement = $pdo->prepare($sql);
$statement->bindValue(':title', $title, PDO::PARAM_STR);
$statement->bindValue(':content', $content, PDO::PARAM_STR);

$statement->execute();
$pages = $statement->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>top画面</title>
</head>

<body>
  <div>
    <div>
      <div class="background right">
        <form action="privatepage.php" method="POST">
          <input type="submit" name="昇順" value="新しい順">
          <input type="submit" name="降順" value="古い順">
        </form>

        <?php if (isset($_POST['昇順']) && $_POST['昇順'] === '新しい順') { ?>
        <?php array_multisort(
            array_column($pages, 'created_at'),
            SORT_ASC,
            $pages
        );} ?>
        <?php if (isset($_POST['降順']) && $_POST['降順'] === '古い順') { ?>
        <?php array_multisort(
            array_column($pages, 'created_at'),
            SORT_DESC,
            $pages
        );} ?>
      
      </div>

    <div>
      <table border="1">
        <tr>
          <th>タイトル</th>
          <th>内容</th>
          <th>作成日時</th>
        </tr>
        <?php foreach ($pages as $page): ?>
          <tr>
            <td><?php echo $page['name']; ?></td>
            <td><?php echo $page['contents']; ?></td>
            <td><?php echo $page['created_at']; ?></td>
          </tr>
        <?php endforeach; ?>
      </table>
    </div>
  </div>
</body>

</html>