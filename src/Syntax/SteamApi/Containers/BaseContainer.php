<?php

namespace Syntax\SteamApi\Containers;

use NukaCode\Database\Collection;

abstract class BaseContainer
{
    /**
     * @param        $app
     * @param string $field
     * @param mixed  $value
     *
     * @return mixed
     */
    protected function checkIsNullField($app, $field, $value = null)
    {
        return ! is_null($app->$field) ? $app->$field : $value;
    }

    /**
     * @param        $app
     * @param string $field
     * @param mixed  $value
     *
     * @return mixed
     */
    protected function checkIssetField($app, $field, $value = null)
    {
        return isset($app->$field) ? $app->$field : $value;
    }

    /**
     * @param        $app
     * @param string $field
     * @param mixed  $value
     *
     * @return mixed
     */
    protected function checkIssetCollection($app, $field, $value = null)
    {
        return isset($app->$field) ? new Collection($app->$field) : $value;
    }

    /**
     * @param string $image
     *
     * @return string
     */
    protected function getImageForAvatar($image)
    {
        return '<img src="' . $image . '" />';
    }

    /**
     * Very simple pluralize helper for days, hours, minutes.
     * This is not an end all solution to pluralization.
     *
     * @param $word
     * @param $count
     *
     * @return string
     */
    protected function pluralize($word, $count)
    {
        if ((int) $count === 1) {
            return $word .' ';
        }

        return $word .'s ';
    }

    /**
     * Convert a value from pure minutes into something easily digestible.
     *
     * @param $minutes
     *
     * @return string
     */
    protected function convertFromMinutes($minutes)
    {
        $seconds = $minutes * 60;

        $secondsInAMinute = 60;
        $secondsInAnHour  = 60 * $secondsInAMinute;
        $secondsInADay    = 24 * $secondsInAnHour;

        // extract days
        $days = floor($seconds / $secondsInADay);

        // extract hours
        $hourSeconds = $seconds % $secondsInADay;
        $hours       = floor($hourSeconds / $secondsInAnHour);

        // extract minutes
        $minuteSeconds = $hourSeconds % $secondsInAnHour;
        $minutes       = floor($minuteSeconds / $secondsInAMinute);

        // return the final string
        $output = '';

        if ($days > 0) {
            $output .= $days . ' ' . $this->pluralize('day', $days);
        }

        if ($hours > 0) {
            $output .= $hours . ' ' . $this->pluralize('hour', $hours);
        }

        $output .= $minutes . ' ' . $this->pluralize('minute', $minutes);

        return $output;
    }
}
