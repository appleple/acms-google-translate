<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: google/cloud/datalabeling/v1beta1/data_labeling_service.proto

namespace Google\Cloud\DataLabeling\V1beta1;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Request message for GetExample
 *
 * Generated from protobuf message <code>google.cloud.datalabeling.v1beta1.GetExampleRequest</code>
 */
class GetExampleRequest extends \Google\Protobuf\Internal\Message
{
    /**
     * Required. Name of example, format:
     * projects/{project_id}/datasets/{dataset_id}/annotatedDatasets/
     * {annotated_dataset_id}/examples/{example_id}
     *
     * Generated from protobuf field <code>string name = 1 [(.google.api.field_behavior) = REQUIRED, (.google.api.resource_reference) = {</code>
     */
    private $name = '';
    /**
     * Optional. An expression for filtering Examples. Filter by
     * annotation_spec.display_name is supported. Format
     * "annotation_spec.display_name = {display_name}"
     *
     * Generated from protobuf field <code>string filter = 2 [(.google.api.field_behavior) = OPTIONAL];</code>
     */
    private $filter = '';

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type string $name
     *           Required. Name of example, format:
     *           projects/{project_id}/datasets/{dataset_id}/annotatedDatasets/
     *           {annotated_dataset_id}/examples/{example_id}
     *     @type string $filter
     *           Optional. An expression for filtering Examples. Filter by
     *           annotation_spec.display_name is supported. Format
     *           "annotation_spec.display_name = {display_name}"
     * }
     */
    public function __construct($data = NULL) {
        \GPBMetadata\Google\Cloud\Datalabeling\V1Beta1\DataLabelingService::initOnce();
        parent::__construct($data);
    }

    /**
     * Required. Name of example, format:
     * projects/{project_id}/datasets/{dataset_id}/annotatedDatasets/
     * {annotated_dataset_id}/examples/{example_id}
     *
     * Generated from protobuf field <code>string name = 1 [(.google.api.field_behavior) = REQUIRED, (.google.api.resource_reference) = {</code>
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Required. Name of example, format:
     * projects/{project_id}/datasets/{dataset_id}/annotatedDatasets/
     * {annotated_dataset_id}/examples/{example_id}
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
     * Optional. An expression for filtering Examples. Filter by
     * annotation_spec.display_name is supported. Format
     * "annotation_spec.display_name = {display_name}"
     *
     * Generated from protobuf field <code>string filter = 2 [(.google.api.field_behavior) = OPTIONAL];</code>
     * @return string
     */
    public function getFilter()
    {
        return $this->filter;
    }

    /**
     * Optional. An expression for filtering Examples. Filter by
     * annotation_spec.display_name is supported. Format
     * "annotation_spec.display_name = {display_name}"
     *
     * Generated from protobuf field <code>string filter = 2 [(.google.api.field_behavior) = OPTIONAL];</code>
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
