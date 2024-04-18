<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: google/dataflow/v1beta3/metrics.proto

namespace Google\Cloud\Dataflow\V1beta3;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Information about a worker
 *
 * Generated from protobuf message <code>google.dataflow.v1beta3.WorkerDetails</code>
 */
class WorkerDetails extends \Google\Protobuf\Internal\Message
{
    /**
     * Name of this worker
     *
     * Generated from protobuf field <code>string worker_name = 1;</code>
     */
    private $worker_name = '';
    /**
     * Work items processed by this worker, sorted by time.
     *
     * Generated from protobuf field <code>repeated .google.dataflow.v1beta3.WorkItemDetails work_items = 2;</code>
     */
    private $work_items;

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type string $worker_name
     *           Name of this worker
     *     @type array<\Google\Cloud\Dataflow\V1beta3\WorkItemDetails>|\Google\Protobuf\Internal\RepeatedField $work_items
     *           Work items processed by this worker, sorted by time.
     * }
     */
    public function __construct($data = NULL) {
        \GPBMetadata\Google\Dataflow\V1Beta3\Metrics::initOnce();
        parent::__construct($data);
    }

    /**
     * Name of this worker
     *
     * Generated from protobuf field <code>string worker_name = 1;</code>
     * @return string
     */
    public function getWorkerName()
    {
        return $this->worker_name;
    }

    /**
     * Name of this worker
     *
     * Generated from protobuf field <code>string worker_name = 1;</code>
     * @param string $var
     * @return $this
     */
    public function setWorkerName($var)
    {
        GPBUtil::checkString($var, True);
        $this->worker_name = $var;

        return $this;
    }

    /**
     * Work items processed by this worker, sorted by time.
     *
     * Generated from protobuf field <code>repeated .google.dataflow.v1beta3.WorkItemDetails work_items = 2;</code>
     * @return \Google\Protobuf\Internal\RepeatedField
     */
    public function getWorkItems()
    {
        return $this->work_items;
    }

    /**
     * Work items processed by this worker, sorted by time.
     *
     * Generated from protobuf field <code>repeated .google.dataflow.v1beta3.WorkItemDetails work_items = 2;</code>
     * @param array<\Google\Cloud\Dataflow\V1beta3\WorkItemDetails>|\Google\Protobuf\Internal\RepeatedField $var
     * @return $this
     */
    public function setWorkItems($var)
    {
        $arr = GPBUtil::checkRepeatedField($var, \Google\Protobuf\Internal\GPBType::MESSAGE, \Google\Cloud\Dataflow\V1beta3\WorkItemDetails::class);
        $this->work_items = $arr;

        return $this;
    }

}

