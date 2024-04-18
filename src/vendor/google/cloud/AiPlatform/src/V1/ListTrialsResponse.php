<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: google/cloud/aiplatform/v1/vizier_service.proto

namespace Google\Cloud\AIPlatform\V1;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Response message for
 * [VizierService.ListTrials][google.cloud.aiplatform.v1.VizierService.ListTrials].
 *
 * Generated from protobuf message <code>google.cloud.aiplatform.v1.ListTrialsResponse</code>
 */
class ListTrialsResponse extends \Google\Protobuf\Internal\Message
{
    /**
     * The Trials associated with the Study.
     *
     * Generated from protobuf field <code>repeated .google.cloud.aiplatform.v1.Trial trials = 1;</code>
     */
    private $trials;
    /**
     * Pass this token as the `page_token` field of the request for a
     * subsequent call.
     * If this field is omitted, there are no subsequent pages.
     *
     * Generated from protobuf field <code>string next_page_token = 2;</code>
     */
    private $next_page_token = '';

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type array<\Google\Cloud\AIPlatform\V1\Trial>|\Google\Protobuf\Internal\RepeatedField $trials
     *           The Trials associated with the Study.
     *     @type string $next_page_token
     *           Pass this token as the `page_token` field of the request for a
     *           subsequent call.
     *           If this field is omitted, there are no subsequent pages.
     * }
     */
    public function __construct($data = NULL) {
        \GPBMetadata\Google\Cloud\Aiplatform\V1\VizierService::initOnce();
        parent::__construct($data);
    }

    /**
     * The Trials associated with the Study.
     *
     * Generated from protobuf field <code>repeated .google.cloud.aiplatform.v1.Trial trials = 1;</code>
     * @return \Google\Protobuf\Internal\RepeatedField
     */
    public function getTrials()
    {
        return $this->trials;
    }

    /**
     * The Trials associated with the Study.
     *
     * Generated from protobuf field <code>repeated .google.cloud.aiplatform.v1.Trial trials = 1;</code>
     * @param array<\Google\Cloud\AIPlatform\V1\Trial>|\Google\Protobuf\Internal\RepeatedField $var
     * @return $this
     */
    public function setTrials($var)
    {
        $arr = GPBUtil::checkRepeatedField($var, \Google\Protobuf\Internal\GPBType::MESSAGE, \Google\Cloud\AIPlatform\V1\Trial::class);
        $this->trials = $arr;

        return $this;
    }

    /**
     * Pass this token as the `page_token` field of the request for a
     * subsequent call.
     * If this field is omitted, there are no subsequent pages.
     *
     * Generated from protobuf field <code>string next_page_token = 2;</code>
     * @return string
     */
    public function getNextPageToken()
    {
        return $this->next_page_token;
    }

    /**
     * Pass this token as the `page_token` field of the request for a
     * subsequent call.
     * If this field is omitted, there are no subsequent pages.
     *
     * Generated from protobuf field <code>string next_page_token = 2;</code>
     * @param string $var
     * @return $this
     */
    public function setNextPageToken($var)
    {
        GPBUtil::checkString($var, True);
        $this->next_page_token = $var;

        return $this;
    }

}

