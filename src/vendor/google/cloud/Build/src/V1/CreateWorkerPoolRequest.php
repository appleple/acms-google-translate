<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: google/devtools/cloudbuild/v1/cloudbuild.proto

namespace Google\Cloud\Build\V1;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Request to create a new `WorkerPool`.
 *
 * Generated from protobuf message <code>google.devtools.cloudbuild.v1.CreateWorkerPoolRequest</code>
 */
class CreateWorkerPoolRequest extends \Google\Protobuf\Internal\Message
{
    /**
     * Required. The parent resource where this worker pool will be created.
     * Format: `projects/{project}/locations/{location}`.
     *
     * Generated from protobuf field <code>string parent = 1 [(.google.api.field_behavior) = REQUIRED, (.google.api.resource_reference) = {</code>
     */
    private $parent = '';
    /**
     * Required. `WorkerPool` resource to create.
     *
     * Generated from protobuf field <code>.google.devtools.cloudbuild.v1.WorkerPool worker_pool = 2 [(.google.api.field_behavior) = REQUIRED];</code>
     */
    private $worker_pool = null;
    /**
     * Required. Immutable. The ID to use for the `WorkerPool`, which will become
     * the final component of the resource name.
     * This value should be 1-63 characters, and valid characters
     * are /[a-z][0-9]-/.
     *
     * Generated from protobuf field <code>string worker_pool_id = 3 [(.google.api.field_behavior) = IMMUTABLE, (.google.api.field_behavior) = REQUIRED];</code>
     */
    private $worker_pool_id = '';
    /**
     * If set, validate the request and preview the response, but do not actually
     * post it.
     *
     * Generated from protobuf field <code>bool validate_only = 4;</code>
     */
    private $validate_only = false;

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type string $parent
     *           Required. The parent resource where this worker pool will be created.
     *           Format: `projects/{project}/locations/{location}`.
     *     @type \Google\Cloud\Build\V1\WorkerPool $worker_pool
     *           Required. `WorkerPool` resource to create.
     *     @type string $worker_pool_id
     *           Required. Immutable. The ID to use for the `WorkerPool`, which will become
     *           the final component of the resource name.
     *           This value should be 1-63 characters, and valid characters
     *           are /[a-z][0-9]-/.
     *     @type bool $validate_only
     *           If set, validate the request and preview the response, but do not actually
     *           post it.
     * }
     */
    public function __construct($data = NULL) {
        \GPBMetadata\Google\Devtools\Cloudbuild\V1\Cloudbuild::initOnce();
        parent::__construct($data);
    }

    /**
     * Required. The parent resource where this worker pool will be created.
     * Format: `projects/{project}/locations/{location}`.
     *
     * Generated from protobuf field <code>string parent = 1 [(.google.api.field_behavior) = REQUIRED, (.google.api.resource_reference) = {</code>
     * @return string
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Required. The parent resource where this worker pool will be created.
     * Format: `projects/{project}/locations/{location}`.
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
     * Required. `WorkerPool` resource to create.
     *
     * Generated from protobuf field <code>.google.devtools.cloudbuild.v1.WorkerPool worker_pool = 2 [(.google.api.field_behavior) = REQUIRED];</code>
     * @return \Google\Cloud\Build\V1\WorkerPool|null
     */
    public function getWorkerPool()
    {
        return $this->worker_pool;
    }

    public function hasWorkerPool()
    {
        return isset($this->worker_pool);
    }

    public function clearWorkerPool()
    {
        unset($this->worker_pool);
    }

    /**
     * Required. `WorkerPool` resource to create.
     *
     * Generated from protobuf field <code>.google.devtools.cloudbuild.v1.WorkerPool worker_pool = 2 [(.google.api.field_behavior) = REQUIRED];</code>
     * @param \Google\Cloud\Build\V1\WorkerPool $var
     * @return $this
     */
    public function setWorkerPool($var)
    {
        GPBUtil::checkMessage($var, \Google\Cloud\Build\V1\WorkerPool::class);
        $this->worker_pool = $var;

        return $this;
    }

    /**
     * Required. Immutable. The ID to use for the `WorkerPool`, which will become
     * the final component of the resource name.
     * This value should be 1-63 characters, and valid characters
     * are /[a-z][0-9]-/.
     *
     * Generated from protobuf field <code>string worker_pool_id = 3 [(.google.api.field_behavior) = IMMUTABLE, (.google.api.field_behavior) = REQUIRED];</code>
     * @return string
     */
    public function getWorkerPoolId()
    {
        return $this->worker_pool_id;
    }

    /**
     * Required. Immutable. The ID to use for the `WorkerPool`, which will become
     * the final component of the resource name.
     * This value should be 1-63 characters, and valid characters
     * are /[a-z][0-9]-/.
     *
     * Generated from protobuf field <code>string worker_pool_id = 3 [(.google.api.field_behavior) = IMMUTABLE, (.google.api.field_behavior) = REQUIRED];</code>
     * @param string $var
     * @return $this
     */
    public function setWorkerPoolId($var)
    {
        GPBUtil::checkString($var, True);
        $this->worker_pool_id = $var;

        return $this;
    }

    /**
     * If set, validate the request and preview the response, but do not actually
     * post it.
     *
     * Generated from protobuf field <code>bool validate_only = 4;</code>
     * @return bool
     */
    public function getValidateOnly()
    {
        return $this->validate_only;
    }

    /**
     * If set, validate the request and preview the response, but do not actually
     * post it.
     *
     * Generated from protobuf field <code>bool validate_only = 4;</code>
     * @param bool $var
     * @return $this
     */
    public function setValidateOnly($var)
    {
        GPBUtil::checkBool($var);
        $this->validate_only = $var;

        return $this;
    }

}
