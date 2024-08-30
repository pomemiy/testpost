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

  // SQLコードの挿入を防ぐ
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

