
<!doctype html>

<html
    <head>
        <title>Login Page</title>
        
        <style>
            .login{
                width: 360px;
                margin: 50px auto;
                font: cambria, "hoefler text", "liberation serif", times, "times new roman",serif;
                border-radius: 10px;
                border: 5px solid #ccc;
                padding: 10px 40px 25px;
                margin-top: 78px;
            }
            input[type=text],input[type=password]{
                width: 99%;
                padding: 10px;
                margin-top: 8px;
                border: 1px solid #ccc;
                padding-left: 5px;
                font-size: 16px;
                font-family: cambria, "hoefler text", "liberation serif", times, "times new roman",serif;
                
            }
            input[type=submit]{
                width: 100px;
                background-color: #0099cc;
                border: 2px solid #06f;
                padding: 10px;
                font-size: 20px;
                color: #ffffff;
                cursor: pointer;
                border-radius: 5px;
                margin-bottom: 15px;
            }
            
        </style>
    </head>
    <body>
        <div class="login">
            <h2 align="center">Selamat datang, silahkan login!</h2>
            <form action="index.php?opq=in" method="POST" style="text-align: center;">          	
                <input type="text" placeholder="NIK" id="nik" name="user"> </br></br>              
                <input type="password" placeholder="Password" id="pass" name="pass"> </br></br>
                <input type="submit" value="Login" name="submit"> 
                <span>
                    <? session_start();
                    echo "$_SESSION['error']"
                    ?>
                </span>

            </form>
        </div>
    </body>