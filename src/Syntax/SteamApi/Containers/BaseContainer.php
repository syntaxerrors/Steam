<?php

namespace Syntax\SteamApi\Containers;

use Illuminate\Support\Collection;

abstract class BaseContainer
{
    /**
     * @param        $app
     * @param string $field
     * @param mixed|null $value
     * @return mixed
     */
    protected function checkIsNullField($app, string $field, mixed $value = null): mixed
    {
        return ! is_null($app->$field) ? $app->$field : $value;
    }

    /**
     * @param        $app
     * @param string $field
     *
     * @return mixed
     */
    protected function checkIssetField($app, string $field, mixed $value = null): mixed
    {
        return $app->$field ?? $value;
    }

    /**
     * @param        $app
     * @param string $field
     * @param mixed|null $value
     * @return mixed
     */
    protected function checkIssetCollection($app, string $field, mixed $value = null): mixed
    {
        return isset($app->$field) ? new Collection($app->$field) : $value;
    }

    /**
     * @param string $image
     *
     * @return string
     */
    protected function getImageForAvatar(string $image): string
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
    protected function pluralize($word, $count): string
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
    protected function convertFromMinutes($minutes): string
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
