<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: google/cloud/workflows/executions/v1beta/executions.proto

namespace Google\Cloud\Workflows\Executions\V1beta;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Request for the
 * [ListExecutions][google.cloud.workflows.executions.v1beta.Executions.ListExecutions]
 * method.
 *
 * Generated from protobuf message <code>google.cloud.workflows.executions.v1beta.ListExecutionsRequest</code>
 */
class ListExecutionsRequest extends \Google\Protobuf\Internal\Message
{
    /**
     * Required. Name of the workflow for which the executions should be listed.
     * Format: projects/{project}/locations/{location}/workflows/{workflow}
     *
     * Generated from protobuf field <code>string parent = 1 [(.google.api.field_behavior) = REQUIRED, (.google.api.resource_reference) = {</code>
     */
    private $parent = '';
    /**
     * Maximum number of executions to return per call.
     * Max supported value depends on the selected Execution view: it's 10000 for
     * BASIC and 100 for FULL. The default value used if the field is not
     * specified is 100, regardless of the selected view. Values greater than
     * the max value will be coerced down to it.
     *
     * Generated from protobuf field <code>int32 page_size = 2;</code>
     */
    private $page_size = 0;
    /**
     * A page token, received from a previous `ListExecutions` call.
     * Provide this to retrieve the subsequent page.
     * When paginating, all other parameters provided to `ListExecutions` must
     * match the call that provided the page token.
     *
     * Generated from protobuf field <code>string page_token = 3;</code>
     */
    private $page_token = '';
    /**
     * Optional. A view defining which fields should be filled in the returned executions.
     * The API will default to the BASIC view.
     *
     * Generated from protobuf field <code>.google.cloud.workflows.executions.v1beta.ExecutionView view = 4 [(.google.api.field_behavior) = OPTIONAL];</code>
     */
    private $view = 0;

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type string $parent
     *           Required. Name of the workflow for which the executions should be listed.
     *           Format: projects/{project}/locations/{location}/workflows/{workflow}
     *     @type int $page_size
     *           Maximum number of executions to return per call.
     *           Max supported value depends on the selected Execution view: it's 10000 for
     *           BASIC and 100 for FULL. The default value used if the field is not
     *           specified is 100, regardless of the selected view. Values greater than
     *           the max value will be coerced down to it.
     *     @type string $page_token
     *           A page token, received from a previous `ListExecutions` call.
     *           Provide this to retrieve the subsequent page.
     *           When paginating, all other parameters provided to `ListExecutions` must
     *           match the call that provided the page token.
     *     @type int $view
     *           Optional. A view defining which fields should be filled in the returned executions.
     *           The API will default to the BASIC view.
     * }
     */
    public function __construct($data = NULL) {
        \GPBMetadata\Google\Cloud\Workflows\Executions\V1Beta\Executions::initOnce();
        parent::__construct($data);
    }

    /**
     * Required. Name of the workflow for which the executions should be listed.
     * Format: projects/{project}/locations/{location}/workflows/{workflow}
     *
     * Generated from protobuf field <code>string parent = 1 [(.google.api.field_behavior) = REQUIRED, (.google.api.resource_reference) = {</code>
     * @return string
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Required. Name of the workflow for which the executions should be listed.
     * Format: projects/{project}/locations/{location}/workflows/{workflow}
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
     * Maximum number of executions to return per call.
     * Max supported value depends on the selected Execution view: it's 10000 for
     * BASIC and 100 for FULL. The default value used if the field is not
     * specified is 100, regardless of the selected view. Values greater than
     * the max value will be coerced down to it.
     *
     * Generated from protobuf field <code>int32 page_size = 2;</code>
     * @return int
     */
    public function getPageSize()
    {
        return $this->page_size;
    }

    /**
     * Maximum number of executions to return per call.
     * Max supported value depends on the selected Execution view: it's 10000 for
     * BASIC and 100 for FULL. The default value used if the field is not
     * specified is 100, regardless of the selected view. Values greater than
     * the max value will be coerced down to it.
     *
     * Generated from protobuf field <code>int32 page_size = 2;</code>
     * @param int $var
     * @return $this
     */
    public function setPageSize($var)
    {
        GPBUtil::checkInt32($var);
        $this->page_size = $var;

        return $this;
    }

    /**
     * A page token, received from a previous `ListExecutions` call.
     * Provide this to retrieve the subsequent page.
     * When paginating, all other parameters provided to `ListExecutions` must
     * match the call that provided the page token.
     *
     * Generated from protobuf field <code>string page_token = 3;</code>
     * @return string
     */
    public function getPageToken()
    {
        return $this->page_token;
    }

    /**
     * A page token, received from a previous `ListExecutions` call.
     * Provide this to retrieve the subsequent page.
     * When paginating, all other parameters provided to `ListExecutions` must
     * match the call that provided the page token.
     *
     * Generated from protobuf field <code>string page_token = 3;</code>
     * @param string $var
     * @return $this
     */
    public function setPageToken($var)
    {
        GPBUtil::checkString($var, True);
        $this->page_token = $var;

        return $this;
    }

    /**
     * Optional. A view defining which fields should be filled in the returned executions.
     * The API will default to the BASIC view.
     *
     * Generated from protobuf field <code>.google.cloud.workflows.executions.v1beta.ExecutionView view = 4 [(.google.api.field_behavior) = OPTIONAL];</code>
     * @return int
     */
    public function getView()
    {
        return $this->view;
    }

    /**
     * Optional. A view defining which fields should be filled in the returned executions.
     * The API will default to the BASIC view.
     *
     * Generated from protobuf field <code>.google.cloud.workflows.executions.v1beta.ExecutionView view = 4 [(.google.api.field_behavior) = OPTIONAL];</code>
     * @param int $var
     * @return $this
     */
    public function setView($var)
    {
        GPBUtil::checkEnum($var, \Google\Cloud\Workflows\Executions\V1beta\ExecutionView::class);
        $this->view = $var;

        return $this;
    }

}
