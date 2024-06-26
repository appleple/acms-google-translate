<?php

namespace Acms\Plugins\GoogleTranslate\Contracts;

#[\AllowDynamicProperties]
abstract class Model
{
    /**
     * @var bool
     */
    protected $update = false;

    /**
     * @var array
     */
    protected $columns = [];

    /**
     * Model constructor.
     */
    public function __construct()
    {
        $this->columns = $this->getColumns();
        foreach ($this->columns as $column) {
            $this->{$column} = null;
        }
    }

    /**
     * @static
     * @param int $id
     * @return self|false
     */
    public static function find($id)
    {
        throw new \RuntimeException('Model does not defined find method.');
    }

    /**
     * Get columns
     *
     * @return string[]
     */
    abstract protected function getColumns();

    /**
     * Initialize model
     *
     * @return void
     */
    abstract public function init();

    /**
     * Load model
     *
     * @param array $item
     * @return void
     */
    abstract public function load($item);

    /**
     * Update or Insert entry
     *
     * @return void
     */
    abstract public function save();

    /**
     * Delete entry
     *
     * @return void
     */
    abstract public function delete();

    /**
     * Getter
     *
     * @param string $key
     * @return mixed
     */
    public function __get($key)
    {
        $getter = 'get' . ucfirst($key);

        if (method_exists($this, $getter)) {
            return call_user_func([$this, $getter]);
        } elseif (property_exists($this, $key)) {
            return $this->{$key};
        }
        throw new \RuntimeException("Property \"$key\" does not exist.");
    }

    /**
     * Setter
     *
     * @param string $key
     * @param mixed $value
     */
    public function __set($key, $value)
    {
        $setter = 'set' . ucfirst($key);

        if (method_exists($this, $setter)) {
            return call_user_func([$this, $setter], $value);
        }
        $this->{$key} = $value;
    }
}
