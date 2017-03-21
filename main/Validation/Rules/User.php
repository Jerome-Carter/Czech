<?php

namespace Czech\Validation\Rules;

class User extends \Respect\Validation\Rules\AbstractRule
{

    protected $container;

    public function __construct($container) {
        $this->container = $container;
    }

    public function validate($input)
    {
        return $this->container->db->table('students')->where('id', $input)->count()  === 1;
    }

}
