<?php

namespace SupportBee;

use SupportBee\Exceptions\ConfigException as ConfigException;
use SupportBee\API\Tickets as Tickets;
use SupportBee\API\Replies as Replies;
use SupportBee\API\Comments as Comments;
use SupportBee\API\Agents as Agents;
use SupportBee\API\Labels as Labels;

/**
 * Class SupportBee
 *
 * @package SupportBee
 */
class SupportBee
{
	const VERSION = '0.0.1';
	const BASE    = 'https://COMPANY.supportbee.com/';

	public static $base_url   = null;
	public static $auth_token = null;
	public static $headers    = array();

	public function __construct( $config = array() )
	{
		$this->validate( $config );

		self::$base_url = str_replace( 'COMPANY', $config['company'], self::BASE);
		self::$auth_token = $config['token'];

		self::$headers = array(
			'Content-Type' => 'application/json',
			'Accept'       => 'application/json'
		);
	}

	/**
	 * Validates the SupportBee configuration options
	 *
	 * @params  array       $config
	 *
	 * @throws  SupportBee\Exceptions\ConfigException    When a config value does not meet its validation criteria
	 */
	private function validate( $config )
	{
		if ( count( $config ) == 0 )
			throw new ConfigException('Auth token and company need to be set.');

		if ( !isset( $config['token'] ) || !ctype_alnum( $config['token'] ) )
			throw new ConfigException('Invalid Token.');

		if ( !isset( $config['company'] ) || !ctype_alnum( $config['company'] ) )
			throw new ConfigException('Invalid Company name.');
	}

	public function tickets( $options = array() )
	{
		return Tickets::tickets( $options );
	}

	public function ticket( $id = 0 )
	{
		return Tickets::get_ticket( $id );
	}

	public function searchTickets( $options = array() )
	{
		if ( !is_array($options) )
			$options = array( 'query' => $options );

		return Tickets::search( $options );
	}

	public function replies( $id = 0 )
	{
		return Replies::replies( $id );
	}

	public function reply( $ticket_id = 0, $reply_id = 0 )
	{
		return Replies::get_reply( $ticket_id, $reply_id );
	}

	public function comments( $id = 0 )
	{
		return Comments::comments( $id );
	}

	public function agents( $options = array() )
	{
		if ( !is_array($options) )
			$options = array( 'with_invited' => $options );

		return Agents::agents( $options );
	}

	public function agent( $id = 0 )
	{
		return Agents::get_agent( $id );
	}

	public function labels()
	{
		return Labels::labels();
	}
}
