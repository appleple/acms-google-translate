<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: google/devtools/cloudtrace/v2/tracing.proto

namespace Google\Cloud\Trace\V2;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * The request message for the `BatchWriteSpans` method.
 *
 * Generated from protobuf message <code>google.devtools.cloudtrace.v2.BatchWriteSpansRequest</code>
 */
class BatchWriteSpansRequest extends \Google\Protobuf\Internal\Message
{
    /**
     * Required. The name of the project where the spans belong. The format is
     * `projects/[PROJECT_ID]`.
     *
     * Generated from protobuf field <code>string name = 1 [(.google.api.field_behavior) = REQUIRED, (.google.api.resource_reference) = {</code>
     */
    private $name = '';
    /**
     * Required. A list of new spans. The span names must not match existing
     * spans, otherwise the results are undefined.
     *
     * Generated from protobuf field <code>repeated .google.devtools.cloudtrace.v2.Span spans = 2 [(.google.api.field_behavior) = REQUIRED];</code>
     */
    private $spans;

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type string $name
     *           Required. The name of the project where the spans belong. The format is
     *           `projects/[PROJECT_ID]`.
     *     @type array<\Google\Cloud\Trace\V2\Span>|\Google\Protobuf\Internal\RepeatedField $spans
     *           Required. A list of new spans. The span names must not match existing
     *           spans, otherwise the results are undefined.
     * }
     */
    public function __construct($data = NULL) {
        \GPBMetadata\Google\Devtools\Cloudtrace\V2\Tracing::initOnce();
        parent::__construct($data);
    }

    /**
     * Required. The name of the project where the spans belong. The format is
     * `projects/[PROJECT_ID]`.
     *
     * Generated from protobuf field <code>string name = 1 [(.google.api.field_behavior) = REQUIRED, (.google.api.resource_reference) = {</code>
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Required. The name of the project where the spans belong. The format is
     * `projects/[PROJECT_ID]`.
     *
     * Generated from protobuf field <code>string name = 1 [(.google.api.field_behavior) = REQUIRED, (.google.api.resource_reference) = {</code>
     * @param string $var
     * @return $this
     */
    public function setName($var)
    {
        GPBUtil::checkString($var, True);
        $this->name = $var;

        return $this;
    }

    /**
     * Required. A list of new spans. The span names must not match existing
     * spans, otherwise the results are undefined.
     *
     * Generated from protobuf field <code>repeated .google.devtools.cloudtrace.v2.Span spans = 2 [(.google.api.field_behavior) = REQUIRED];</code>
     * @return \Google\Protobuf\Internal\RepeatedField
     */
    public function getSpans()
    {
        return $this->spans;
    }

    /**
     * Required. A list of new spans. The span names must not match existing
     * spans, otherwise the results are undefined.
     *
     * Generated from protobuf field <code>repeated .google.devtools.cloudtrace.v2.Span spans = 2 [(.google.api.field_behavior) = REQUIRED];</code>
     * @param array<\Google\Cloud\Trace\V2\Span>|\Google\Protobuf\Internal\RepeatedField $var
     * @return $this
     */
    public function setSpans($var)
    {
        $arr = GPBUtil::checkRepeatedField($var, \Google\Protobuf\Internal\GPBType::MESSAGE, \Google\Cloud\Trace\V2\Span::class);
        $this->spans = $arr;

        return $this;
    }

}
