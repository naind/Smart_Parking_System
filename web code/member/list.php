<!DOCTYPE html>
<?php
    require_once("../lib/MYDB.php");
    $pdo= db_connect();
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title>주차장 입출 관리목록</title>
    </head>
    <body>
 <?php
try{
    $sql="select * from phptest.parking";
    $stmh=$pdo->query($sql);
    
    $count=$stmh->rowCount();
    print "검색 결과는 $count 건입니다.<br>";
 
} catch (PDOException $Exception) {
    print "오류 :".$Exception->getMessage();
}
    if($count<1)
        print "출입자가 없습니다.<br>";
    else{
?>
        <a href="../index.php">홈페이지로 가기</a>
        <table border="1" width="600">
            <tr align="center">
                <th>num</th><th>아이디</th><th>이름</th><th>차량번호</th><th>출입시간</th>
                <!--<th>수정</th>--><th>삭제</th>
            </tr>
<?php
    while($row=$stmh->fetch(PDO::FETCH_ASSOC)){
?>
            <tr align="center">
                <td><?=$row['num']?></td><td><?=$row['id']?></td><td><?=$row['name']?></td>
                <td><?=$row['number']?></td><td><?=$row['regist_day']?></td>
                <!--<td><a href="../ch09/member/updateForm.php?id=<? =$row['id']?>">수정</a></td>-->
                <td><a href="delete.php?num=<?=$row['num']?>">삭제</a></td>
            </tr>
          
    <?php } 
    }
    ?>
        </table>  
    </body>
</html>
