<?php


/**
 * A class to ease timezone managment for users in an applications
 *
 * @author Ghalib Soomro <ghalibsoomro@gmail.com>
 * @website http://www.ghalibsoomro.com/
 * @phone/whatsapp, 03337348108/
 */
class UserTimeZone
{

	/**
	 * Time format to be used
	 *
	 * @var string
	 */
	protected $format = null;

	/**
	 * Timezone to be used
	 *
	 * @var string
	 */
	protected $defaultTimeZone = null;

	/**
	 * @param string $format
	 * @param string $defaultTimeZone
	 */
	public function __construct($format = 'Y-m-d h:i:s P', $defaultTimeZone = 'UTC')
	{
		$this->format = $format;
		$this->defaultTimeZone = $defaultTimeZone;
	}

	/**
	 * Returns all timezones in an array.
	 * Can be used to construct a dropdown of all timezones
	 * for users to select
	 *
	 * @return array
	 */
	public function getTimeZones()
	{
		return DateTimeZone::listIdentifiers();
	}

	/**
	 * Sets dates in GMT format.
	 * Should be used when saving dates in database for example.
	 *
	 * @param $date
	 * @return string
	 */
	public function setDate($date)
	{
		$date = new DateTime($date, new DateTimeZone($this->defaultTimeZone));
		$date->setTimezone(new DateTimeZone('GMT'));

		return $date->format($this->format);
	}

	/**
	 * Gets data based on users' timezones.
	 * Should be used when showing dates in pages for example.
	 *
	 * @param $date
	 * @return string
	 */
	public function getDate($date)
	{
		$date = new DateTime($date, new DateTimeZone('GMT'));
		$date->setTimezone(new DateTimeZone($this->defaultTimeZone));

		return $date->format($this->format);
	}
}
