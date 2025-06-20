<?php

namespace Winter\Builder\Classes;

use Doctrine\DBAL\Schema\Column;
use Doctrine\Deprecations\Deprecation;

class WinterColumn
{
    protected bool $_unique = false;
    protected Column $_column;
    public function __construct(
        Column $column,
        array $options,
    ) {
        $this->_column = $column;
        $this->setOptions($options);
    }

    public function __call(string $name, array $arguments)
    {
        return $this->_column->$name(...$arguments);
    }

    /**
     * @return bool
     */
    public function getUnique()
    {
        return $this->_unique;
    }

    /**
     * @param bool $unique
     *
     * @return WinterColumn
     */
    public function setUnique($unique)
    {
        $this->_unique = (bool) $unique;
        return $this;
    }

    /**
     * @param mixed[] $options
     *
     * @return Column
     */
    public function setOptions(array $options)
    {
        foreach ($options as $name => $value) {
            $method = 'set' . $name;
            if (! method_exists($this, $method)) {
                // next major: throw an exception
                Deprecation::trigger(
                    'doctrine/dbal',
                    'https://github.com/doctrine/dbal/pull/2846',
                    'The "%s" column option is not supported,' .
                        ' setting unknown options is deprecated and will cause an error in Doctrine DBAL 3.0',
                    $name
                );

                continue;
            }

            $this->$method($value);
        }

        return $this;
    }
}
