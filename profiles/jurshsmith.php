<?php 
		require 'db.php';
		$result = $conn->query("Select * from secret_word LIMIT 1");
		$result = $result->fetch(PDO::FETCH_OBJ);
		$secret_word = $result->secret_word;
		$result2 = $conn->query("Select * from interns_data where username = 'jurshsmith'");
		$user = $result2->fetch(PDO::FETCH_OBJ);
	?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="description" content="">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>
HNG 4.0 | Jurshsmith
</title>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

<!-- jQuery library -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

<!-- Latest compiled JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.10/css/all.css" integrity="sha384-+d0P83n9kaQMCwj8F4RJB66tzIwOKmrdb46+porD/OvrJ+37WqIM7UoBtwHO6Nlg" crossorigin="anonymous">
<!--Javascript starts here-->
<script>
$('document').ready(function(){
	$('.list-inline-items').hide();
});
</script>

<!--my css starts here-->
<style>
html,body{
		background-color: #f4f4f4;

}

#j-background{
		background-color: #f4f4f4;
				}

#j-image{
		border: 7px solid grey;
		border-radius: 45px;
		max-width: 100%;
}
#j-firstdiv{
		border-right: 1px solid grey;
		text-align: center;
		font-family: montserrat;
		color : grey;
}
.j-header{
		font-family: montserrat;
		color: grey;
		font-weight: 370;
}
.j-center{
		text-align: center;
}


/**chatbot css here**/
#show-chatbot{
	
	background: grey;
	color: white;
	position: fixed;
	right: 4px;
	bottom: 4px;
	padding: 5px;
	display: inline;
	z-index: 250;

}
#chat-interface{

	background-color: red;
	height: 300px;
	width: 300px;
	position: fixed;
	right: 4px;
	bottom: 33px;
	z-index: 23;

}
.add-transparency{
	background-color: #d0d0d0 !important;
}
#chatbot-header{
	background-color: #a39c9c !important;
	font-size: 16px;
}
#chatbot-header span{
	padding : 13px;
}
#chatbot-footer{
	position: fixed;
	right: 4px;
	bottom: 33px;
	background-color: #a39c9c;

}
#chatbot-footer span{

	padding : 10px;

}
.chatbot-text-area{
	position: fixed;
	bottom:  20px;
	width: 264px;
}
</style>
</head>
<body>
<!--chatbot html here-->
<a id = "j-show-chatbot" style = "color:white" href = "#">
<div id = "show-chatbot">
	<div class = "row">
		
		<div class = "col-lg-12 col-md-12 col-sm-12 col-xs-12">
 <span class="glyphicon glyphicon-user"></span> &nbsp;&nbsp;Click toggle chatbot
		</div>
	</div>
</div>
</a>
<div id = "chat-interface" style = "background-color: transparent">

	<div id = "chatbot-header">
		<span class="glyphicon glyphicon-fullscreen"></span>
	 	<span style = "position: relative; left : 200px" class="glyphicon glyphicon-remove-sign"></span>
	</div>


	<form class = "chatbot-text-area">
			<div class="form-group">
  
  <input type="text" class="form-control" id="usr">
				</div></form>
	<div id = "chatbot-footer">
	
		<span class="glyphicon glyphicon-send"></span>
		

	</div>


</div>
<!-- chatbot 
html 
ends here -->

<div id = "j-background" style = "width : 100%">
	<div class = "row">
		<div id = "j-firstdiv" class = "col-lg-5 col-md-5 col-sm-12 col-xs-12">
			<br><br>
			<center><img  src = "http://res.cloudinary.com/jurshsmith/image/upload/v1524109896/josh.png" width = "350px" id = "j-image"></center><br><br>
			<font class = "j-profile">  <span class="glyphicon glyphicon-user"></span>  &nbsp;</font>
			<font class = "j-profile" style = "font-weight: 600;">OLADELE JOSHUA</font><br>
			 <font class = "j-header" style = "font-weight: 250;">
			 <span class="glyphicon glyphicon-ok-sign"></span> &nbsp;
			 	HNG INTERN</font><br>
			<br><br>
			<br><br>
			<br><br>
			

			 	<a href = "https://github.com/Jurshsmith"><i class="fab fa-github j-profile"></i></a>
			 	<a href = "https://github.com/Jurshsmith"><i class="fab fa-instagram j-profile"></i></a>
			 	<a href = "https://github.com/Jurshsmith"><i class="fab fa-twitter j-profile"></i></a>

			<br><br>
		</div>
		<div class = "col-lg-7 col-md-7 col-sm-12 col-xs-12">
			<br><br>
			<div id = "j-story">
			<font class = "j-header">ABOUT ME &nbsp;&nbsp;    <span class="glyphicon glyphicon-pencil"></span></font>
			<br><br><br><br><br><br>
			<br><br><br><br><br><br>
			<font class = "j-header">MY QUALIFICATIONS&nbsp;&nbsp;<span class="glyphicon glyphicon-wrench"></span></font>
			<br><br><br><br><br><br>
			<br><br><br><br><br><br>
			<font class = "j-header">MY SKILLS &nbsp;&nbsp;<span class="glyphicon glyphicon-briefcase"></span></font>
			<br><br><br><br><br><br>
			<br><br><br><br><br><br>
			</div>

		</div>
	</div>
</div>


</body>
</html>
<!--css js-->
<script type = "text/javascript">
$(document).ready(
function(){
  setInterval(function(){
var width = $(document).innerWidth();
if(width < 1000 )
{
  $('.j-profile').css({"font-size": "20px"});
  $('.j-header').css({"font-size": "16px"});
  $('#j-story').css({"text-align":"center"});




}
else{
  $('.j-profile').css({"font-size": "26px"});
  $('#j-story').css({"text-align":"left"});



}

  },6);
}
  );
</script>




<!-- chatbot js -->
<script type="text/javascript">
$('#chat-interface').hide();


$('#show-chatbot').click(function(){
$('#chat-interface').addClass('add-transparency');

$('#chat-interface').toggle(500);


});

</script>
