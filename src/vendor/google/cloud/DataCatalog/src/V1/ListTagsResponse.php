<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: google/cloud/datacatalog/v1/datacatalog.proto

namespace Google\Cloud\DataCatalog\V1;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Response message for
 * [ListTags][google.cloud.datacatalog.v1.DataCatalog.ListTags].
 *
 * Generated from protobuf message <code>google.cloud.datacatalog.v1.ListTagsResponse</code>
 */
class ListTagsResponse extends \Google\Protobuf\Internal\Message
{
    /**
     * [Tag][google.cloud.datacatalog.v1.Tag] details.
     *
     * Generated from protobuf field <code>repeated .google.cloud.datacatalog.v1.Tag tags = 1;</code>
     */
    private $tags;
    /**
     * Pagination token of the next results page. Empty if there are
     * no more items in results.
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
     *     @type array<\Google\Cloud\DataCatalog\V1\Tag>|\Google\Protobuf\Internal\RepeatedField $tags
     *           [Tag][google.cloud.datacatalog.v1.Tag] details.
     *     @type string $next_page_token
     *           Pagination token of the next results page. Empty if there are
     *           no more items in results.
     * }
     */
    public function __construct($data = NULL) {
        \GPBMetadata\Google\Cloud\Datacatalog\V1\Datacatalog::initOnce();
        parent::__construct($data);
    }

    /**
     * [Tag][google.cloud.datacatalog.v1.Tag] details.
     *
     * Generated from protobuf field <code>repeated .google.cloud.datacatalog.v1.Tag tags = 1;</code>
     * @return \Google\Protobuf\Internal\RepeatedField
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * [Tag][google.cloud.datacatalog.v1.Tag] details.
     *
     * Generated from protobuf field <code>repeated .google.cloud.datacatalog.v1.Tag tags = 1;</code>
     * @param array<\Google\Cloud\DataCatalog\V1\Tag>|\Google\Protobuf\Internal\RepeatedField $var
     * @return $this
     */
    public function setTags($var)
    {
        $arr = GPBUtil::checkRepeatedField($var, \Google\Protobuf\Internal\GPBType::MESSAGE, \Google\Cloud\DataCatalog\V1\Tag::class);
        $this->tags = $arr;

        return $this;
    }

    /**
     * Pagination token of the next results page. Empty if there are
     * no more items in results.
     *
     * Generated from protobuf field <code>string next_page_token = 2;</code>
     * @return string
     */
    public function getNextPageToken()
    {
        return $this->next_page_token;
    }

    /**
     * Pagination token of the next results page. Empty if there are
     * no more items in results.
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

