<?php
if($_SERVER['REQUEST_METHOD'] !== "POST"){
    if(!defined('DB_USER')){
        require "../../config.php";
        try {
            $conn = new PDO("mysql:host=". DB_HOST. ";dbname=". DB_DATABASE , DB_USER, DB_PASSWORD);
        } catch (PDOException $e) {
            die("Could not connect to the database " . DB_DATABASE . ": " . $e->getMessage());
        }
    }
    try{
        $result = $conn->query("Select * from secret_word LIMIT 1");
        $result = $result->fetch(PDO::FETCH_OBJ);

        $result2 = $conn->query("Select * from interns_data where username='interactive_bee'");
        $user = $result2->fetch(PDO::FETCH_OBJ);
    } catch (PDOException $e){
        throw $e;
    }
    $secret_word = $result->secret_word;

}
?>

<?php
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    if(!defined('DB_USER')){
        require "../../config.php";
        try {
            $conn = new PDO("mysql:host=". DB_HOST. ";dbname=". DB_DATABASE , DB_USER, DB_PASSWORD);
        } catch (PDOException $e) {
            die("Could not connect to the database " . DB_DATABASE . ": " . $e->getMessage());
        }
    }
    require "../answers.php";
    date_default_timezone_set("Africa/Lagos");
    // header('Content-Type: application/json');
    if(!isset($_POST['question'])){
        echo json_encode([
            'status' => 1,
            'answer' => "Please type in a question"
        ]);
        return;
    }
    $question = $_POST['question']; //get the entry into the chatbot text field
    //check if in training mode
    $index_of_train = stripos($question, "train:");
    if($index_of_train === false){//then in question mode
        $question = preg_replace('([\s]+)', ' ', trim($question)); //remove extra white space from question
        $question = preg_replace("([?.])", "", $question); //remove ? and .
        //check if answer already exists in database
        $question = "%$question%";
        $sql = "select * from chatbot where question like :question";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':question', $question);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $rows = $stmt->fetchAll();
        if(count($rows)>0){
            $index = rand(0, count($rows)-1);
            $row = $rows[$index];
            $answer = $row['answer'];
            //check if the answer is to call a function
            $index_of_parentheses = stripos($answer, "((");
            if($index_of_parentheses === false){ //then the answer is not to call a function
                echo json_encode([
                    'status' => 1,
                    'answer' => $answer
                ]);
            }else{//otherwise call a function. but get the function name first
                $index_of_parentheses_closing = stripos($answer, "))");
                if($index_of_parentheses_closing !== false){
                    $function_name = substr($answer, $index_of_parentheses+2, $index_of_parentheses_closing-$index_of_parentheses-2);
                    $function_name = trim($function_name);
                    if(stripos($function_name, ' ') !== false){ //if method name contains whitespaces, do not invoke any method
                        echo json_encode([
                            'status' => 0,
                            'answer' => "The function name should be without white spaces"
                        ]);
                        return;
                    }
                    if(!function_exists($function_name)){
                        echo json_encode([
                            'status' => 0,
                            'answer' => "I am unable to find the function you require"
                        ]);
                    }else{
                        echo json_encode([
                            'status' => 1,
                            'answer' => str_replace("(($function_name))", $function_name(), $answer)
                        ]);
                    }
                    return;
                }
            }
        }else{
            echo json_encode([
                'status' => 0,
                'answer' => "I can't answer that right now, please train me.The format...<br> <b>train: question#answer#password</b>"
            ]);
        }
        return;
    }else{
        //in training mode
        //get the question and answer
        $question_and_answer_string = substr($question, 6);
        //remove excess white space in $question_and_answer_string
        $question_and_answer_string = preg_replace('([\s]+)', ' ', trim($question_and_answer_string));

        $question_and_answer_string = preg_replace("([?.])", "", $question_and_answer_string); //remove ? and . so that questions missing ? (and maybe .) can be recognized
        $split_string = explode("#", $question_and_answer_string);
        if(count($split_string) == 1){
            echo json_encode([
                'status' => 0,
                'answer' => "I can't answer that right now, please train me.The format...<br> <b>train: question#answer#password</b>"
            ]);
            return;
        }
        $que = trim($split_string[0]);
        $ans = trim($split_string[1]);
        if(count($split_string) < 3){
            echo json_encode([
                'status' => 0,
                'answer' => "training password required"
            ]);
            return;
        }
        $password = trim($split_string[2]);
        //verify if training password is correct
        define('TRAINING_PASSWORD', 'password');
        if($password !== TRAINING_PASSWORD){
            echo json_encode([
                'status' => 0,
                'answer' => "invalid training password"
            ]);
            return;
        }

        //insert into database
        $sql = "insert into chatbot (question, answer) values (:question, :answer)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':question', $que);
        $stmt->bindParam(':answer', $ans);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        echo json_encode([
            'status' => 1,
            'answer' => "Thank you, I am now smarter"
        ]);
        return;
    }
    echo json_encode([
        'status' => 0,
        'answer' => "I can't answer that right now, please train me.The format...<br> <b>train: question#answer#password</b>"
    ]);

}
?>

<!DOCTYPE html>
<html lang="en">
<body style="background-color:#87bdd8;">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://fonts.googleapis.com/css?family=Rhodium+Libre" rel="stylesheet">
    <title>Blessing Akpan</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link id="css" rel="stylesheet" href="https://static.oracle.com/cdn/jet/v4.0.0/default/css/alta/oj-alta-min.css" type="text/css">
    <style type="text/css">

    <style>
        .panel-body{
            background: #ccc;
        }
        .body0 {
            height: 100%;
        .under {
            position: relative;
            /*top:450px;*/
            height: 50px;
            width: 100%;
            font-family: "Alegreya";
            line-height: normal;
            font-size: 32px;
            text-align: center;
            color: #000830;
        }
        .panel-heading h5{
            display: inline;
        }
        .pan{
            margin-top: 20px;
        }
        .box1{
            padding: 5px 7px;
            color: #fff;
            margin-left: 15px;
            margin-bottom: 10px;
            border-radius: 25px;
            background: linear-gradient(-85deg, #d9edf7, #9d9d9d);
        }
        .box2{
            padding: 5px 7px;
            color: #fff;
            margin-right: 15px;
            margin-bottom: 10px;
            border-radius: 25px;
            background: linear-gradient(-85deg, #a3c2a1, #2b0404);
        }
        .form-input{
            margin-top: 60px;
        }
    </style>
</head>
<style>
    body{
        font-family: 'Rhodium Libre', serif;
    }
    #container{
        min-height: 400px;
        text-align:center;
        background-color: lightgrey;
        border: 25px solid blue;
        padding: 25px;
        margin: 25px;
    }

    .inputText{
        margin:16px;
        width:70%;
    }
    img{
        border-radius: 5px;
        background-color: beige;
        width:350px;
        height:300px;
        margin-top:3em;
    }
    h1{
        font-size: 2.5em;
        margin-bottom:0px;
        text-transform:uppercase;
    }
    h5{
        font-size: 2em;
        font-style:bold;
        color:Coral;
    }
    #content{
        text-align:centre;
    }
    p{
        margin:0px;
        font-size: 20px;
        font-weight: bold;
    }
    #chat-interface{
        height:300px;
        overflow: auto;
    }
    .image-icon{
        width:30px;
        height:30px;
    }

    #bot-message{
        text-align: left;
        color: blue !important;
    }


    #user-message{
        text-align: right;
        color: green !important;
    }

    #question{
        width: 100%;
        height: 50px;
        color: green;
        text-align: right;
        font-size: 20px;

    }

    #submit-button{
        background-color: blue;
        width:100%;
        height:50px;
        background-color: blue;
        border: 0px solid transparent;
        border-radius: 3px;
        color: white;
        padding: 0px;
    }

    .no-margin{
        padding:0px;
        margin:0px;
    }
     #header-trigger{
        position: fixed;
        bottom: 0;
        right: 0;
        z-index: 10000;
        cursor: pointer;
    }

    #bot-head{
        cursor: pointer;
    }
</style>
<body>
<div id="container">
<div class="oj-flex oj-flex-items-pad oj-contrast-marker">
<div class="oj-sm-6 oj-md-6 oj-flex-item">
    <div class="oj-flex oj-sm-align-items-center oj-sm-margin-2x">
        <div role="img" class="oj-flex-item alignCenter">
            <oj-avatar role="img" size="[[avatarSize]]" initials='[[initials]]'
            data-bind="attr:{'aria-label':'Avatar of Akpan, Blessing Michael'}">
            </oj-avatar>
            <img class="img-fluid " onerror="this.src='images/default.jpg'" src="http://res.cloudinary.com/dlvlxep3r/image/upload/v1523715773/interactive_bee.jpg">
        </div>
    </div>
    <div class="body0">
        <h1><?php echo $user->name; ?></h1>
        <p>Writer | Android Developer | HNG Intern</p>
        <p>Akwa Ibom, Nigeria</p>
                <div class="oj-flex oj-md-align-items-center"><a href="https://github.com/BeeAkpan">
                    <div class="oj-flex-item oj-flex oj-sm-flex-direction-column oj-sm-align-items-center oj-sm-margin-2x">
                        <img style="width:40px; height: 40px;" src="https://cdn1.iconfinder.com/data/icons/logotypes/32/github-512.png">
                    </div></a>

                    <a href="https://www.linkedin.com/in/interactivebee/">
                         <div class="oj-flex-item oj-flex oj-sm-flex-direction-column oj-sm-align-items-center oj-sm-margin-2x">
                            <img style="width:40px; height: 40px;" src="http://icons.iconarchive.com/icons/custom-icon-design/pretty-social-media/256/Linkedin-icon.png">
                         </div>
                    </a>
                </div></span>
    </div>
</div>
            <div class="col-sm-4" id="header-trigger">
                <div class="panel panel-default pan" >
                    <div class="panel-heading">
                        <h5>SMART CHAT-BOT</h5>
                    </div>
                </div>
            </div>
            <div class="oj-sm-8">
                <div id="chatter">
                    <div class="panel panel-default pan">
                        <div class="panel-heading" id="bot-head">
                            <h5>SMART CHAT-BOT</h5>
                        </div>
                        <div id="chats">
                            <div class="panel-body" id="chat-interface"></div>
                        </div>

                        <!-- New Form for input-->
                        <form action="#" method="post" id="bot-interface">
                            <div class="oj-md-10 col-xs-10 no-margin" style="margin:0px">
                                <input class="inputText no-margin" type="text" name="question" id="question" >
                            </div>
                            <div class="oj-md-2 oj-xs-2 no-margin">
                                <input type="Submit" value="Send" placeholder="Write your message and press enter" id="submit-button">
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- jQuery library -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

<!-- Latest compiled JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</div>
<script>

    $(document).ready(function(){
        // By default hide the chat bot
        $('#chatter').hide();

        // Activate Chat bot visibility
        $('#header-trigger').click(function(){
            $('#header-trigger').hide();
            $('#chatter').fadeIn('3000');
            $('#chatter').focus();
            $('#question').focus();
        });

        $('#bot-head').click(function(){
            $('#header-trigger').fadeIn('1000');
            $('#chatter').hide();
        });

        $('#submit-button').click(function(){

            $('#chat-interface').stop().animate({scrollTop: $('#chat-interface')[0].scrollHeight}, 1000);

        });


        var questionForm = $('#bot-interface');
        questionForm.submit(function(e){
            e.preventDefault();
            console.log($("#chats")[0].scrollHeight);
            var questionBox = $('#question');
            var question = questionBox.val();
            //display question in the message frame as a chat entry
            var messageFrame = $('#chat-interface');
            var chatToBeDisplayed = '<div class="row message" id="user-message">'+
                '<div class="oj-md-12">'+
                '<p>'+question+'</p>'+
                '</div>'+
                '</div>';
            messageFrame.html(messageFrame.html()+chatToBeDisplayed);
            $("#chats").scrollTop($("#chats")[0].scrollHeight);

            //send question to server
            $.ajax({
                url: "../profiles/interactive_bee.php",
                type: "post",
                data: {question: question},
                dataType: "json",
                success: function(response){
                    if(response.status == 1){
                        var chatToBeDisplayed = '<div class="row message"id="bot-message">'+
                            '<div class="oj-md-1 " >'+
                            '<p>Bot:</p>'+
                            '</div>'+
                            '<div class="oj-md-11 ">'+
                            '<p>'+response.answer+'</p>'+
                            '</div>'+
                            '</div>';
                        messageFrame.html(messageFrame.html()+chatToBeDisplayed);
                        questionBox.val("");
                    }else if(response.status == 0){
                        var chatToBeDisplayed = '<div class="row message" id="bot-message">'+
                            '<div class="oj-md-1 " >'+
                            '<p>Bot:</p>'+
                            '</div>'+
                            '<div class="oj-md-11 ">'+
                            '<p>'+response.answer+'</p>'+
                            '</div>'+
                            '</div>';
                        messageFrame.html(messageFrame.html()+chatToBeDisplayed);
                        $("#chats").scrollTop($("#chats")[0].scrollHeight);

                    }
                },
                error: function(error){
                    console.log(error);
                }
            })
        });
    });
</script>
</body>
</html>