<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: google/cloud/aiplatform/v1/dataset_service.proto

namespace Google\Cloud\AIPlatform\V1;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Runtime operation information for
 * [DatasetService.ExportData][google.cloud.aiplatform.v1.DatasetService.ExportData].
 *
 * Generated from protobuf message <code>google.cloud.aiplatform.v1.ExportDataOperationMetadata</code>
 */
class ExportDataOperationMetadata extends \Google\Protobuf\Internal\Message
{
    /**
     * The common part of the operation metadata.
     *
     * Generated from protobuf field <code>.google.cloud.aiplatform.v1.GenericOperationMetadata generic_metadata = 1;</code>
     */
    private $generic_metadata = null;
    /**
     * A Google Cloud Storage directory which path ends with '/'. The exported
     * data is stored in the directory.
     *
     * Generated from protobuf field <code>string gcs_output_directory = 2;</code>
     */
    private $gcs_output_directory = '';

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type \Google\Cloud\AIPlatform\V1\GenericOperationMetadata $generic_metadata
     *           The common part of the operation metadata.
     *     @type string $gcs_output_directory
     *           A Google Cloud Storage directory which path ends with '/'. The exported
     *           data is stored in the directory.
     * }
     */
    public function __construct($data = NULL) {
        \GPBMetadata\Google\Cloud\Aiplatform\V1\DatasetService::initOnce();
        parent::__construct($data);
    }

    /**
     * The common part of the operation metadata.
     *
     * Generated from protobuf field <code>.google.cloud.aiplatform.v1.GenericOperationMetadata generic_metadata = 1;</code>
     * @return \Google\Cloud\AIPlatform\V1\GenericOperationMetadata|null
     */
    public function getGenericMetadata()
    {
        return $this->generic_metadata;
    }

    public function hasGenericMetadata()
    {
        return isset($this->generic_metadata);
    }

    public function clearGenericMetadata()
    {
        unset($this->generic_metadata);
    }

    /**
     * The common part of the operation metadata.
     *
     * Generated from protobuf field <code>.google.cloud.aiplatform.v1.GenericOperationMetadata generic_metadata = 1;</code>
     * @param \Google\Cloud\AIPlatform\V1\GenericOperationMetadata $var
     * @return $this
     */
    public function setGenericMetadata($var)
    {
        GPBUtil::checkMessage($var, \Google\Cloud\AIPlatform\V1\GenericOperationMetadata::class);
        $this->generic_metadata = $var;

        return $this;
    }

    /**
     * A Google Cloud Storage directory which path ends with '/'. The exported
     * data is stored in the directory.
     *
     * Generated from protobuf field <code>string gcs_output_directory = 2;</code>
     * @return string
     */
    public function getGcsOutputDirectory()
    {
        return $this->gcs_output_directory;
    }

    /**
     * A Google Cloud Storage directory which path ends with '/'. The exported
     * data is stored in the directory.
     *
     * Generated from protobuf field <code>string gcs_output_directory = 2;</code>
     * @param string $var
     * @return $this
     */
    public function setGcsOutputDirectory($var)
    {
        GPBUtil::checkString($var, True);
        $this->gcs_output_directory = $var;

        return $this;
    }

}

