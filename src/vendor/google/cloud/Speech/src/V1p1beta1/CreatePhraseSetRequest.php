<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: google/cloud/speech/v1p1beta1/cloud_speech_adaptation.proto

namespace Google\Cloud\Speech\V1p1beta1;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Message sent by the client for the `CreatePhraseSet` method.
 *
 * Generated from protobuf message <code>google.cloud.speech.v1p1beta1.CreatePhraseSetRequest</code>
 */
class CreatePhraseSetRequest extends \Google\Protobuf\Internal\Message
{
    /**
     * Required. The parent resource where this phrase set will be created.
     * Format:
     * `projects/{project}/locations/{location}`
     * Speech-to-Text supports three locations: `global`, `us` (US North America),
     * and `eu` (Europe). If you are calling the `speech.googleapis.com`
     * endpoint, use the `global` location. To specify a region, use a
     * [regional endpoint](https://cloud.google.com/speech-to-text/docs/endpoints)
     * with matching `us` or `eu` location value.
     *
     * Generated from protobuf field <code>string parent = 1 [(.google.api.field_behavior) = REQUIRED, (.google.api.resource_reference) = {</code>
     */
    private $parent = '';
    /**
     * Required. The ID to use for the phrase set, which will become the final
     * component of the phrase set's resource name.
     * This value should restrict to letters, numbers, and hyphens, with the first
     * character a letter, the last a letter or a number, and be 4-63 characters.
     *
     * Generated from protobuf field <code>string phrase_set_id = 2 [(.google.api.field_behavior) = REQUIRED];</code>
     */
    private $phrase_set_id = '';
    /**
     * Required. The phrase set to create.
     *
     * Generated from protobuf field <code>.google.cloud.speech.v1p1beta1.PhraseSet phrase_set = 3 [(.google.api.field_behavior) = REQUIRED];</code>
     */
    private $phrase_set = null;

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type string $parent
     *           Required. The parent resource where this phrase set will be created.
     *           Format:
     *           `projects/{project}/locations/{location}`
     *           Speech-to-Text supports three locations: `global`, `us` (US North America),
     *           and `eu` (Europe). If you are calling the `speech.googleapis.com`
     *           endpoint, use the `global` location. To specify a region, use a
     *           [regional endpoint](https://cloud.google.com/speech-to-text/docs/endpoints)
     *           with matching `us` or `eu` location value.
     *     @type string $phrase_set_id
     *           Required. The ID to use for the phrase set, which will become the final
     *           component of the phrase set's resource name.
     *           This value should restrict to letters, numbers, and hyphens, with the first
     *           character a letter, the last a letter or a number, and be 4-63 characters.
     *     @type \Google\Cloud\Speech\V1p1beta1\PhraseSet $phrase_set
     *           Required. The phrase set to create.
     * }
     */
    public function __construct($data = NULL) {
        \GPBMetadata\Google\Cloud\Speech\V1P1Beta1\CloudSpeechAdaptation::initOnce();
        parent::__construct($data);
    }

    /**
     * Required. The parent resource where this phrase set will be created.
     * Format:
     * `projects/{project}/locations/{location}`
     * Speech-to-Text supports three locations: `global`, `us` (US North America),
     * and `eu` (Europe). If you are calling the `speech.googleapis.com`
     * endpoint, use the `global` location. To specify a region, use a
     * [regional endpoint](https://cloud.google.com/speech-to-text/docs/endpoints)
     * with matching `us` or `eu` location value.
     *
     * Generated from protobuf field <code>string parent = 1 [(.google.api.field_behavior) = REQUIRED, (.google.api.resource_reference) = {</code>
     * @return string
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Required. The parent resource where this phrase set will be created.
     * Format:
     * `projects/{project}/locations/{location}`
     * Speech-to-Text supports three locations: `global`, `us` (US North America),
     * and `eu` (Europe). If you are calling the `speech.googleapis.com`
     * endpoint, use the `global` location. To specify a region, use a
     * [regional endpoint](https://cloud.google.com/speech-to-text/docs/endpoints)
     * with matching `us` or `eu` location value.
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
     * Required. The ID to use for the phrase set, which will become the final
     * component of the phrase set's resource name.
     * This value should restrict to letters, numbers, and hyphens, with the first
     * character a letter, the last a letter or a number, and be 4-63 characters.
     *
     * Generated from protobuf field <code>string phrase_set_id = 2 [(.google.api.field_behavior) = REQUIRED];</code>
     * @return string
     */
    public function getPhraseSetId()
    {
        return $this->phrase_set_id;
    }

    /**
     * Required. The ID to use for the phrase set, which will become the final
     * component of the phrase set's resource name.
     * This value should restrict to letters, numbers, and hyphens, with the first
     * character a letter, the last a letter or a number, and be 4-63 characters.
     *
     * Generated from protobuf field <code>string phrase_set_id = 2 [(.google.api.field_behavior) = REQUIRED];</code>
     * @param string $var
     * @return $this
     */
    public function setPhraseSetId($var)
    {
        GPBUtil::checkString($var, True);
        $this->phrase_set_id = $var;

        return $this;
    }

    /**
     * Required. The phrase set to create.
     *
     * Generated from protobuf field <code>.google.cloud.speech.v1p1beta1.PhraseSet phrase_set = 3 [(.google.api.field_behavior) = REQUIRED];</code>
     * @return \Google\Cloud\Speech\V1p1beta1\PhraseSet|null
     */
    public function getPhraseSet()
    {
        return $this->phrase_set;
    }

    public function hasPhraseSet()
    {
        return isset($this->phrase_set);
    }

    public function clearPhraseSet()
    {
        unset($this->phrase_set);
    }

    /**
     * Required. The phrase set to create.
     *
     * Generated from protobuf field <code>.google.cloud.speech.v1p1beta1.PhraseSet phrase_set = 3 [(.google.api.field_behavior) = REQUIRED];</code>
     * @param \Google\Cloud\Speech\V1p1beta1\PhraseSet $var
     * @return $this
     */
    public function setPhraseSet($var)
    {
        GPBUtil::checkMessage($var, \Google\Cloud\Speech\V1p1beta1\PhraseSet::class);
        $this->phrase_set = $var;

        return $this;
    }

}
