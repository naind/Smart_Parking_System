<?php
session_start();

$id = $_REQUEST["id"];
$pw = $_REQUEST["pass"];

if($id == "admin" && $pw == "123456")
{   
    $_SESSION["userid"]="admin";
    $_SESSION["name"]="관리자";
    $_SESSION["nick"]="관리자";
    $_SESSION["level"]=1;
    
    header("Location:http://10.10.11.15:8080/ch09/index.php");
    exit;
}

require_once("../lib/MYDB.php");
$pdo = db_connect();

try{
    $sql = "select * from phptest.member where id=?";
    $stmh = $pdo->prepare($sql);
    $stmh->bindValue(1,$id,PDO::PARAM_STR);
    $stmh->execute();
    
    $count = $stmh->rowCount();
} catch (PDOException $Exception) {
    print "오류: ".$Exception->getMessage();
}
$row=$stmh->fetch(PDO::FETCH_ASSOC);

if($count<1) {
?>

<script>
    alert("아이디가 틀립니다!");
    history.back();
    </script>
    
<?php
} elseif($pw!=$row["pass"]){
?>

    <script>
        alert("비밀번호가 틀립니다!");
        history.back();
    </script>
    
<?php
} else{
    $_SESSION["userid"]=$row["id"];
    $_SESSION["name"]=$row["name"];
    $_SESSION["nick"]=$row["nick"];
    $_SESSION["level"]=$row["level"];
    
    header("Location:http://10.10.11.15:8080/ch09/index.php");
    exit;
}
?>
