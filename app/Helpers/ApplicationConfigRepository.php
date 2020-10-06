<?php


namespace App\Helpers;


use App\Parameter;
use Illuminate\Support\Arr;

class ApplicationConfigRepository implements \ArrayAccess
{
    /**
     * All of the configuration items.
     *
     * @var array
     */
    protected $items = [];

    /**
     * Determine if the given configuration value exists.
     *
     * @param string $key
     * @return bool
     */
    public function has(string $key)
    {
        return Arr::has($this->items, $key);
    }

    /**
     * Get the specified configuration value.
     *
     * @param string $key
     * @return mixed
     * @throws \Exception
     */
    public function get(string $key)
    {
        if(!$this->has($key)) {
            if($item = $this->getFromDb($key)) {
                $this->set($key, $item);
            } else {
                throw new \Exception("Missing parameter for {$key}");
            }
        }

        return Arr::get($this->items, $key);
    }

    private function getFromDb($key)
    {
        $parameter = Parameter::getByName($key);

        if(!$parameter) {
            return false;
        }

        return $parameter->value;
    }

    /**
     * Set a given configuration value.
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function set(string $key, $value = null)
    {
        Arr::set($this->items, $key, $value);
    }

    /**
     * Determine if the given configuration option exists.
     *
     * @param  string  $key
     * @return bool
     */
    public function offsetExists($key)
    {
        return $this->has($key);
    }

    /**
     * Get a configuration option.
     *
     * @param  string  $key
     * @return mixed
     */
    public function offsetGet($key)
    {
        return $this->get($key);
    }

    /**
     * Set a configuration option.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return void
     */
    public function offsetSet($key, $value)
    {
        $this->set($key, $value);
    }

    /**
     * Unset a configuration option.
     *
     * @param  string  $key
     * @return void
     */
    public function offsetUnset($key)
    {
        $this->set($key, null);
    }
}
