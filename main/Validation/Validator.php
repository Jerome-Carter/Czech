<?php

namespace Czech\Validation;

use Respect\Vaildation\Validator as Respect;
use Respect\Validation\Exceptions\NestedValidationException;

class Validator{

    protected $err_list;

    public function validate($request, array $rules) {
        foreach ($rules as $field => $info) {
            try {
                $info[1]->setName(" ")->assert($request->getParam($field));
            } catch (NestedValidationException $e) {
                $this->err_list[$field] = $e->getMessages();
            }
        }
        $_SESSION['form_errors'] = $this->err_list;
        return $this;
    }

    public function failed() {
        return !empty($this->err_list);
    }

}