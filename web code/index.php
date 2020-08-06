<?php
session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>주차관리 홈페이지</title>
        <meta name="viewport" content="width=device-width,
              initial-scale=1.0, maximum-scale=1.0,minimum-scale=0.3,
              user-scalable=yes">
        <style>
            body{margin:0 auto; width:98%}
            @media screen and (max-width:801px){
                #wrap {margin:0 auto; width:20%; float: left;}
            }
            /*481~800px(pc)*/
            @media screen and (max-width:800px){
                #wrap {width:35%; float: left;}
            }
            /*480px까지 적용 (모바일)*/
            @media screen and (max-width:480px){
                #wrap {width:99%;}
            }
        </style>
        <link rel="stylesheet" type="text/css" href="./css/common.css">
    </head>
    
    <body>
        <div id="wrap">
            <div id="header">
                <?php include "./lib/top_login1.php"; ?>
            </div> <!-- end of header -->
            <div id="menu">
                <?php include "./lib/top_menu1.php"; ?>
            </div> <!-- end of menu -->   
            
            <div id="content">
                <div id="main_img"><img src="./img/main_img.jpg"></div>
            </div> <!-- end of content -->
            </div> <!-- end of wrap -->
    </body>
</html>
