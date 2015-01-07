<?php namespace Syntax\SteamApi\Containers;

abstract class BaseContainer {

    /**
     * @param        $app
     * @param string $field
     * @param string $value
     *
     * @return null|string
     */
    protected function checkIsNullField($app, $field, $value = null)
    {
        return !is_null($app->$field) ? $app->$field : $value;
    }

    /**
     * @param        $app
     * @param string $field
     * @param string $value
     *
     * @return null|string
     */
    protected function checkIssetField($app, $field, $value = null)
    {
        return isset($app->$field) ? $app->$field : $value;
    }

    /**
     * @param        $app
     * @param string $field
     * @param string $value
     *
     * @return null|string
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
        return \HTML::image($image);
    }

}