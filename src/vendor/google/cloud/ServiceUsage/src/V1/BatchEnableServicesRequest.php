<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: google/api/serviceusage/v1/serviceusage.proto

namespace Google\Cloud\ServiceUsage\V1;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Request message for the `BatchEnableServices` method.
 *
 * Generated from protobuf message <code>google.api.serviceusage.v1.BatchEnableServicesRequest</code>
 */
class BatchEnableServicesRequest extends \Google\Protobuf\Internal\Message
{
    /**
     * Parent to enable services on.
     * An example name would be:
     * `projects/123` where `123` is the project number.
     * The `BatchEnableServices` method currently only supports projects.
     *
     * Generated from protobuf field <code>string parent = 1;</code>
     */
    private $parent = '';
    /**
     * The identifiers of the services to enable on the project.
     * A valid identifier would be:
     * serviceusage.googleapis.com
     * Enabling services requires that each service is public or is shared with
     * the user enabling the service.
     * A single request can enable a maximum of 20 services at a time. If more
     * than 20 services are specified, the request will fail, and no state changes
     * will occur.
     *
     * Generated from protobuf field <code>repeated string service_ids = 2;</code>
     */
    private $service_ids;

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type string $parent
     *           Parent to enable services on.
     *           An example name would be:
     *           `projects/123` where `123` is the project number.
     *           The `BatchEnableServices` method currently only supports projects.
     *     @type array<string>|\Google\Protobuf\Internal\RepeatedField $service_ids
     *           The identifiers of the services to enable on the project.
     *           A valid identifier would be:
     *           serviceusage.googleapis.com
     *           Enabling services requires that each service is public or is shared with
     *           the user enabling the service.
     *           A single request can enable a maximum of 20 services at a time. If more
     *           than 20 services are specified, the request will fail, and no state changes
     *           will occur.
     * }
     */
    public function __construct($data = NULL) {
        \GPBMetadata\Google\Api\Serviceusage\V1\Serviceusage::initOnce();
        parent::__construct($data);
    }

    /**
     * Parent to enable services on.
     * An example name would be:
     * `projects/123` where `123` is the project number.
     * The `BatchEnableServices` method currently only supports projects.
     *
     * Generated from protobuf field <code>string parent = 1;</code>
     * @return string
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Parent to enable services on.
     * An example name would be:
     * `projects/123` where `123` is the project number.
     * The `BatchEnableServices` method currently only supports projects.
     *
     * Generated from protobuf field <code>string parent = 1;</code>
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
     * The identifiers of the services to enable on the project.
     * A valid identifier would be:
     * serviceusage.googleapis.com
     * Enabling services requires that each service is public or is shared with
     * the user enabling the service.
     * A single request can enable a maximum of 20 services at a time. If more
     * than 20 services are specified, the request will fail, and no state changes
     * will occur.
     *
     * Generated from protobuf field <code>repeated string service_ids = 2;</code>
     * @return \Google\Protobuf\Internal\RepeatedField
     */
    public function getServiceIds()
    {
        return $this->service_ids;
    }

    /**
     * The identifiers of the services to enable on the project.
     * A valid identifier would be:
     * serviceusage.googleapis.com
     * Enabling services requires that each service is public or is shared with
     * the user enabling the service.
     * A single request can enable a maximum of 20 services at a time. If more
     * than 20 services are specified, the request will fail, and no state changes
     * will occur.
     *
     * Generated from protobuf field <code>repeated string service_ids = 2;</code>
     * @param array<string>|\Google\Protobuf\Internal\RepeatedField $var
     * @return $this
     */
    public function setServiceIds($var)
    {
        $arr = GPBUtil::checkRepeatedField($var, \Google\Protobuf\Internal\GPBType::STRING);
        $this->service_ids = $arr;

        return $this;
    }

}
