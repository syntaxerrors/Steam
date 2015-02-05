<?php namespace Syntax\SteamApi;

class Collection extends \Illuminate\Database\Eloquent\Collection {

    /**
     * Dynamically retrieve attributes on the model.
     *
     * @param  string $key
     *
     * @return Collection
     */
    public function __get($key)
    {
        $newCollection = new Collection();
        foreach ($this->items as $item) {
            if ($item instanceof Collection) {
                foreach ($item as $subItem) {
                    $newCollection->put($newCollection->count(), $subItem->$key);
                }
            } elseif (is_object($item) && ! $item instanceof Collection && $item->$key instanceof Collection) {
                foreach ($item->$key as $subItem) {
                    $newCollection->put($newCollection->count(), $subItem);
                }
            } else {
                $newCollection->put($newCollection->count(), $item->$key);
            }
        }

        return $newCollection;
    }

    /**
     * Allow a method to be run on the enitre collection.
     *
     * @param string $method
     * @param array  $args
     *
     * @return Collection
     */
    public function __call($method, $args)
    {
        if ($this->count() <= 0) {
            return $this;
        }

        foreach ($this->items as $item) {
            if (! is_object($item)) {
                continue;
            }
            call_user_func_array([$item, $method], $args);
        }

        return $this;
    }

    /**
     * Insert into an object
     *
     * @param mixed $value
     * @param int   $afterKey
     *
     * @return Collection
     */
    public function insertAfter($value, $afterKey)
    {
        $new_object = new Collection();

        foreach ((array)$this->items as $k => $v) {
            if ($afterKey == $k) {
                $new_object->add($value);
            }

            $new_object->add($v);
        }

        $this->items = $new_object->items;

        return $this;
    }

    /**
     * Find item in a collection
     *
     * @param string      $column
     * @param string|null $value
     *
     * @return $this
     */
    public function where($column, $value = null)
    {
        foreach ($this->items as $key => $item) {

            if (strstr($column, '->')) {
                if ($this->handleMultiTap($key, $item, $column, $value)) {
                    continue;
                }
            } else {
                if ($this->handleSingle($key, $item, $column, $value)) {
                    continue;
                }
            }
        }

        return $this;
    }

    /**
     * @param $key
     * @param $item
     * @param $column
     * @param $value
     *
     * @return bool
     */
    private function handleMultiTap($key, $item, $column, $value)
    {
        $objectToSearch = $this->tapThroughObjects($column, $item);

        if ($this->removeCollectionItem($key, $value, $objectToSearch)) {
            return true;
        }

        if ($this->removeItem($key, $value, $objectToSearch)) {
            return true;
        }

        return false;
    }

    /**
     * @param $key
     * @param $item
     * @param $column
     * @param $value
     *
     * @return bool
     */
    private function handleSingle($key, $item, $column, $value)
    {
        if (! $item->$column) {
            $this->forget($key);
            return true;
        }

        if ($this->removeItem($key, $value, $item->$column)) {
            return true;
        }

        return false;
    }

    /**
     * @param $column
     * @param $item
     *
     * @return mixed
     */
    private function tapThroughObjects($column, $item)
    {
        $taps = explode('->', $column);

        $objectToSearch = $item;
        foreach ($taps as $tapKey => $tap) {

            // Keep tapping till we hit the last object.
            $objectToSearch = $objectToSearch->$tap;
        }

        return $objectToSearch;
    }

    /**
     * @param $key
     * @param $value
     * @param $objectToSearch
     *
     * @return bool
     */
    private function removeCollectionItem($key, $value, $objectToSearch)
    {
        if ($objectToSearch instanceof Collection) {
            if (! in_array($value, $objectToSearch->toArray())) {
                $this->forget($key);
                return true;
            }
        }

        return false;
    }

    /**
     * @param $key
     * @param $value
     * @param $objectToSearch
     *
     * @return bool
     */
    private function removeItem($key, $value, $objectToSearch)
    {
        if ($objectToSearch != $value) {
            $this->forget($key);
            return true;
        }

        return false;
    }
}