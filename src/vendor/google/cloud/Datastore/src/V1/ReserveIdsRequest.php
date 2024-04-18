<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: google/datastore/v1/datastore.proto

namespace Google\Cloud\Datastore\V1;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * The request for
 * [Datastore.ReserveIds][google.datastore.v1.Datastore.ReserveIds].
 *
 * Generated from protobuf message <code>google.datastore.v1.ReserveIdsRequest</code>
 */
class ReserveIdsRequest extends \Google\Protobuf\Internal\Message
{
    /**
     * Required. The ID of the project against which to make the request.
     *
     * Generated from protobuf field <code>string project_id = 8 [(.google.api.field_behavior) = REQUIRED];</code>
     */
    private $project_id = '';
    /**
     * The ID of the database against which to make the request.
     * '(default)' is not allowed; please use empty string '' to refer the default
     * database.
     *
     * Generated from protobuf field <code>string database_id = 9;</code>
     */
    private $database_id = '';
    /**
     * Required. A list of keys with complete key paths whose numeric IDs should
     * not be auto-allocated.
     *
     * Generated from protobuf field <code>repeated .google.datastore.v1.Key keys = 1 [(.google.api.field_behavior) = REQUIRED];</code>
     */
    private $keys;

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type string $project_id
     *           Required. The ID of the project against which to make the request.
     *     @type string $database_id
     *           The ID of the database against which to make the request.
     *           '(default)' is not allowed; please use empty string '' to refer the default
     *           database.
     *     @type array<\Google\Cloud\Datastore\V1\Key>|\Google\Protobuf\Internal\RepeatedField $keys
     *           Required. A list of keys with complete key paths whose numeric IDs should
     *           not be auto-allocated.
     * }
     */
    public function __construct($data = NULL) {
        \GPBMetadata\Google\Datastore\V1\Datastore::initOnce();
        parent::__construct($data);
    }

    /**
     * Required. The ID of the project against which to make the request.
     *
     * Generated from protobuf field <code>string project_id = 8 [(.google.api.field_behavior) = REQUIRED];</code>
     * @return string
     */
    public function getProjectId()
    {
        return $this->project_id;
    }

    /**
     * Required. The ID of the project against which to make the request.
     *
     * Generated from protobuf field <code>string project_id = 8 [(.google.api.field_behavior) = REQUIRED];</code>
     * @param string $var
     * @return $this
     */
    public function setProjectId($var)
    {
        GPBUtil::checkString($var, True);
        $this->project_id = $var;

        return $this;
    }

    /**
     * The ID of the database against which to make the request.
     * '(default)' is not allowed; please use empty string '' to refer the default
     * database.
     *
     * Generated from protobuf field <code>string database_id = 9;</code>
     * @return string
     */
    public function getDatabaseId()
    {
        return $this->database_id;
    }

    /**
     * The ID of the database against which to make the request.
     * '(default)' is not allowed; please use empty string '' to refer the default
     * database.
     *
     * Generated from protobuf field <code>string database_id = 9;</code>
     * @param string $var
     * @return $this
     */
    public function setDatabaseId($var)
    {
        GPBUtil::checkString($var, True);
        $this->database_id = $var;

        return $this;
    }

    /**
     * Required. A list of keys with complete key paths whose numeric IDs should
     * not be auto-allocated.
     *
     * Generated from protobuf field <code>repeated .google.datastore.v1.Key keys = 1 [(.google.api.field_behavior) = REQUIRED];</code>
     * @return \Google\Protobuf\Internal\RepeatedField
     */
    public function getKeys()
    {
        return $this->keys;
    }

    /**
     * Required. A list of keys with complete key paths whose numeric IDs should
     * not be auto-allocated.
     *
     * Generated from protobuf field <code>repeated .google.datastore.v1.Key keys = 1 [(.google.api.field_behavior) = REQUIRED];</code>
     * @param array<\Google\Cloud\Datastore\V1\Key>|\Google\Protobuf\Internal\RepeatedField $var
     * @return $this
     */
    public function setKeys($var)
    {
        $arr = GPBUtil::checkRepeatedField($var, \Google\Protobuf\Internal\GPBType::MESSAGE, \Google\Cloud\Datastore\V1\Key::class);
        $this->keys = $arr;

        return $this;
    }

}

