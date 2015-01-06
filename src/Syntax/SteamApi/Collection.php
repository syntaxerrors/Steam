<?php namespace Syntax\SteamApi;

class Collection extends \Illuminate\Database\Eloquent\Collection {

	/**
	 * Dynamically retrieve attributes on the model.
	 *
	 * @param  string  $key
	 * @return mixed
	 */
	public function __get($key)
	{
		$newCollection = new Collection();
		foreach ($this->items as $item) {
			if ($item instanceof Collection) {
				foreach ($item as $subItem) {
					$newCollection->put($newCollection->count(), $subItem->$key);
				}
			}
			elseif (is_object($item) && !$item instanceof Collection && $item->$key instanceof Collection) {
				foreach ($item->$key as $subItem) {
					$newCollection->put($newCollection->count(), $subItem);
				}
			}
			else {
				$newCollection->put($newCollection->count(), $item->$key);
			}
		}
		return $newCollection;
	}

	/**
	 * Allow a method to be run on the enitre collection.
	 *
	 * @param string $method
	 * @param array $args
	 * @return Collection
	 */
	public function __call($method, $args)
	{
		if ($this->count() <= 0) {
			return $this;
		}

		foreach ($this->items as $item) {
			if (!is_object($item)) {
				continue;
			}
			call_user_func_array(array($item, $method), $args);
		}

		return $this;
	}

	/**
	 * Insert into an object
	 *
	 * @param mixed	$value
	 * @param int 	$afterKey
	 *
	 * @return Collection
	 */
	public function insertAfter($value, $afterKey)
	{
		$new_object = new Collection();

		foreach ((array) $this->items as $k => $v) {
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
	 */
	public function where($column, $value = null)
	{
		foreach ($this->items as $key => $item) {

			if (strstr($column, '->')) {
				$taps = explode('->', $column);

				$objectToSearch = $item;
				foreach ($taps as $tapKey => $tap) {

					// Keep tapping till we hit the last object.
					$objectToSearch = $objectToSearch->$tap;
				}

				if ($objectToSearch instanceof Collection) {
					if (!in_array($value, $objectToSearch->toArray())) {
						$this->forget($key);
						continue;
					}
				}
				else {
					if ($objectToSearch != $value) {
						$this->forget($key);
						continue;
					}
				}
			} else {
				if (!$item->$column) {
					$this->forget($key);
					continue;
				}

				if ($item->$column != $value) {
					$this->forget($key);
					continue;
				}
			}
		}

		return $this;
	}
}