<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: google/cloud/compute/v1/compute.proto

namespace Google\Cloud\Compute\V1;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * A request message for Disks.Update. See the method description for details.
 *
 * Generated from protobuf message <code>google.cloud.compute.v1.UpdateDiskRequest</code>
 */
class UpdateDiskRequest extends \Google\Protobuf\Internal\Message
{
    /**
     * The disk name for this request.
     *
     * Generated from protobuf field <code>string disk = 3083677 [(.google.api.field_behavior) = REQUIRED];</code>
     */
    private $disk = '';
    /**
     * The body resource for this request
     *
     * Generated from protobuf field <code>.google.cloud.compute.v1.Disk disk_resource = 25880688 [(.google.api.field_behavior) = REQUIRED];</code>
     */
    private $disk_resource = null;
    /**
     * Generated from protobuf field <code>optional string paths = 106438894;</code>
     */
    private $paths = null;
    /**
     * Project ID for this request.
     *
     * Generated from protobuf field <code>string project = 227560217 [(.google.api.field_behavior) = REQUIRED, (.google.cloud.operation_request_field) = "project"];</code>
     */
    private $project = '';
    /**
     * An optional request ID to identify requests. Specify a unique request ID so that if you must retry your request, the server will know to ignore the request if it has already been completed. For example, consider a situation where you make an initial request and the request times out. If you make the request again with the same request ID, the server can check if original operation with the same request ID was received, and if so, will ignore the second request. This prevents clients from accidentally creating duplicate commitments. The request ID must be a valid UUID with the exception that zero UUID is not supported ( 00000000-0000-0000-0000-000000000000).
     *
     * Generated from protobuf field <code>optional string request_id = 37109963;</code>
     */
    private $request_id = null;
    /**
     * update_mask indicates fields to be updated as part of this request.
     *
     * Generated from protobuf field <code>optional string update_mask = 500079778;</code>
     */
    private $update_mask = null;
    /**
     * The name of the zone for this request.
     *
     * Generated from protobuf field <code>string zone = 3744684 [(.google.api.field_behavior) = REQUIRED, (.google.cloud.operation_request_field) = "zone"];</code>
     */
    private $zone = '';

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type string $disk
     *           The disk name for this request.
     *     @type \Google\Cloud\Compute\V1\Disk $disk_resource
     *           The body resource for this request
     *     @type string $paths
     *     @type string $project
     *           Project ID for this request.
     *     @type string $request_id
     *           An optional request ID to identify requests. Specify a unique request ID so that if you must retry your request, the server will know to ignore the request if it has already been completed. For example, consider a situation where you make an initial request and the request times out. If you make the request again with the same request ID, the server can check if original operation with the same request ID was received, and if so, will ignore the second request. This prevents clients from accidentally creating duplicate commitments. The request ID must be a valid UUID with the exception that zero UUID is not supported ( 00000000-0000-0000-0000-000000000000).
     *     @type string $update_mask
     *           update_mask indicates fields to be updated as part of this request.
     *     @type string $zone
     *           The name of the zone for this request.
     * }
     */
    public function __construct($data = NULL) {
        \GPBMetadata\Google\Cloud\Compute\V1\Compute::initOnce();
        parent::__construct($data);
    }

    /**
     * The disk name for this request.
     *
     * Generated from protobuf field <code>string disk = 3083677 [(.google.api.field_behavior) = REQUIRED];</code>
     * @return string
     */
    public function getDisk()
    {
        return $this->disk;
    }

    /**
     * The disk name for this request.
     *
     * Generated from protobuf field <code>string disk = 3083677 [(.google.api.field_behavior) = REQUIRED];</code>
     * @param string $var
     * @return $this
     */
    public function setDisk($var)
    {
        GPBUtil::checkString($var, True);
        $this->disk = $var;

        return $this;
    }

    /**
     * The body resource for this request
     *
     * Generated from protobuf field <code>.google.cloud.compute.v1.Disk disk_resource = 25880688 [(.google.api.field_behavior) = REQUIRED];</code>
     * @return \Google\Cloud\Compute\V1\Disk|null
     */
    public function getDiskResource()
    {
        return $this->disk_resource;
    }

    public function hasDiskResource()
    {
        return isset($this->disk_resource);
    }

    public function clearDiskResource()
    {
        unset($this->disk_resource);
    }

    /**
     * The body resource for this request
     *
     * Generated from protobuf field <code>.google.cloud.compute.v1.Disk disk_resource = 25880688 [(.google.api.field_behavior) = REQUIRED];</code>
     * @param \Google\Cloud\Compute\V1\Disk $var
     * @return $this
     */
    public function setDiskResource($var)
    {
        GPBUtil::checkMessage($var, \Google\Cloud\Compute\V1\Disk::class);
        $this->disk_resource = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>optional string paths = 106438894;</code>
     * @return string
     */
    public function getPaths()
    {
        return isset($this->paths) ? $this->paths : '';
    }

    public function hasPaths()
    {
        return isset($this->paths);
    }

    public function clearPaths()
    {
        unset($this->paths);
    }

    /**
     * Generated from protobuf field <code>optional string paths = 106438894;</code>
     * @param string $var
     * @return $this
     */
    public function setPaths($var)
    {
        GPBUtil::checkString($var, True);
        $this->paths = $var;

        return $this;
    }

    /**
     * Project ID for this request.
     *
     * Generated from protobuf field <code>string project = 227560217 [(.google.api.field_behavior) = REQUIRED, (.google.cloud.operation_request_field) = "project"];</code>
     * @return string
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * Project ID for this request.
     *
     * Generated from protobuf field <code>string project = 227560217 [(.google.api.field_behavior) = REQUIRED, (.google.cloud.operation_request_field) = "project"];</code>
     * @param string $var
     * @return $this
     */
    public function setProject($var)
    {
        GPBUtil::checkString($var, True);
        $this->project = $var;

        return $this;
    }

    /**
     * An optional request ID to identify requests. Specify a unique request ID so that if you must retry your request, the server will know to ignore the request if it has already been completed. For example, consider a situation where you make an initial request and the request times out. If you make the request again with the same request ID, the server can check if original operation with the same request ID was received, and if so, will ignore the second request. This prevents clients from accidentally creating duplicate commitments. The request ID must be a valid UUID with the exception that zero UUID is not supported ( 00000000-0000-0000-0000-000000000000).
     *
     * Generated from protobuf field <code>optional string request_id = 37109963;</code>
     * @return string
     */
    public function getRequestId()
    {
        return isset($this->request_id) ? $this->request_id : '';
    }

    public function hasRequestId()
    {
        return isset($this->request_id);
    }

    public function clearRequestId()
    {
        unset($this->request_id);
    }

    /**
     * An optional request ID to identify requests. Specify a unique request ID so that if you must retry your request, the server will know to ignore the request if it has already been completed. For example, consider a situation where you make an initial request and the request times out. If you make the request again with the same request ID, the server can check if original operation with the same request ID was received, and if so, will ignore the second request. This prevents clients from accidentally creating duplicate commitments. The request ID must be a valid UUID with the exception that zero UUID is not supported ( 00000000-0000-0000-0000-000000000000).
     *
     * Generated from protobuf field <code>optional string request_id = 37109963;</code>
     * @param string $var
     * @return $this
     */
    public function setRequestId($var)
    {
        GPBUtil::checkString($var, True);
        $this->request_id = $var;

        return $this;
    }

    /**
     * update_mask indicates fields to be updated as part of this request.
     *
     * Generated from protobuf field <code>optional string update_mask = 500079778;</code>
     * @return string
     */
    public function getUpdateMask()
    {
        return isset($this->update_mask) ? $this->update_mask : '';
    }

    public function hasUpdateMask()
    {
        return isset($this->update_mask);
    }

    public function clearUpdateMask()
    {
        unset($this->update_mask);
    }

    /**
     * update_mask indicates fields to be updated as part of this request.
     *
     * Generated from protobuf field <code>optional string update_mask = 500079778;</code>
     * @param string $var
     * @return $this
     */
    public function setUpdateMask($var)
    {
        GPBUtil::checkString($var, True);
        $this->update_mask = $var;

        return $this;
    }

    /**
     * The name of the zone for this request.
     *
     * Generated from protobuf field <code>string zone = 3744684 [(.google.api.field_behavior) = REQUIRED, (.google.cloud.operation_request_field) = "zone"];</code>
     * @return string
     */
    public function getZone()
    {
        return $this->zone;
    }

    /**
     * The name of the zone for this request.
     *
     * Generated from protobuf field <code>string zone = 3744684 [(.google.api.field_behavior) = REQUIRED, (.google.cloud.operation_request_field) = "zone"];</code>
     * @param string $var
     * @return $this
     */
    public function setZone($var)
    {
        GPBUtil::checkString($var, True);
        $this->zone = $var;

        return $this;
    }

}

