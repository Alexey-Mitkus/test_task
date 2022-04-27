<?php

namespace App\Libraries\Date;

use Illuminate\Support\Str;
use Carbon\Carbon;

class DateFormat
{
	public static function post($time)
	{
		$timestamp = strtotime(Carbon::parse($time)->toDateTimeString());
		$published = date('d.m.Y', $timestamp);
		if( $published === date('d.m.Y') ){
			return trans('date.today', ['time' => date('H:i', $timestamp)]);
		} elseif( $published === date('d.m.Y', strtotime('-1 day')) ){
			return trans('date.yesterday', ['time' => date('H:i', $timestamp)]);
		} else {
			$formatted = trans('date.later', [
				'time' => date('H:i', $timestamp),
				'date' => date('d F' . (date('Y', $timestamp) === date('Y') ? null : ' Y'), $timestamp)
			]);
			return strtr(Str::lower($formatted), trans('date.month_declensions'));
		}
	}

    public function secondsToString($secs)
    {
        $collection = collect([]);
        $days = floor($secs / 86400);
        $secs = $secs % 86400;

        if( $days > 0 ){
            $collection->push(\morphos\Russian\pluralize($days, 'день'));
        }

        $hours = floor($secs / 3600);
        $secs = $secs % 3600;

        if( $hours > 0 ){
            $collection->push(\morphos\Russian\pluralize($hours, 'час'));
        }

        $minutes = floor($secs / 60);
        $secs = $secs % 60;

        if( $minutes > 0 ){
            $collection->push(\morphos\Russian\pluralize($minutes, 'минута'));
        }
        if( $secs > 0 ){
            $collection->push(\morphos\Russian\pluralize($secs, 'секунда'));
        }
        return $collection->implode(', ');
    }
}