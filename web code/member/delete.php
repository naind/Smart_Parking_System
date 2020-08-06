<?php
$id=$_REQUEST["num"];

require_once("../lib/MYDB.php");
$pdo= db_connect();

try {
    $pdo->beginTransaction(); //selete 외 해당됨.
    $sql="delete from phptest.parking where num=?";
    $stmh=$pdo->prepare($sql);
    $stmh->bindValue(1, $id,PDO::PARAM_STR);
    
    $stmh->execute();
    $pdo->commit();
    header("Location:http://10.10.11.15:8080/ch09/member/list.php");
} catch (PDOException $Exception) {
    $pdo->rollBack();
    print "오류 :".$Exception->getMessage();
}
?>