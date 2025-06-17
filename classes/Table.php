<?php namespace Winter\Builder\Classes;

use function array_filter;
use function array_merge;
use function in_array;
use function preg_match;
use function strlen;
use function strtolower;

use const ARRAY_FILTER_USE_KEY;

/**
 * Object Representation of a table.
 */
class Table extends AbstractAsset
{
    /** @var Column[] */
    protected $_columns = [];

    /**
     * @param Table                  $table
     * @param Column[]               $columns
     * @param Index[]                $indexes
     * @param ForeignKeyConstraint[] $fkConstraints
     * @param int                    $idGeneratorType
     * @param mixed[]                $options
     *
     * @throws Exception
     */
    public function __construct(
        $table,
    ) {
    }

    /**
     * @param string  $name
     * @param string  $typeName
     * @param mixed[] $options
     *
     * @return Column
     */
    public function addColumn($name, $typeName, array $options = [])
    {
        $column = new Column($name, Type::getType($typeName), $options);

        $this->_addColumn($column);

        return $column;
    }
    /**
     * @return void
     *
     * @throws SchemaException
     */
    protected function _addColumn(Column $column)
    {
        $columnName = $column->getName();
        $columnName = $this->normalizeIdentifier($columnName);

        if (isset($this->_columns[$columnName])) {
            throw SchemaException::columnAlreadyExists($this->getName(), $columnName);
        }

        $this->_columns[$columnName] = $column;
    }

}
