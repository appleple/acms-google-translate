<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: grafeas/v1/cvss.proto

namespace Grafeas\V1\CVSS;

use UnexpectedValueException;

/**
 * Protobuf type <code>grafeas.v1.CVSS.PrivilegesRequired</code>
 */
class PrivilegesRequired
{
    /**
     * Generated from protobuf enum <code>PRIVILEGES_REQUIRED_UNSPECIFIED = 0;</code>
     */
    const PRIVILEGES_REQUIRED_UNSPECIFIED = 0;
    /**
     * Generated from protobuf enum <code>PRIVILEGES_REQUIRED_NONE = 1;</code>
     */
    const PRIVILEGES_REQUIRED_NONE = 1;
    /**
     * Generated from protobuf enum <code>PRIVILEGES_REQUIRED_LOW = 2;</code>
     */
    const PRIVILEGES_REQUIRED_LOW = 2;
    /**
     * Generated from protobuf enum <code>PRIVILEGES_REQUIRED_HIGH = 3;</code>
     */
    const PRIVILEGES_REQUIRED_HIGH = 3;

    private static $valueToName = [
        self::PRIVILEGES_REQUIRED_UNSPECIFIED => 'PRIVILEGES_REQUIRED_UNSPECIFIED',
        self::PRIVILEGES_REQUIRED_NONE => 'PRIVILEGES_REQUIRED_NONE',
        self::PRIVILEGES_REQUIRED_LOW => 'PRIVILEGES_REQUIRED_LOW',
        self::PRIVILEGES_REQUIRED_HIGH => 'PRIVILEGES_REQUIRED_HIGH',
    ];

    public static function name($value)
    {
        if (!isset(self::$valueToName[$value])) {
            throw new UnexpectedValueException(sprintf(
                    'Enum %s has no name defined for value %s', __CLASS__, $value));
        }
        return self::$valueToName[$value];
    }


    public static function value($name)
    {
        $const = __CLASS__ . '::' . strtoupper($name);
        if (!defined($const)) {
            throw new UnexpectedValueException(sprintf(
                    'Enum %s has no value defined for name %s', __CLASS__, $name));
        }
        return constant($const);
    }
}

