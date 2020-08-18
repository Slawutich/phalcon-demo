<?php

use Phalcon\Validation;
use Phalcon\Validation\Validator\Email as EmailValidator;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\StringLength;
use Phalcon\Mvc\Model\Behavior\Timestampable;
use Phalcon\Validation\Validator\Uniqueness;

class Users extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var string
     */
    public $name;

    /**
     *
     * @var string
     */
    public $email;

    /**
     *
     * @var string
     */
    public $password;

    /**
     *
     * @var string
     */
    public $active;

    /**
     *
     * @var string
     */
    public $created_at;

    /**
     *
     * @var string
     */
    public $updated_at;


    /**
     * Validations and business logic
     *
     * @return boolean
     */
    public function validation()
    {
        $validator = new Validation();

        $validator->add(
            'email',
            new EmailValidator(
                [
                    'model'   => $this,
                    'message' => 'Please enter a correct email address',
                ]
            )
        );

        $validator->add(
            [
                "name",
                "email",
                "password"
            ],
            new PresenceOf(
                [
                    "message" => [
                        "name"  => "The name is required",
                        "email" => "The email is required",
                        "password" => "The password is required",
                    ],
                ]
            )
        );

        $validator->add(
            [
                "name",
                "password"
            ],
            new StringLength(
                [
                    "max"             => [
                        "name" => 255,
                        "password" => 60
                    ],
                    "min"             => [
                        "name" => 3,
                        "password" => 8
                    ],
                    "messageMaximum"  => [
                        "name" => "The name may not be greater than 255 characters.",
                        "password" => "The password may not be greater than 60 characters."
                    ],
                    "messageMinimum"  => [
                        "name" => "The name must be at least 3 characters.",
                        "password" => "The password must be at least 8 characters."
                    ],
                    "includedMaximum" => [
                        "name" => true,
                        "password" => true
                    ],
                    "includedMinimum" => [
                        "name" => false,
                        "password" => false
                    ],
                ]
            )
        );

        $validator->add(
            'email',
            new Uniqueness(
                [
                    'message' => 'User with this email already exists',
                ]
            )
        );

        return $this->validate($validator);
    }


    public function afterValidationOnCreate(){
        $this->password = $this->getDI()->getSecurity()->hash($this->password);
    }
    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("phalcon-demo");
        $this->setSource("users");

        $this->addBehavior(
            new Timestampable(
                [
                    "beforeCreate" => [
                        "field"  => "created_at",
                        "format" => "Y-m-d H:i:s",
                    ],
                ]
            )
        );

        $this->addBehavior(
            new Timestampable(
                [
                    "beforeUpdate" => [
                        "field"  => "updated_at",
                        "format" => "Y-m-d H:i:s",
                    ],
                ]
            )
        );

        $this->addBehavior(
            new Timestampable(
                [
                    "beforeUpdate" => [
                        "field"  => "updated_at",
                        "format" => "Y-m-d H:i:s",
                    ],
                ]
            )
        );
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Users[]|Users|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Users|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
