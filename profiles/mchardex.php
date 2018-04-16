<!DOCTYPE html>
<html>
<head>

    <?php 
		require 'db.php';

		$result = $conn->query("Select * from secret_word LIMIT 1");
		$result = $result->fetch(PDO::FETCH_OBJ);
		$secret_word = $result->secret_word;

		$result2 = $conn->query("Select * from interns_data where username = 'mchardex'");
        $user = $result2->fetch(PDO::FETCH_OBJ);
        
	?>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<style>
        .card {
        box-shadow: 0 19px 17px 0 rgba(0, 0, 0, 0.2);
        max-width: 300px;
        margin: auto;
        text-align: center;
        font-family: arial;
    }

        .title {
        color: #d69999;
        font-size: 18px;
    }

        a {
        text-decoration: none;
        font-size: 22px;
        color: black;
    }

        a:hover {
        opacity: 0.7;
    }
    </style>
</head>
<body>

	<h2 style="text-align: center;
    font-family: sans-serif;
    font-size: 20px;
    text-shadow: 2px 2px 6px grey;">My Profile Card</h2>

	<div class="card">
	  <img src="<?php echo $user->image_filename ?>" alt="mchardex" style="width:100%; height: 350px">
	  <h1 style="font-size: 24px;">@<?php echo $user->username ?></h1>
	  <p class="title">Web Developer</p>
      <p class="username">@<?php echo $username; ?></p>
	  <p>Javascript, NodeJs and jquery</p>
	  <div style="margin: 24px 0; padding-bottom: 20px">
	    <a href="https://twitter.com/mchardex"><i class="fa fa-twitter"></i></a>  
	    <a href="https://www.linkedin.com/in/adebisi-oluwabukunmi-13159474/"><i class="fa fa-linkedin"></i></a>  
	    <a href="https://www.facebook.com/bhukunmie.hardehbc.5"><i class="fa fa-facebook"></i></a> 
	    <a href="https://github.com/McHardex"><i class="fa fa-github"></i></a>
	 </div>
	</div>

</body>
</html>
