<?php

class Time {


	public static function convert($string)
	{

		$tz = new DateTimeZone( Config::get('application.timezone', 'UTC') );

		if ($string instanceof DateTime) {
			$string->setTimeZone($tz);
			return $string;
		}
		return new DateTime($string, $tz);
	}


	public static function interval($date1, $date2='now')
	{
		$date1 = static::convert($date1);
		$date2 = static::convert($date2);
		return $date1->diff($date2);
	}


	public static function ago($date1, $date2='now')
	{
		$interval = static::interval($date1,$date2);

		$when = $interval->invert ? 'from now' : 'ago';
		$days = intval( $interval->format('%a') );

		if ($interval->m > 18) {
			$num = $interval->y;
			$units = 'year';
		} else if ($days > 45 ) {
			$num = $interval->m;
			$units = 'month';
		} else if ($days > 10) {
			$num = round($days/7);
			$units = 'week';
		} else if ($days > 1) {
			$num = $days;
			$units = 'day';
		} else if ($days || $interval->h) {
			$num = $interval->h + (24*$days);
			$units = 'hour';
		} else if ($interval->i) {
			$num = $interval->i;
			$units = 'minute';
		} else {
			return 'moments ' . $when;
		}

		return $num . ' ' . Str::plural($units, $num) . ' ' . $when;

	}

}
