<?php
// セッション開始
session_start();

// ローカルのMySQLDBに接続
$servername = "localhost";
$username = "root";
$password = "migikatanochou";
$dbname = "mysample";

// データベース接続の作成
$conn = new mysqli($servername, $username, $password, $dbname);

// 接続チェック
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "接続完了<br>";

// フォームが送信された場合の処理
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $name = $_POST['name'];               // フォームから名前を取得
  $uninumber = $_POST['uninumber'];     // フォームから背番号を取得
  $birthday = $_POST['birthday'];       // フォームから誕生日を取得
  $position = $_POST['position'];       // フォームからポジションを取得
  $review = $_POST['review'];           // フォームから寸評を取得

  // プリペアドステートメントを使用してSQLインジェクションを防ぐ
  $sql = "INSERT INTO test1 (name, uninumber, birthday, position, review) VALUES (?, ?, ?, ?, ?)";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("sssss", $name, $uninumber, $birthday, $position, $review);

    

    
    if ($stmt->execute()) {
      // データベースにデータを挿入後、セッションにメッセージを保存し、リダイレクト
      $_SESSION['message'] = "選手情報が登録されました。";
      header("Location: " . $_SERVER['PHP_SELF']);
      exit();
  } else {
      $_SESSION['message'] = "エラー: " . $stmt->error;
  }

  $stmt->close();
}




// データベース接続を最後に閉じる
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Simple CMS</title>
</head>
<body>
    <h2>選手情報を入稿する</h2>

    <!-- メッセージ表示 -->
    <?php
    if (isset($_SESSION['message'])) {
        echo "<p>" . htmlspecialchars($_SESSION['message']) . "</p>";
        unset($_SESSION['message']); // メッセージを表示後に削除
    }
    ?>


    <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
        <label for="name">名前:</label><br>
        <input type="text" id="name" name="name" required><br>
        <label for="uninumber">背番号:</label><br>
        <input type="text" id="uninumber" name="uninumber" required><br>
        <label for="position">ポジション:</label><br>
        <select id="position" name="position">
            <option value="1">投手</option>
            <option value="2">捕手</option>
            <option value="3">内野手</option>
            <option value="4">外野手</option>
        </select><br>
        <label for="birthday">生年月日:</label><br>
        <input type="date" id="birthday" name="birthday" required><br>
        <label for="review">寸評:</label><br>
        <textarea id="review" name="review" rows="4" cols="50"></textarea><br>

        <input type="submit" value="送信">
    </form>
</body>
</html>
