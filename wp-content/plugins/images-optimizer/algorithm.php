<?php

// Restricting Direct Access
defined('ABSPATH') or die(require_once('404.php'));

/*
 * ALGORITHM
 * */


class AB{

	/*
	 * STRUCTURE of $data
	 *
	 * array(
	 * 	   'alpha-image' => array(
	 * 			'impressions' => '', // int : arbitary number
	 *  		'clicks' => '', // int : arbitary number
	 * 		),
	 * 	   'beta-image' => array(
	 * 			'impressions' => '', // int : arbitary number
	 *  		'clicks' => '', // int : arbitary number
	 * 		),
	 *     'view' => array(
	 * 			'alpha' => '', // int : arbitary number
	 * 			'beta' => '', // int: arbitary number
	 * 		)
	 * );
	 * */
	protected $data, // This variable has all the data that is necessary for the algo to operate.
		$unit; // Stores the unit number.

	private $a_impressions, // Impressions of alpah image
		$a_clicks, // Clicks of alpha image
		$b_impressions, // Impressions of beta image
		$b_clicks; // Clicks of beta image

	/*
	 * @constructor function
	 * @params :: $data [array], $unit [arbitary number]
	 * @return :: none
	 *
	 * */
	public function __construct($data, $unit){

		$this->data = $data;

		$this->unit = $unit;

		$this->a_impressions = $this->data['alpha-image']['impressions'];
		$this->a_clicks = $this->data['alpha-image']['clicks'];

		$this->b_impressions = $this->data['beta-image']['impressions'];
		$this->b_clicks = $this->data['beta-image']['clicks'];

	}


	/*
	 * @initial_state
	 * @params :: void
	 * @return :: alpha/beta/false [arbitary string]
	 *
	 * This function cheacks and see that if the initaial state is over.
	 * If not then it checks the impressions and returns an image.
	 *
	 * */
	protected function initial_state(){

		if( $this->a_impressions <= $this->unit || $this->b_impressions <= $this->unit ){
			if($this->a_impressions <= $this->b_impressions){

				$this->data['alpha-image']['impressions']++;
				return 'alpha';

			} else {

				$this->data['beta-image']['impressions']++;
				return 'beta';

			}
		} else return false;

	}


	/*
	 * @active_state
	 * @params :: void
	 * @return :: alpha/beta [arbitary string]
	 *
	 * This function executes when the initial_state is done and the
	 * algorithm is active.
	 *
	 * */
	protected function active_state(){

		$total_impressions = $this->a_impressions + $this->b_impressions;

		// if it has to set the view ratio
		if( $total_impressions % $this->unit == 0 ){

			$a_crt = ($this->a_clicks * 100) / $this->a_impressions;
			$b_crt = ($this->b_clicks * 100) / $this->b_impressions;

			$a_b_ratio = $a_crt / $b_crt;
			$b_a_ratio = $b_crt / $a_crt;

			$total_ratio = $a_b_ratio + $b_a_ratio;

			$view_a = round( ($this->unit / $total_ratio) * $a_b_ratio );
			$view_b = $this->unit - $view_a;

			$this->data['view']['alpha'] = $view_a;
			$this->data['view']['beta'] = $view_b;

		}

		// alpha or beta
		$view_a = $this->data['view']['alpha'];
		$view_b = $this->data['view']['beta'];

		if($view_a >= $view_b) {

			$this->data['alpha-image']['impressions']++;
			$this->data['view']['alpha']--;
			return 'alpha';

		} else {

			$this->data['beta-image']['impressions']++;
			$this->data['view']['beta']--;
			return 'beta';

		}

	}


	/*
	 * @get_image
	 * @params :: void
	 * @return :: alpha/beta [arbitary string]
	 *
	 * This function will be called from outside to get the image.
	 *
	 * */
	public function get_image(){

		// If initial_state is true.
		if ($image = $this->initial_state()) return $image;
			else  // Checks for initial state cross but both of the images are not clicked at least once.
				if ($this->a_clicks == 0 || $this->b_clicks == 0){
					$this->unit += 10;
					return $this->initial_state();
				}


		// If none of the above condition is satisfied then execute the rest.
		return $this->active_state();
	}

	/*
	 * @get_data
	 * @params :: void
	 * @return :: data [array]
	 *
	 * */
	public function get_data(){
		return $this->data;
	}


	/*
	 * @get_unit
	 * @params :: void
	 * @return :: unit [int]
	 *
	 * */
	public function get_unit(){
		return $this->unit;
	}

}

?>
