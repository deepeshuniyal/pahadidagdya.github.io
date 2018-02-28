<?php
if(isset($_POST['facebook-page-url']) && isset($_POST['fb-app-id']))
{
	$FacebookSettingsArray = serialize(
		array(
			'FacebookPageUrl' => $_POST['facebook-page-url'],
			'ColorScheme' =>	'',
			'Header' => $_POST['show-widget-header'],
			'Stream' => $_POST['show-live-stream'],
			'Width' => $_POST['widget-width'],
			'Height' => $_POST['widget-height'],
			'FbAppId' => $_POST['fb-app-id'],
			'ShowBorder' => 'true',
			'ShowFaces' => $_POST['show-fan-faces'],
			'ForceWall' => 'false'
		)
	);
	update_option("weblizar_facebook_shortcode_settings", $FacebookSettingsArray);
} 
	$FacebookSettings = unserialize(get_option("weblizar_facebook_shortcode_settings"));
	//load default values OR saved values
	$ForceWall = 'false';
	if ( isset( $FacebookSettings[ 'ForceWall' ] ) ) {
		$ForceWall = $FacebookSettings[ 'ForceWall' ];
	}

	$Header = 'true';
	if ( isset( $FacebookSettings[ 'Header' ] ) ) {
		$Header = $FacebookSettings[ 'Header' ];
	}

	$Height = 560;
	if ( isset( $FacebookSettings[ 'Height' ] ) ) {
		$Height = $FacebookSettings[ 'Height' ];
	}

	$FacebookPageUrl = 'https://www.facebook.com/Weblizarwp/';
	if ( isset( $FacebookSettings[ 'FacebookPageUrl' ] ) ) {
		$FacebookPageUrl = $FacebookSettings[ 'FacebookPageUrl' ];
	}

	$ShowBorder = 'true';
	if ( isset( $FacebookSettings[ 'ShowBorder' ] ) ) {
		$ShowBorder = $FacebookSettings[ 'ShowBorder' ];
	}

	$ShowFaces = 'true';
	if ( isset( $FacebookSettings[ 'ShowFaces' ] ) ) {
		$ShowFaces = $FacebookSettings[ 'ShowFaces' ];
	}

	$Stream = 'true';
	if ( isset( $FacebookSettings[ 'Stream' ] ) ) {
		$Stream = $FacebookSettings[ 'Stream' ];
	}

	$Width = 292;
	if ( isset( $FacebookSettings[ 'Width' ] ) ) {
		$Width = $FacebookSettings[ 'Width' ];
	}

	$FbAppId = "488390501239538";
	if ( isset( $FacebookSettings[ 'FbAppId' ] ) ) {
		$FbAppId = $FacebookSettings[ 'FbAppId' ];
	}
?>