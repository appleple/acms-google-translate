<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: google/dataflow/v1beta3/templates.proto

namespace Google\Cloud\Dataflow\V1beta3;

use UnexpectedValueException;

/**
 * ParameterType specifies what kind of input we need for this parameter.
 *
 * Protobuf type <code>google.dataflow.v1beta3.ParameterType</code>
 */
class ParameterType
{
    /**
     * Default input type.
     *
     * Generated from protobuf enum <code>DEFAULT = 0;</code>
     */
    const PBDEFAULT = 0;
    /**
     * The parameter specifies generic text input.
     *
     * Generated from protobuf enum <code>TEXT = 1;</code>
     */
    const TEXT = 1;
    /**
     * The parameter specifies a Cloud Storage Bucket to read from.
     *
     * Generated from protobuf enum <code>GCS_READ_BUCKET = 2;</code>
     */
    const GCS_READ_BUCKET = 2;
    /**
     * The parameter specifies a Cloud Storage Bucket to write to.
     *
     * Generated from protobuf enum <code>GCS_WRITE_BUCKET = 3;</code>
     */
    const GCS_WRITE_BUCKET = 3;
    /**
     * The parameter specifies a Cloud Storage file path to read from.
     *
     * Generated from protobuf enum <code>GCS_READ_FILE = 4;</code>
     */
    const GCS_READ_FILE = 4;
    /**
     * The parameter specifies a Cloud Storage file path to write to.
     *
     * Generated from protobuf enum <code>GCS_WRITE_FILE = 5;</code>
     */
    const GCS_WRITE_FILE = 5;
    /**
     * The parameter specifies a Cloud Storage folder path to read from.
     *
     * Generated from protobuf enum <code>GCS_READ_FOLDER = 6;</code>
     */
    const GCS_READ_FOLDER = 6;
    /**
     * The parameter specifies a Cloud Storage folder to write to.
     *
     * Generated from protobuf enum <code>GCS_WRITE_FOLDER = 7;</code>
     */
    const GCS_WRITE_FOLDER = 7;
    /**
     * The parameter specifies a Pub/Sub Topic.
     *
     * Generated from protobuf enum <code>PUBSUB_TOPIC = 8;</code>
     */
    const PUBSUB_TOPIC = 8;
    /**
     * The parameter specifies a Pub/Sub Subscription.
     *
     * Generated from protobuf enum <code>PUBSUB_SUBSCRIPTION = 9;</code>
     */
    const PUBSUB_SUBSCRIPTION = 9;

    private static $valueToName = [
        self::PBDEFAULT => 'DEFAULT',
        self::TEXT => 'TEXT',
        self::GCS_READ_BUCKET => 'GCS_READ_BUCKET',
        self::GCS_WRITE_BUCKET => 'GCS_WRITE_BUCKET',
        self::GCS_READ_FILE => 'GCS_READ_FILE',
        self::GCS_WRITE_FILE => 'GCS_WRITE_FILE',
        self::GCS_READ_FOLDER => 'GCS_READ_FOLDER',
        self::GCS_WRITE_FOLDER => 'GCS_WRITE_FOLDER',
        self::PUBSUB_TOPIC => 'PUBSUB_TOPIC',
        self::PUBSUB_SUBSCRIPTION => 'PUBSUB_SUBSCRIPTION',
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
            $pbconst =  __CLASS__. '::PB' . strtoupper($name);
            if (!defined($pbconst)) {
                throw new UnexpectedValueException(sprintf(
                        'Enum %s has no value defined for name %s', __CLASS__, $name));
            }
            return constant($pbconst);
        }
        return constant($const);
    }
}

