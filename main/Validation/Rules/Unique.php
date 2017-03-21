<?php

namespace Czech\Validation\Rules;

class Unique extends \Respect\Validation\Rules\AbstractRule
{

    protected $table;
    protected $column;
    protected $container;

    public function __construct($container, $table, $column, $current) {
        $this->table = $table;
        $this->column = $column;
        $this->container = $container;
    }

    public function validate($input)
    {
        return $this->container->db->table($this->table)->where($this->column, $input)->count()  === 0;
    }

}
