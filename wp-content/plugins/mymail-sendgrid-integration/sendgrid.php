<?php
/*
Plugin Name: MyMail SendGrid Integration
Plugin URI: http://rxa.li/mymail?utm_campaign=wporg&utm_source=MyMail+SendGrid+Integration
Description: Uses SendGrid to deliver emails for the MyMail Newsletter Plugin for WordPress. This requires at least version 2.0.25 of the plugin
Version: 0.5.1
Author: revaxarts.com
Author URI: https://revaxarts.com

License: GPLv2 or later
*/


define( 'MYMAIL_SENDGRID_VERSION', '0.5.1' );
define( 'MYMAIL_SENDGRID_REQUIRED_VERSION', '2.0.25' );
define( 'MYMAIL_SENDGRID_ID', 'sendgrid' );


class MyMailSendGird {

	private $plugin_path;
	private $plugin_url;

	/**
	 *
	 */
	public function __construct() {

		$this->plugin_path = plugin_dir_path( __FILE__ );
		$this->plugin_url = plugin_dir_url( __FILE__ );

		register_activation_hook( __FILE__, array( &$this, 'activate' ) );
		register_deactivation_hook( __FILE__, array( &$this, 'deactivate' ) );

		load_plugin_textdomain( 'mymail-sendgrid', false, dirname( plugin_basename( __FILE__ ) ) . '/lang' );

		add_action( 'init', array( &$this, 'init' ), 1 );
	}


	/*
	 * init the plugin
	 *
	 * @access public
	 * @return void
	 */
	public function init() {

		if ( ! function_exists( 'mymail' ) ) {

			add_action( 'admin_notices', array( &$this, 'notice' ) );

		} else {

			add_filter( 'mymail_delivery_methods', array( &$this, 'delivery_method' ) );
			add_action( 'mymail_deliverymethod_tab_sendgrid', array( &$this, 'deliverytab' ) );

			add_filter( 'mymail_verify_options', array( &$this, 'verify_options' ) );

			if ( mymail_option( 'deliverymethod' ) == MYMAIL_SENDGRID_ID ) {
				add_action( 'mymail_initsend', array( &$this, 'initsend' ) );
				add_action( 'mymail_presend', array( &$this, 'presend' ) );
				add_action( 'mymail_dosend', array( &$this, 'dosend' ) );
				add_action( 'mymail_sendgrid_cron', array( &$this, 'reset' ) );
				add_action( 'mymail_cron_worker', array( &$this, 'check_bounces' ), -1 );
				add_action( 'mymail_check_bounces', array( &$this, 'check_bounces' ) );
				add_action( 'mymail_section_tab_bounce', array( &$this, 'section_tab_bounce' ) );
			}
		}

		if ( function_exists( 'mailster' ) ) {

			add_action( 'admin_notices', function(){

				$name = 'MyMail SendGrid Integration';
				$slug = 'mailster-sendgrid/mailster-sendgrid.php';

				$install_url = wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=' . dirname( $slug ) ), 'install-plugin_' . dirname( $slug ) );

				$search_url = add_query_arg( array(
					's' => $slug,
					'tab' => 'search',
					'type' => 'term',
				), admin_url( 'plugin-install.php' ) );

				?>
			<div class="error">
				<p>
				<strong><?php echo esc_html( $name ) ?></strong> is deprecated in Mailster and no longer maintained! Please switch to the <a href="<?php echo esc_url( $search_url ) ?>">new version</a> as soon as possible or <a href="<?php echo esc_url( $install_url ) ?>">install it now!</a>
				</p>
			</div>
				<?php

			} );
		}

	}


	/**
	 * initsend function.
	 *
	 * uses mymail_initsend hook to set initial settings
	 *
	 * @access public
	 * @return void
	 * @param mixed $mailobject
	 */
	public function initsend( $mailobject ) {

		$method = mymail_option( MYMAIL_SENDGRID_ID . '_api' );

		if ( $method == 'smtp' ) {

			$secure = mymail_option( MYMAIL_SENDGRID_ID . '_secure' );

			$mailobject->mailer->Mailer = 'smtp';
			$mailobject->mailer->SMTPSecure = $secure ? 'ssl' : 'none';
			$mailobject->mailer->Host = 'smtp.sendgrid.net';
			$mailobject->mailer->Port = $secure ? 465 : 587;
			$mailobject->mailer->SMTPAuth = true;
			$mailobject->mailer->Username = mymail_option( MYMAIL_SENDGRID_ID . '_user' );
			$mailobject->mailer->Password = mymail_option( MYMAIL_SENDGRID_ID . '_pwd' );
			$mailobject->mailer->SMTPKeepAlive = true;

		} elseif ( $method == 'web' ) {

		}

		// sendgrid will handle DKIM integration
		$mailobject->dkim = false;

	}


	/**
	 * presend function.
	 *
	 * uses the mymail_presend hook to apply settings before each mail
	 *
	 * @access public
	 * @return void
	 * @param mixed $mailobject
	 */
	public function presend( $mailobject ) {

		$method = mymail_option( MYMAIL_SENDGRID_ID . '_api' );

		$xsmtpapi['unique_args'] = array(
			'subject' => $mailobject->subject,
			'subscriberID' => $mailobject->subscriberID,
			'campaignID' => $mailobject->campaignID,
		);
		$categories = mymail_option( MYMAIL_SENDGRID_ID . '_categories' );
		if ( ! empty( $categories ) ) {
			$xsmtpapi['category'] = array_slice( array_map( 'trim', explode( ',', $categories ) ), 0, 10 );
		}

		if ( $method == 'smtp' ) {

			if ( ! empty( $xsmtpapi ) ) {
				$mailobject->add_header( 'X-SMTPAPI', json_encode( $xsmtpapi ) );
			}

			// use pre_send from the main class
			$mailobject->pre_send();

		} elseif ( $method == 'web' ) {

			// embedding images doesn't work
			$mailobject->embed_images = false;

			$mailobject->pre_send();

			$mailobject->sendgrid_object = array(
				'from' => $mailobject->from,
				'fromname' => $mailobject->from_name,
				'replyto' => $mailobject->reply_to,
				// doesn't work right now
				// 'returnpath' => $mailobject->bouncemail,
				'to' => $mailobject->to,
				'subject' => $mailobject->subject,
				'text' => $mailobject->mailer->AltBody,
				'html' => $mailobject->mailer->Body,
				'api_user' => mymail_option( MYMAIL_SENDGRID_ID . '_user' ),
				'api_key' => mymail_option( MYMAIL_SENDGRID_ID . '_pwd' ),
				'files' => array(),
				'content' => array(),
				'x-smtpapi' => json_encode( $xsmtpapi ),
			);

			if ( ! empty( $mailobject->headers ) ) {
				$mailobject->sendgrid_object['headers'] = json_encode( $mailobject->headers );
			}

			// currently not working on some clients
			if ( false ) {

				$images = $this->embedd_images( $mailobject->make_img_relative( $mailobject->content ) );

				$mailobject->sendgrid_object['files'] = wp_parse_args( $images['files'] , $mailobject->sendgrid_object['files'] );
				$mailobject->sendgrid_object['content'] = wp_parse_args( $images['content'] , $mailobject->sendgrid_object['content'] );

			}

			if ( ! empty( $mailobject->attachments ) ) {

				$attachments = $this->attachments( $mailobject->attachments );

				$mailobject->sendgrid_object['files'] = wp_parse_args( $attachments['files'] , $mailobject->sendgrid_object['files'] );
				$mailobject->sendgrid_object['content'] = wp_parse_args( $attachments['content'] , $mailobject->sendgrid_object['content'] );

			}
		}

	}


	/**
	 * dosend function.
	 *
	 * uses the ymail_dosend hook and triggers the send
	 *
	 * @access public
	 * @param mixed $mailobject
	 * @return void
	 */
	public function dosend( $mailobject ) {

		$method = mymail_option( MYMAIL_SENDGRID_ID . '_api' );

		if ( $method == 'smtp' ) {

			// use send from the main class
			$mailobject->do_send();

		} elseif ( $method == 'web' ) {

			if ( ! isset( $mailobject->sendgrid_object ) ) {
				$mailobject->set_error( __( 'SendGrid options not defined', 'mymail-sendgrid' ) );
				return false;
			}

			$response = $this->do_call( 'mail.send', $mailobject->sendgrid_object, true );

			// set errors if exists
			if ( isset( $response->errors ) ) {
				$mailobject->set_error( $response->errors );
			}

			if ( isset( $response->message ) && $response->message == 'success' ) {
				$mailobject->sent = true;
			} else {
				$mailobject->sent = false;
			}
		}
	}




	/**
	 * reset function.
	 *
	 * resets the current time
	 *
	 * @access public
	 * @param mixed $message
	 * @return array
	 */
	public function reset() {
		update_option( '_transient__mymail_send_period_timeout', false );
		update_option( '_transient__mymail_send_period', 0 );

	}



	/**
	 * attachments function.
	 *
	 * prepares the array for attachemnts
	 *
	 * @access public
	 * @param unknown $attachments
	 * @return array
	 */
	public function attachments( $attachments ) {

		$return = array(
			'files' => array(),
			'content' => array(),
		);

		foreach ( $attachments as $attachment ) {
			if ( ! file_exists( $attachment ) ) {
				continue;
			}
			$filename = basename( $attachment );

			$return['files'][ $filename ] = file_get_contents( $attachment );
			$return['content'][ $filename ] = $filename;

		}

		return $return;
	}


	/**
	 * embedd_images function.
	 *
	 * prepares the array for embedded images
	 *
	 * @access public
	 * @param mixed $message
	 * @return array
	 */
	public function embedd_images( $message ) {

		$return = array(
			'files' => array(),
			'content' => array(),
			'html' => $message,
		);

		$upload_folder = wp_upload_dir();
		$folder = $upload_folder['basedir'];

		preg_match_all( "/(src|background)=[\"']([^\"']+)[\"']/Ui", $message, $images );

		if ( isset( $images[2] ) ) {

			foreach ( $images[2] as $i => $url ) {
				if ( empty( $url ) ) {
					continue;
				}
				if ( substr( $url, 0, 7 ) == 'http://' ) {
					continue;
				}
				if ( substr( $url, 0, 8 ) == 'https://' ) {
					continue;
				}
				if ( ! file_exists( $folder . '/' . $url ) ) {
					continue;
				}
				$filename = basename( $url );
				$directory = dirname( $url );
				if ( $directory == '.' ) {
					$directory = '';
				}
				$cid = md5( $folder . '/' . $url . time() );
				$return['html'] = str_replace( $url, 'cid:' . $cid, $return['html'] );
				$return['files'][ $filename ] = file_get_contents( $folder . '/' . $url );
				$return['content'][ $filename ] = $cid;
			}
		}
		return $return;
	}




	/**
	 * delivery_method function.
	 *
	 * add the delivery method to the options
	 *
	 * @access public
	 * @param mixed $delivery_methods
	 * @return void
	 */
	public function delivery_method( $delivery_methods ) {
		$delivery_methods[ MYMAIL_SENDGRID_ID ] = 'SendGrid';
		return $delivery_methods;
	}


	/**
	 * deliverytab function.
	 *
	 * the content of the tab for the options
	 *
	 * @access public
	 * @return void
	 */
	public function deliverytab() {

		$verified = mymail_option( MYMAIL_SENDGRID_ID . '_verified' );

?>
		<table class="form-table">
			<?php if ( ! $verified ) { ?>
			<tr valign="top">
				<th scope="row">&nbsp;</th>
				<td><p class="description"><?php echo sprintf( __( 'You need a %s account to use this service!', 'mymail-sendgrid' ), '<a href="http://mbsy.co/sendgrid/63320" class="external">SendGrid</a>' ); ?></p>
				</td>
			</tr>
			<?php }?>
			<tr valign="top">
				<th scope="row"><?php _e( 'SendGrid Username' , 'mymail-sendgrid' ) ?></th>
				<td><input type="text" name="mymail_options[<?php echo MYMAIL_SENDGRID_ID ?>_user]" value="<?php echo esc_attr( mymail_option( MYMAIL_SENDGRID_ID . '_user' ) ); ?>" class="regular-text"></td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php _e( 'SendGrid Password' , 'mymail-sendgrid' ) ?></th>
				<td><input type="password" name="mymail_options[<?php echo MYMAIL_SENDGRID_ID ?>_pwd]" value="<?php echo esc_attr( mymail_option( MYMAIL_SENDGRID_ID . '_pwd' ) ); ?>" class="regular-text" autocomplete="new-password"></td>
			</tr>
			<tr valign="top">
				<th scope="row">&nbsp;</th>
				<td>
					<?php if ( $verified ) : ?>
					<span style="color:#3AB61B">&#10004;</span> <?php esc_html_e( 'Your credentials are ok!', 'mymail-sendgrid' ) ?>
					<?php else : ?>
					<span style="color:#D54E21">&#10006;</span> <?php esc_html_e( 'Your credentials are WRONG!', 'mymail-sendgrid' ) ?>
					<?php endif; ?>

					<input type="hidden" name="mymail_options[<?php echo MYMAIL_SENDGRID_ID ?>_verified]" value="<?php echo $verified ?>">
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php _e( 'Send Emails with' , 'mymail-sendgrid' ) ?></th>
				<td>
				<select name="mymail_options[<?php echo MYMAIL_SENDGRID_ID ?>_api]">
					<option value="web" <?php selected( mymail_option( MYMAIL_SENDGRID_ID . '_api' ), 'web' )?>>WEB API</option>
					<option value="smtp" <?php selected( mymail_option( MYMAIL_SENDGRID_ID . '_api' ), 'smtp' )?>>SMTP API</option>
				</select>
				<?php if ( mymail_option( MYMAIL_SENDGRID_ID . '_api' ) == 'web' ) : ?>
				<span class="description"><?php _e( 'embedded images are not working with the web API!', 'mymail-sendgrid' ); ?></span>
				<?php endif; ?>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php _e( 'Categories' , 'mymail-sendgrid' ) ?></th>
				<td><input type="text" name="mymail_options[<?php echo MYMAIL_SENDGRID_ID ?>_categories]" value="<?php echo esc_attr( mymail_option( MYMAIL_SENDGRID_ID . '_categories' ) ); ?>" class="large-text">
				<p class="howto"><?php echo sprintf( __( 'Define up to 10 %s, separated with commas which get send via SendGrid X-SMTPAPI' , 'mymail-sendgrid' ), '<a href="https://sendgrid.com/docs/API_Reference/SMTP_API/categories.html" class="external">' . __( 'Categories', 'mymail-sendgrid' ) . '</a>' ) ?></p>
			</td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php _e( 'Secure Connection' , 'mymail-sendgrid' ) ?></th>
				<td><label><input type="hidden" name="mymail_options[<?php echo MYMAIL_SENDGRID_ID ?>_secure]" value=""><input type="checkbox" name="mymail_options[<?php echo MYMAIL_SENDGRID_ID ?>_secure]" value="1" <?php checked( mymail_option( MYMAIL_SENDGRID_ID . '_secure' ), true )?>> <?php _e( 'use secure connection', 'mymail-sendgrid' ); ?></label></td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php _e( 'Bounce Handling via' , 'mymail-sendgrid' ) ?></th>
				<td>
				<select name="mymail_options[<?php echo MYMAIL_SENDGRID_ID ?>_bouncehandling]">
					<option value="sendgrid" <?php selected( mymail_option( MYMAIL_SENDGRID_ID . '_bouncehandling' ), 'sendgrid' )?>>SendGrid</option>
					<option value="mymail" <?php selected( mymail_option( MYMAIL_SENDGRID_ID . '_bouncehandling' ), 'mymail' )?>>MyMail</option>
				</select> <span class="description"><?php _e( 'MyMail cannot handle bounces when the WEB API is used' , 'mymail-sendgrid' ) ?></span>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php _e( 'DKIM' , 'mymail-sendgrid' ) ?></th>
				<td><p class="howto"><?php _e( 'Set the domain to "sendgrid.me" on the "Apps" page at SendGrid.com (default)' , 'mymail-sendgrid' ) ?></p></td>
			</tr>
		</table>

	<?php

	}


	/**
	 * verify_options function.
	 *
	 * some verification if options are saved
	 *
	 * @access public
	 * @param unknown $user (optional)
	 * @param unknown $pwd  (optional)
	 * @return void
	 */
	public function verify( $user = '', $pwd = '' ) {

		$url = ( mymail_option( MYMAIL_SENDGRID_ID . '_secure' ) ? 'https' : 'http' ) . '://api.sendgrid.com/api/profile.get.json';

		if ( ! $user ) {
			$user = mymail_option( MYMAIL_SENDGRID_ID . '_user' );
		}
		if ( ! $pwd ) {
			$pwd = mymail_option( MYMAIL_SENDGRID_ID . '_pwd' );
		}

		$data = array(
			'api_user' => $user,
			'api_key' => $pwd,
		);

		$response = wp_remote_get( add_query_arg( $data, $url ), array(
			'timeout' => 5,
		) );

		$response = wp_remote_retrieve_body( $response );
		$response = json_decode( $response );

		if ( isset( $response->error ) ) {
			return false;
		} elseif ( isset( $response[0]->username ) ) {
			return true;
		}

		return false;
	}



	/**
	 * verify_options function.
	 *
	 * some verification if options are saved
	 *
	 * @access public
	 * @param mixed $options
	 * @return void
	 */
	public function verify_options( $options ) {

		if ( $timestamp = wp_next_scheduled( 'mymail_sendgrid_cron' ) ) {
			wp_unschedule_event( $timestamp, 'mymail_sendgrid_cron' );
		}

		if ( $options['deliverymethod'] == MYMAIL_SENDGRID_ID ) {

			$old_user = mymail_option( MYMAIL_SENDGRID_ID . '_user' );
			$old_pwd = mymail_option( MYMAIL_SENDGRID_ID . '_pwd' );

			if ( $old_user != $options[ MYMAIL_SENDGRID_ID . '_user' ]
				|| $old_pwd != $options[ MYMAIL_SENDGRID_ID . '_pwd' ]
				|| ! mymail_option( MYMAIL_SENDGRID_ID . '_verified' ) ) {

				$options[ MYMAIL_SENDGRID_ID . '_verified' ] = $this->verify( $options[ MYMAIL_SENDGRID_ID . '_user' ], $options[ MYMAIL_SENDGRID_ID . '_pwd' ] );

				if ( $options[ MYMAIL_SENDGRID_ID . '_verified' ] ) {
					add_settings_error( 'mymail_options', 'mymail_options', sprintf( __( 'Please update your sending limits! %s', 'mymail-sendgrid' ), '<a href="http://sendgrid.com/account/overview" class="external">SendGrid Dashboard</a>' ) );

				}
			}

			if ( ! wp_next_scheduled( 'mymail_sendgrid_cron' ) ) {
				// reset on 00:00 PST ( GMT -8 ) == GMT +16
				$timeoffset = strtotime( 'midnight' ) + ( ( 24 -8 ) * HOUR_IN_SECONDS );
				if ( $timeoffset < time() ) { $timeoffset + ( 24 * HOUR_IN_SECONDS );
				}
				wp_schedule_event( $timeoffset, 'daily', 'mymail_sendgrid_cron' );
			}

			if ( $options[ MYMAIL_SENDGRID_ID . '_api' ] == 'smtp' ) {
				if ( function_exists( 'fsockopen' ) ) {
					$host = 'smtp.sendgrid.net';
					$port = $options[ MYMAIL_SENDGRID_ID . '_secure' ] ? 465 : 587;
					$conn = fsockopen( $host, $port, $errno, $errstr, 15 );

					if ( is_resource( $conn ) ) {

						fclose( $conn );

					} else {

						add_settings_error( 'mymail_options', 'mymail_options', sprintf( __( 'Not able to use SendGrid with SMTP API cause of the blocked port %s! Please send with the WEB API or choose a different delivery method!', 'mymail-sendgrid' ), $port ) );

					}
				}
			} else {

				if ( $options[ MYMAIL_SENDGRID_ID . '_bouncehandling' ] == 'mymail' ) {
					add_settings_error( 'mymail_options', 'mymail_options', __( 'It is currently not possible to handle bounces with MyMail when using the WEB API', 'mymail-sendgrid' ) );
					$options[ MYMAIL_SENDGRID_ID . '_bouncehandling' ] = 'sendgird';
				}
			}

			if ( $options[ MYMAIL_SENDGRID_ID . '_bouncehandling' ] == 'mymail' ) {
				add_settings_error( 'mymail_options', 'mymail_options', sprintf( __( 'Please make sure your SendGrid Account "preserve headers" otherwise MyMail is not able to handle bounces', 'mymail-sendgrid' ), $port ) );
			}
		}

		return $options;
	}


	/**
	 * check_bounces function.
	 *
	 * checks for bounces and reset them if needed
	 *
	 * @access public
	 * @return void
	 */
	public function check_bounces() {

		if ( get_transient( 'mymail_check_bounces_lock' ) || mymail_option( MYMAIL_SENDGRID_ID . '_bouncehandling' ) == 'mymail' ) {
			return false;
		}

		// check bounces only every five minutes
		set_transient( 'mymail_check_bounces_lock', true, mymail_option( 'bounce_check', 5 ) * 60 );

		$collection = array();

		$response = $this->do_call( 'bounces.get', array( 'date' => 1, 'limit' => 200 ) );

		if ( is_wp_error( $response ) ) {
			return false;
		}

		$collection['bounces'] = (array) $response->body;

		$response = $this->do_call( 'blocks.get', array( 'date' => 1, 'limit' => 200 ) );

		if ( is_wp_error( $response ) ) {
			return false;
		}

		$collection['blocks'] = (array) $response->body;

		$response = $this->do_call( 'spamreports.get', array( 'date' => 1, 'limit' => 200 ) );

		if ( is_wp_error( $response ) ) {
			return false;
		}

		$collection['spamreports'] = (array) $response->body;

		$response = $this->do_call( 'unsubscribes.get', array( 'date' => 1, 'limit' => 200 ) );

		if ( is_wp_error( $response ) ) {
			return false;
		}

		$collection['unsubscribes'] = (array) $response->body;

		foreach ( $collection as $type => $messages ) {

			foreach ( $messages as $message ) {

				$subscriber = mymail( 'subscribers' )->get_by_mail( $message->email );

				// only if user exists
				if ( $subscriber ) {

					$reseted = false;
					$campaigns = mymail( 'subscribers' )->get_sent_campaigns( $subscriber->ID );

					if ( $type == 'unsubscribes' ) {

						if ( mymail( 'subscribers' )->unsubscribe( $subscriber->ID, isset( $campaigns[0] ) ? $campaigns[0]->campaign_id : null ) ) {
							$response = $this->do_call( $type . '.delete', array( 'email' => $message->email ), true );
							$reseted = isset( $response->message ) && $response->message == 'success';
						}
					} else {

						// any code with 5 eg 5.x.x or a spamreport
						$is_hard_bounce = $type == 'spamreports' || substr( $message->status, 0, 1 ) == 5;

						foreach ( $campaigns as $i => $campaign ) {

							// only the last 10 campaigns
							if ( $i >= 10 ) { break;
							}

							if ( mymail( 'subscribers' )->bounce( $subscriber->ID, $campaign->campaign_id, $is_hard_bounce ) ) {
								$response = $this->do_call( $type . '.delete', array( 'email' => $message->email ), true );
								$reseted = isset( $response->message ) && $response->message == 'success';
							}
						}
					}

					if ( ! $reseted ) {
						$response = $this->do_call( $type . '.delete', array( 'email' => $message->email ), true );
						$reseted = isset( $response->message ) && $response->message == 'success';
					}
				} else {
					// remove user from the list
					$response = $this->do_call( $type . '.delete', array( 'email' => $message->email ), true );
					$count++;
				}
			}
		}

	}


	/**
	 * do_call function.
	 *
	 * makes a request to the sendgrid endpoint and returns the result
	 *
	 * @access public
	 * @param mixed $path
	 * @param array $data     (optional) (default: array())
	 * @param bool  $bodyonly (optional) (default: false)
	 * @param int   $timeout  (optional) (default: 5)
	 * @return void
	 */
	public function do_call( $path, $data = array(), $bodyonly = false, $timeout = 5 ) {

		$url = ( ! mymail_option( MYMAIL_SENDGRID_ID . '_secure' ) ? 'http' : 'https' ) . '://api.sendgrid.com/api/' . $path . '.json';
		if ( is_bool( $data ) ) {
			$bodyonly = $data;
			$data = array();
		}

		$user = mymail_option( MYMAIL_SENDGRID_ID . '_user' );
		$pwd = mymail_option( MYMAIL_SENDGRID_ID . '_pwd' );

		if ( $path == 'mail.send' ) {

			$url = add_query_arg( array(
					'api_user' => $user,
					'api_key' => $pwd,
			), $url );

			$response = wp_remote_post( $url, array(
					'timeout' => $timeout,
					'body' => $data,
			) );

		} else {

			$data = wp_parse_args( $data, array(
					'api_user' => $user,
					'api_key' => $pwd,
			) );

			$response = wp_remote_get( add_query_arg( $data, $url ), array(
					'timeout' => $timeout,
			) );

		}

		if ( is_wp_error( $response ) ) {

			return $response;

		}

		$code = wp_remote_retrieve_response_code( $response );
		$body = json_decode( wp_remote_retrieve_body( $response ) );

		if ( $code != 200 ) {
			return new WP_Error( $code, $body->message );
		}

		if ( $bodyonly ) {
			return $body;
		}

		return (object) array(
			'code' => $code,
			'headers' => wp_remote_retrieve_headers( $response ),
			'body' => $body,
		);

	}


	/**
	 * section_tab_bounce function.
	 *
	 * displays a note on the bounce tab (MyMail >= 1.6.2)
	 *
	 * @access public
	 * @param mixed $options
	 * @return void
	 */
	public function section_tab_bounce() {

		if ( mymail_option( MYMAIL_SENDGRID_ID . '_bouncehandling' ) == 'mymail' ) {
			return;
		}

?>
		<div class="error inline"><p><strong><?php _e( 'Bouncing is handled by SendGrid so all your settings will be ignored', 'mymail-sendgrid' ); ?></strong></p></div>

	<?php
	}



	/**
	 * Notice if MyMail is not available
	 *
	 * @access public
	 * @return void
	 */
	public function notice() {
?>
	<div id="message" class="error">
	  <p>
	   <strong>SendGrid integration for MyMail</strong> requires the <a href="http://rxa.li/mymail?utm_campaign=wporg&utm_source=SendGrid+integration+for+MyMail">MyMail Newsletter Plugin</a>, at least version <strong><?php echo MYMAIL_SENDGRID_REQUIRED_VERSION ?></strong>.
	  </p>
	</div>
		<?php
	}



	/**
	 * activate function
	 *
	 * @access public
	 * @return void
	 */
	public function activate() {

		if ( function_exists( 'mymail' ) ) {

			mymail_notice( sprintf( __( 'Change the delivery method on the %s!', 'mymail-sendgrid' ), '<a href="options-general.php?page=newsletter-settings&mymail_remove_notice=delivery_method#delivery">Settings Page</a>' ), '', false, 'delivery_method' );

			$this->reset();
		}
	}


	/**
	 * deactivate function
	 *
	 * @access public
	 * @return void
	 */
	public function deactivate() {
		if ( function_exists( 'mymail' ) ) {
			if ( mymail_option( 'deliverymethod' ) == MYMAIL_SENDGRID_ID ) {
				mymail_update_option( 'deliverymethod', 'simple' );
				mymail_notice( sprintf( __( 'Change the delivery method on the %s!', 'mymail-sendgrid' ), '<a href="options-general.php?page=newsletter-settings&mymail_remove_notice=delivery_method#delivery">Settings Page</a>' ), '', false, 'delivery_method' );
			}
		}
	}


}


new MyMailSendGird();
