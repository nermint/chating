


<?php

session_start();


function formLogin(){
    echo '<div id="loginForm"><form action="index.php" method="POST">
    <h1>LOGIN</h1>
    <p>Please enter your name to continue..</p>
    <div class="container">
    <input type="text" placeholder="Username" name="name" id="name">
    <input type="submit" value="Login" name="enter" id="enter">
    </div>
    </form></div>';
}




 if(isset($_POST['enter'])){
    $username=$_POST['name'];
    if(empty($username)){
        header("Location: ./");
        
    }
       
    if($_POST['name'] !=" "){
        $_SESSION['name']=stripslashes(htmlspecialchars($_POST['name']));
     }
    else{
        echo '<span class="error">Please type in a name</span>';
    }
}


//for get
if(isset($_GET['logout'])){ 
     
    //Simple exit message
    $fp = fopen("log.html", 'a');
    fwrite($fp, "<div class='msgln'><i>User ". $_SESSION['name'] ." has left the chat session.</i><br></div>");
    fclose($fp);
     
    session_destroy();
    header("Location: index.php"); //Redirect the user
}


?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css?family=Roboto+Condensed&display=swap" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <title>ONLINE CHAT | WEB</title>
</head>
<body>
    <?php
        if(!isset($_POST['name']))
            formLogin();
        else{
        ?>
        <div class="wrapper">
            <div class="inner">
                <div class="top-side">
                    <div>welcome , <b><?=$_SESSION['name']; ?></b></div>
                    <div class="exit" id="exit" >exit chat</div>
                </div>
                <div class="message-side" id="chatbox">
                    <?php
                        if(file_exists("log.html") && filesize("log.html")>0){
                            $handle=fopen("log.html","r");
                            $contents=fread($handle, filesize("log.html"));
                            fclose($handle);
                            echo $contents;
                        }
                    ?>
                </div>
                <div class="bottom-side">
                        <input name="usermsg" type="text" id="usermsg" size="63">
                        <input name="submitmsg" type="submit" id="submitmsg" value="Send">
                </div>
            </div>
        </div>
        <?php
           }
        ?>



<script type="text/javascript">
// jQuery Document

$(document).ready(function(){
    //If user wants to logout and end session
    $('#exit').click(function(){
        var exit = confirm("Are you sure you want to end the session?");
		if(exit==true)
        {
            window.location = 'index.php?logout=true';
        }	
    });

    //If user submits the send button
    //ajax
    $("#submitmsg").click(function(){	
		var clientmsg = $("#usermsg").val();
		$.post("post.php", {text: clientmsg});				
		$("#usermsg").attr("value", "");
		return false;
	});


    

    //msj yazildiqca sehifenin scroll etmesi ve melumatin chatbox'a yazilmasi
    function loadLog(){		
		var oldscrollHeight = $("#chatbox").prop("scrollHeight")-20; //Scroll height before the request
        console.log(oldscrollHeight);
		$.ajax({
			url: "log.html",
			cache: false,
			success: function(html){		
				$("#chatbox").html(html); //Insert chat log into the #chatbox div	
				
				//Auto-scroll			
				var newscrollHeight = $("#chatbox").prop("scrollHeight") - 20; //Scroll height after the request
                console.log(newscrollHeight);
				if(newscrollHeight == oldscrollHeight){
                    console.log("geldi");
					$("#chatbox").animate({ scrollTop: 120 }); //Autoscroll to bottom of div
				}				
		  	},
		});
	}

    //her 2.5 saniyeden bir loadLog funksiyasi caliwir ve lazim geldikde sehifeni scroll edir
    setInterval (loadLog, 2500);

    //loadLog();
    

});
</script>


</body>
</html>