<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: google/cloud/functions/v2/functions.proto

namespace Google\Cloud\Functions\V2;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Request for the `ListRuntimes` method.
 *
 * Generated from protobuf message <code>google.cloud.functions.v2.ListRuntimesRequest</code>
 */
class ListRuntimesRequest extends \Google\Protobuf\Internal\Message
{
    /**
     * Required. The project and location from which the runtimes should be
     * listed, specified in the format `projects/&#42;&#47;locations/&#42;`
     *
     * Generated from protobuf field <code>string parent = 1 [(.google.api.field_behavior) = REQUIRED, (.google.api.resource_reference) = {</code>
     */
    private $parent = '';
    /**
     * The filter for Runtimes that match the filter expression,
     * following the syntax outlined in https://google.aip.dev/160.
     *
     * Generated from protobuf field <code>string filter = 2;</code>
     */
    private $filter = '';

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type string $parent
     *           Required. The project and location from which the runtimes should be
     *           listed, specified in the format `projects/&#42;&#47;locations/&#42;`
     *     @type string $filter
     *           The filter for Runtimes that match the filter expression,
     *           following the syntax outlined in https://google.aip.dev/160.
     * }
     */
    public function __construct($data = NULL) {
        \GPBMetadata\Google\Cloud\Functions\V2\Functions::initOnce();
        parent::__construct($data);
    }

    /**
     * Required. The project and location from which the runtimes should be
     * listed, specified in the format `projects/&#42;&#47;locations/&#42;`
     *
     * Generated from protobuf field <code>string parent = 1 [(.google.api.field_behavior) = REQUIRED, (.google.api.resource_reference) = {</code>
     * @return string
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Required. The project and location from which the runtimes should be
     * listed, specified in the format `projects/&#42;&#47;locations/&#42;`
     *
     * Generated from protobuf field <code>string parent = 1 [(.google.api.field_behavior) = REQUIRED, (.google.api.resource_reference) = {</code>
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
     * The filter for Runtimes that match the filter expression,
     * following the syntax outlined in https://google.aip.dev/160.
     *
     * Generated from protobuf field <code>string filter = 2;</code>
     * @return string
     */
    public function getFilter()
    {
        return $this->filter;
    }

    /**
     * The filter for Runtimes that match the filter expression,
     * following the syntax outlined in https://google.aip.dev/160.
     *
     * Generated from protobuf field <code>string filter = 2;</code>
     * @param string $var
     * @return $this
     */
    public function setFilter($var)
    {
        GPBUtil::checkString($var, True);
        $this->filter = $var;

        return $this;
    }

}

