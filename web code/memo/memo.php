<?php
    session_start();
    
require_once("../lib/MYDB.php");  
$pdo = db_connect();

try{
    $sql="select * from phptest.memo order by num desc";
    $stmh = $pdo->query($sql);
    } catch (PDOException $Exception) {
    print "오류: ".$Exception->getMessage();
}
?>
<!DOCTYPE html>
<html>
    <head>
    <meta charset="UTF-8">
    <title>주차 현황</title>
    <link rel="stylesheet" type="text/css" href="../css/common.css">
    <link rel="stylesheet" type="text/css" href="../css/memo.css">
    </head>
<body>
    <div id="wrap">
        <div id="header">
        <?php include "../lib/top_login2.php"; ?>
        </div>
        
    <div id="menu">
        <?php include "../lib/top_menu2.php"; ?>
    </div>
        
    <div id="content">
        <div id="col1">
            <div id="left_menu">
                <?php include "../lib/left_menu.php"; ?>
            </div>
        </div> <!-- end of col1 -->
        <div id="col2">
            <div id="title">
                <img src="../img/title_memo.gif">
            </div>
<?php
if(isset($_SESSION["userid"])){ //로그인 했을 때 글 쓸수 있는 권한 부여
    ?>
<div id="memo_row1">
    <div id="memo_writer"><h1><span>▷ <?=$_SESSION["nick"]?>님 VIP주차장에 오신걸 환영합니다.</span></h1><br></br></div>
<div><h2>▷ 총 주차공간 : 6자리 </h2></div>
<div><h2>▷ 현재 : 3<!--<? =$_SESSION["level"] ?>-->자리 주차중</h2></div>    
<div><h2>▷ 주차 가능 공간 : 3<!--<? =$_SESSION["level"] ?>-->자리 주차 가능</h2></div>
    
</div> 
            <?php   
            }
            
            ?>
        </div> <!-- end of coㅣ2 -->
    </div> 
    </div><!-- end of wrap -->
<p>&nbsp;</p><p>&nbsp;</p>
    </body>
</html>
