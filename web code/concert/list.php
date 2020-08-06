<?php
    session_start();
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<link rel="stylesheet" type="text/css" href="../css/common.css">
<link rel="stylesheet" type="text/css" href="../css/concert.css">
</head>

<?php

require_once("../lib/MYDB.php");  
$pdo = db_connect();

if(isset($_REQUEST["mode"]))
    $mode=$_REQUEST["mode"];
else
    $mode="";
if(isset($_REQUEST["search"]))
    $search=$_REQUEST["search"];
else
    $search="";

if(isset($_REQUEST["find"]))
    $find=$_REQUEST["find"];
else
    $find="";

if($mode=="search"){
    if(!$search){
  ?>
      <script>
        alert('검색할 단어를 입력해주세요!');
        history.back();
      </script>
   <?php
    }
    $sql="select * from phptest.parking where id = ?";
} else {
    $sql="select * from phptest.parking order by num desc";
}
try{
    $stmh = $pdo->query($sql);
    $count=$stmh->rowCount();
?>
<body>
    <div id="wrap">
        <div id="header">
        <?php include "../lib/top_login2.php"; ?>
        </div>
    <div id="menu">
        <?php include "../lib/top_menu2.php"; ?>
    </div>
        <div id="concert">
            <div id="col1">
                <div id="left_menu">
                    <?php include "../lib/left_menu.php"; ?>
                </div>
            </div> <!-- end of col1 -->
            
            <div id="col2">
                <div id="title"><img src="../img/title_concert.gif"></div>
                <form name="board_form" method="post" action="list.php?mode=search">
                    <div id="list_search">
                        <div id="list_search1"> 총 <?= $count ?> 개의 게시물이 있습니다.</div>
                        <div id="list_search2"><img src="../img/select_search.gif"></div>
                        <div id="list_search3">
                            <select name="find">
                                <option value='subject'>제목</option>
                                <option value='content'>내용</option>
                                <option value='nick'>닉네임</option>
                                <option value='name'>이름</option>
                            </select></div> <!-- end of list_search3 -->
                            <div id="list_search4"><input type="text" name="search"></div>
                             <div id="list_search5"><input type="image" src="../img/list_search_button.gif"></div>
                    </div> <!-- end of list_search -->
                </form>
                <div class="clear"></div>
                <div id="list_top_title">
                    <ul>
                        <li id="list_title1"><img src="../img/list_title1.gif"></li>
                        <li id="list_title2"><img src="../img/list_title2.gif"></li>
                        <li id="list_title3"><img src="../img/list_title3.gif"></li>
                        <li id="list_title4"><img src="../img/list_title4.gif"></li>
                        <li id="list_title5"><img src="../img/list_title5.gif"></li>
                    </ul>
                </div> <!-- end of list_top_title -->
                <div id="list_content">
                    
         <?php
         while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
             $item_num=$row["num"];
             $item_id=$row["id"];
             $item_name=$row["name"];
             $item_nick=$row["nick"];
             $item_hit=$row["hit"];
             $item_date=$row["regist_day"];
             $item_date=substr($item_date, 0, 10);
         ?>
            <div id="list_item">
                <div id="list_item1"><?= $item_num ?></div>
                <div id="list_item2"><a href="view.php?num=<?= $item_num ?>"><?= $item_subject ?></a></div>
                <div id="list_item3"><?= $item_nick ?></div>
                <div id="list_item4"><?= $item_date ?></div>
                <div id="list_item5"><?= $item_hit ?></div>
                    </div> <!-- end of list_item -->
<?php
   }
} catch (PDOException $Exception) {
    print "오류: ".$Exception->getMessage();
}
?>
                    
 <div id="write_button">
     <a href="list.php"><img src="../img/list.png"></a>&nbsp;
<?php
if(isset($_SESSION["userid"]))
    {
?>
     <a href="write form.php"><img src="../img/write.png"></a>
<?php

     }
     ?>
        </div>
                </div>
            </div> <!-- end of col2 -->
        </div> <!-- end of content -->
    </div> <!-- end of wrap -->
</body>
</html>

        