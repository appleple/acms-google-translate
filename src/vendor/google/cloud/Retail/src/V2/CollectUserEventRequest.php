<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: google/cloud/retail/v2/user_event_service.proto

namespace Google\Cloud\Retail\V2;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Request message for CollectUserEvent method.
 *
 * Generated from protobuf message <code>google.cloud.retail.v2.CollectUserEventRequest</code>
 */
class CollectUserEventRequest extends \Google\Protobuf\Internal\Message
{
    /**
     * Required. The parent catalog name, such as
     * `projects/1234/locations/global/catalogs/default_catalog`.
     *
     * Generated from protobuf field <code>string parent = 1 [(.google.api.field_behavior) = REQUIRED];</code>
     */
    private $parent = '';
    /**
     * Required. URL encoded UserEvent proto with a length limit of 2,000,000
     * characters.
     *
     * Generated from protobuf field <code>string user_event = 2 [(.google.api.field_behavior) = REQUIRED];</code>
     */
    private $user_event = '';
    /**
     * The URL including cgi-parameters but excluding the hash fragment with a
     * length limit of 5,000 characters. This is often more useful than the
     * referer URL, because many browsers only send the domain for 3rd party
     * requests.
     *
     * Generated from protobuf field <code>string uri = 3;</code>
     */
    private $uri = '';
    /**
     * The event timestamp in milliseconds. This prevents browser caching of
     * otherwise identical get requests. The name is abbreviated to reduce the
     * payload bytes.
     *
     * Generated from protobuf field <code>int64 ets = 4;</code>
     */
    private $ets = 0;
    /**
     * An arbitrary serialized JSON string that contains necessary information
     * that can comprise a user event. When this field is specified, the
     * user_event field will be ignored. Note: line-delimited JSON is not
     * supported, a single JSON only.
     *
     * Generated from protobuf field <code>string raw_json = 5;</code>
     */
    private $raw_json = '';
    protected $conversion_rule;

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type string $prebuilt_rule
     *           The prebuilt rule name that can convert a specific type of raw_json.
     *           For example: "default_schema/v1.0"
     *     @type string $parent
     *           Required. The parent catalog name, such as
     *           `projects/1234/locations/global/catalogs/default_catalog`.
     *     @type string $user_event
     *           Required. URL encoded UserEvent proto with a length limit of 2,000,000
     *           characters.
     *     @type string $uri
     *           The URL including cgi-parameters but excluding the hash fragment with a
     *           length limit of 5,000 characters. This is often more useful than the
     *           referer URL, because many browsers only send the domain for 3rd party
     *           requests.
     *     @type int|string $ets
     *           The event timestamp in milliseconds. This prevents browser caching of
     *           otherwise identical get requests. The name is abbreviated to reduce the
     *           payload bytes.
     *     @type string $raw_json
     *           An arbitrary serialized JSON string that contains necessary information
     *           that can comprise a user event. When this field is specified, the
     *           user_event field will be ignored. Note: line-delimited JSON is not
     *           supported, a single JSON only.
     * }
     */
    public function __construct($data = NULL) {
        \GPBMetadata\Google\Cloud\Retail\V2\UserEventService::initOnce();
        parent::__construct($data);
    }

    /**
     * The prebuilt rule name that can convert a specific type of raw_json.
     * For example: "default_schema/v1.0"
     *
     * Generated from protobuf field <code>string prebuilt_rule = 6;</code>
     * @return string
     */
    public function getPrebuiltRule()
    {
        return $this->readOneof(6);
    }

    public function hasPrebuiltRule()
    {
        return $this->hasOneof(6);
    }

    /**
     * The prebuilt rule name that can convert a specific type of raw_json.
     * For example: "default_schema/v1.0"
     *
     * Generated from protobuf field <code>string prebuilt_rule = 6;</code>
     * @param string $var
     * @return $this
     */
    public function setPrebuiltRule($var)
    {
        GPBUtil::checkString($var, True);
        $this->writeOneof(6, $var);

        return $this;
    }

    /**
     * Required. The parent catalog name, such as
     * `projects/1234/locations/global/catalogs/default_catalog`.
     *
     * Generated from protobuf field <code>string parent = 1 [(.google.api.field_behavior) = REQUIRED];</code>
     * @return string
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Required. The parent catalog name, such as
     * `projects/1234/locations/global/catalogs/default_catalog`.
     *
     * Generated from protobuf field <code>string parent = 1 [(.google.api.field_behavior) = REQUIRED];</code>
     * @param string $var
     * @return $this
     */
    public function setParent($var)
    {
        GPBUtil::checkString($var, True);
        $this->parent = $var;

        return $this;
    }

    /**
     * Required. URL encoded UserEvent proto with a length limit of 2,000,000
     * characters.
     *
     * Generated from protobuf field <code>string user_event = 2 [(.google.api.field_behavior) = REQUIRED];</code>
     * @return string
     */
    public function getUserEvent()
    {
        return $this->user_event;
    }

    /**
     * Required. URL encoded UserEvent proto with a length limit of 2,000,000
     * characters.
     *
     * Generated from protobuf field <code>string user_event = 2 [(.google.api.field_behavior) = REQUIRED];</code>
     * @param string $var
     * @return $this
     */
    public function setUserEvent($var)
    {
        GPBUtil::checkString($var, True);
        $this->user_event = $var;

        return $this;
    }

    /**
     * The URL including cgi-parameters but excluding the hash fragment with a
     * length limit of 5,000 characters. This is often more useful than the
     * referer URL, because many browsers only send the domain for 3rd party
     * requests.
     *
     * Generated from protobuf field <code>string uri = 3;</code>
     * @return string
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * The URL including cgi-parameters but excluding the hash fragment with a
     * length limit of 5,000 characters. This is often more useful than the
     * referer URL, because many browsers only send the domain for 3rd party
     * requests.
     *
     * Generated from protobuf field <code>string uri = 3;</code>
     * @param string $var
     * @return $this
     */
    public function setUri($var)
    {
        GPBUtil::checkString($var, True);
        $this->uri = $var;

        return $this;
    }

    /**
     * The event timestamp in milliseconds. This prevents browser caching of
     * otherwise identical get requests. The name is abbreviated to reduce the
     * payload bytes.
     *
     * Generated from protobuf field <code>int64 ets = 4;</code>
     * @return int|string
     */
    public function getEts()
    {
        return $this->ets;
    }

    /**
     * The event timestamp in milliseconds. This prevents browser caching of
     * otherwise identical get requests. The name is abbreviated to reduce the
     * payload bytes.
     *
     * Generated from protobuf field <code>int64 ets = 4;</code>
     * @param int|string $var
     * @return $this
     */
    public function setEts($var)
    {
        GPBUtil::checkInt64($var);
        $this->ets = $var;

        return $this;
    }

    /**
     * An arbitrary serialized JSON string that contains necessary information
     * that can comprise a user event. When this field is specified, the
     * user_event field will be ignored. Note: line-delimited JSON is not
     * supported, a single JSON only.
     *
     * Generated from protobuf field <code>string raw_json = 5;</code>
     * @return string
     */
    public function getRawJson()
    {
        return $this->raw_json;
    }

    /**
     * An arbitrary serialized JSON string that contains necessary information
     * that can comprise a user event. When this field is specified, the
     * user_event field will be ignored. Note: line-delimited JSON is not
     * supported, a single JSON only.
     *
     * Generated from protobuf field <code>string raw_json = 5;</code>
     * @param string $var
     * @return $this
     */
    public function setRawJson($var)
    {
        GPBUtil::checkString($var, True);
        $this->raw_json = $var;

        return $this;
    }

    /**
     * @return string
     */
    public function getConversionRule()
    {
        return $this->whichOneof("conversion_rule");
    }

}
