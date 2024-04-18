<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: google/cloud/metastore/v1beta/metastore.proto

namespace Google\Cloud\Metastore\V1beta;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Telemetry Configuration for the Dataproc Metastore service.
 *
 * Generated from protobuf message <code>google.cloud.metastore.v1beta.TelemetryConfig</code>
 */
class TelemetryConfig extends \Google\Protobuf\Internal\Message
{
    /**
     * The output format of the Dataproc Metastore service's logs.
     *
     * Generated from protobuf field <code>.google.cloud.metastore.v1beta.TelemetryConfig.LogFormat log_format = 1;</code>
     */
    private $log_format = 0;

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type int $log_format
     *           The output format of the Dataproc Metastore service's logs.
     * }
     */
    public function __construct($data = NULL) {
        \GPBMetadata\Google\Cloud\Metastore\V1Beta\Metastore::initOnce();
        parent::__construct($data);
    }

    /**
     * The output format of the Dataproc Metastore service's logs.
     *
     * Generated from protobuf field <code>.google.cloud.metastore.v1beta.TelemetryConfig.LogFormat log_format = 1;</code>
     * @return int
     */
    public function getLogFormat()
    {
        return $this->log_format;
    }

    /**
     * The output format of the Dataproc Metastore service's logs.
     *
     * Generated from protobuf field <code>.google.cloud.metastore.v1beta.TelemetryConfig.LogFormat log_format = 1;</code>
     * @param int $var
     * @return $this
     */
    public function setLogFormat($var)
    {
        GPBUtil::checkEnum($var, \Google\Cloud\Metastore\V1beta\TelemetryConfig\LogFormat::class);
        $this->log_format = $var;

        return $this;
    }

}

