<?php

namespace Winter\Builder\Classes;

use Doctrine\DBAL\Schema\Column;
use Doctrine\DBAL\Schema\SchemaException;
use Doctrine\DBAL\Schema\Table;
use Doctrine\DBAL\Types\Type;

class WinterTable extends Table
{

    /**
     * @param string  $name
     * @param string  $typeName
     * @param mixed[] $options
     *
     * @return WinterColumn
     */
    public function addColumn($name, $typeName, array $options = [])
    {
        // NOTE: Column does not cover all the use cases so we use a decorator
        $column = new WinterColumn(
            new Column(
                $name,
                Type::getType($typeName),
                $options
            ),
            $options
        );

        $this->_addExtendedColumn($column);

        return $column;
    }

    /**
     * @return void
     *
     * @throws SchemaException
     */
    protected function _addExtendedColumn(WinterColumn $column)
    {
        $columnName = $column->getName();
        $columnName = $this->normalizeIdentifier($columnName);

        if (isset($this->_columns[$columnName])) {
            throw SchemaException::columnAlreadyExists($this->getName(), $columnName);
        }

        $this->_columns[$columnName] = $column;
    }

    /**
     * Normalizes a given identifier.
     *
     * Trims quotes and lowercases the given identifier.
     *
     * @param string|null $identifier The identifier to normalize.
     *
     * @return string The normalized identifier.
     */
    protected function normalizeIdentifier($identifier)
    {
        if ($identifier === null) {
            return '';
        }

        return $this->trimQuotes(strtolower($identifier));
    }
}
