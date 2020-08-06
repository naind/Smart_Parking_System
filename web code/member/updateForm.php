<?php
    session_start();
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>회원정보 수정</title>
<link rel="stylesheet" type="text/css" href="../css/common.css">
<link rel="stylesheet" type="text/css" href="../css/member.css">
 
<script>
function check_id()
{
    window.open("check_id.php?id="+document.member_form.id.value,"IDcheck",
    "left=200, top=200, width=200, height=60, scrollbars=no,resizable=yes");
}

function check_nick()
{
    window.open("check_nick.php?nick="+document.member_form.nick.value,
    "NICKcheck","left=200, top=200, width=200, height=60, scrollbars=no,resizable=yes");
}
function check_input()
{
    if(!document.member_form.hp2.value || !document.member_form.hp3.value)
    {
        alert("휴대폰 번호를 입력하세요"); 
        document.member_form.nick.focus();
        return;
    }
    
    if(!document.member_form.number.value)
    {
        alert("차량 번호를 입력하세요"); 
        document.member_form.number.focus();
        return;
    }
    
    if(document.member_form.pass.value !=
    document.member_form.pass_confirm.value)
    {
        alert("비밀번호가 일치하지 않습니다.\n다시 입력해주세요."); 
        document.member_form.pass.focus();
        document.member_form.pass.select();
        return;
    }
    document.member_form.submit();
}

function reset_form()
{
    document.member_form.id.value = "";
    document.member_form.pass.value = "";
    document.member_form.pass_confirm.value = "";
    document.member_form.name.value = "";
    document.member_form.nick.value = "";
    document.member_form.hp2.value = "";
    document.member_form.hp3.value = "";
    document.member_form.number.value = "";
    //document.member_form.email2.value = "";
    
    document.member_form.id.focus();
    
    return;
}
</script>
</head>
<?php
    $id = $_REQUEST["id"];
    require_once("../lib/MYDB.php");  
    $pdo = db_connect();   

try{
    $sql = "select * from phptest.member where id = ? ";
    $stmh = $pdo->prepare($sql);
    $stmh->bindValue(1, $id, PDO::PARAM_STR);
    $stmh->execute();
    $count = $stmh->rowCount();
    } catch (PDOException $Exception) {
        print "오류: ".$Exception->getMessage();
    }
    if($count<1){
        print "검색결과가 없습니다.<br>";
    }else{
    while($row = $stmh->fetch(PDO::FETCH_ASSOC)){
        $hp=explode("-", $row["hp"]);
        $hp2=$hp[1];
        $hp3=$hp[2];
        
        //$email=explode($row["email"]);
        //$email1=$email[0];
        //$email2=$email[1];
?>
<body>
<div id="wrap">
<div id="header">
	<?php include "../lib/top_login2.php"; ?>
</div> <!-- end of header -->

<div id="menu">
  <?php include "../lib/top_menu2.php"; ?>
</div> <!-- end of menu --> 
<div id="content">
<div id="col1">
<div id="left_menu">
  <?php include "../lib/left_menu.php"; ?>
</div>
</div> <!-- end of col1 -->

<div id="col2">
    <form name="member_form" method="post" action="updatePro.php?id=<?=$id?>">
<div id="title">
    <img src="../img/title_member_modify.gif">
</div> <!-- end of title -->
<div id="form_join">
    <div id="join1">
        <ul>
            <li>* 아이디</li>
            <li>* 비밀번호</li>
            <li>* 비밀번호 확인</li>
            <li>* 이름</li>
            <li>* 닉네임</li>
            <li>* 휴대폰</li>
            <li>* 차량번호</li>
            <!--<li>* &nbsp;&nbsp;&nbsp;이메일</li>-->
        </ul>
    </div> <!-- end of join -->
    <div id="join2">
        <ul>
            <li><?= $row["id"] ?></li>
            <li><input type="password" name="pass" placeholder="15자 이내" value="<?= $row["pass"] ?>" required></li>
            <li><input type="password" name="pass_confirm" placeholder="15자 이내" value="<?= $row["pass"] ?>" required></li>
            <li><input type="text" name="name" placeholder="홍길동" value="<?= $row["name"] ?>" required></li>
            <li><div id="nick1"><input type="text" name="nick" placeholder="닉네임을 입력하세요" value="<?= $row["nick"] ?>" required>
                </div> <div id="nick2"> <a href="#"><img src="../img/check_id.gif" onclick="check_nick()"></a></div></li>
            <li><input type="text" class="hp" name="hp1" value="010"> - <input type="text" class="hp" name="hp2" value="<?= $hp2 ?>"> - 
            <input type="text" class="hp" name="hp3" value="<?= $hp3 ?>"></li>
            <li><input type="text" name="number" placeholder="차량 번호 입력" value="<?= $row["number"] ?>" required></li>
          <!--  <li><input type="text" id="email1" name="email1" value="<? = $email1 ?>"> @
                <input type="text" name="email2" placeholder="naver.com" value="<? = $email2 ?>"></li> -->
        </ul>
    </div>
    <?php }
    }?>
    
    <div class="clear"></div>
    <div id="must"> * 는 필수 입력항목입니다.^^</div>
</div>

<div id="button"><a href="#"><img src="../img/button_save.gif" onclick="check_input()"></a>&nbsp;&nbsp;
    <a href="#"><img src="../img/button_reset.gif" onclick="reset_form()"></a>
</div>
    </form>
</div>
</div>
</div>
    
</body>
</html>

