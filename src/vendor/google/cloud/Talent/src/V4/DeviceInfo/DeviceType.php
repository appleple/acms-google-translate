<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: google/cloud/talent/v4/common.proto

namespace Google\Cloud\Talent\V4\DeviceInfo;

use UnexpectedValueException;

/**
 * An enumeration describing an API access portal and exposure mechanism.
 *
 * Protobuf type <code>google.cloud.talent.v4.DeviceInfo.DeviceType</code>
 */
class DeviceType
{
    /**
     * The device type isn't specified.
     *
     * Generated from protobuf enum <code>DEVICE_TYPE_UNSPECIFIED = 0;</code>
     */
    const DEVICE_TYPE_UNSPECIFIED = 0;
    /**
     * A desktop web browser, such as, Chrome, Firefox, Safari, or Internet
     * Explorer)
     *
     * Generated from protobuf enum <code>WEB = 1;</code>
     */
    const WEB = 1;
    /**
     * A mobile device web browser, such as a phone or tablet with a Chrome
     * browser.
     *
     * Generated from protobuf enum <code>MOBILE_WEB = 2;</code>
     */
    const MOBILE_WEB = 2;
    /**
     * An Android device native application.
     *
     * Generated from protobuf enum <code>ANDROID = 3;</code>
     */
    const ANDROID = 3;
    /**
     * An iOS device native application.
     *
     * Generated from protobuf enum <code>IOS = 4;</code>
     */
    const IOS = 4;
    /**
     * A bot, as opposed to a device operated by human beings, such as a web
     * crawler.
     *
     * Generated from protobuf enum <code>BOT = 5;</code>
     */
    const BOT = 5;
    /**
     * Other devices types.
     *
     * Generated from protobuf enum <code>OTHER = 6;</code>
     */
    const OTHER = 6;

    private static $valueToName = [
        self::DEVICE_TYPE_UNSPECIFIED => 'DEVICE_TYPE_UNSPECIFIED',
        self::WEB => 'WEB',
        self::MOBILE_WEB => 'MOBILE_WEB',
        self::ANDROID => 'ANDROID',
        self::IOS => 'IOS',
        self::BOT => 'BOT',
        self::OTHER => 'OTHER',
    ];

    public static function name($value)
    {
        if (!isset(self::$valueToName[$value])) {
            throw new UnexpectedValueException(sprintf(
                    'Enum %s has no name defined for value %s', __CLASS__, $value));
        }
        return self::$valueToName[$value];
    }


    public static function value($name)
    {
        $const = __CLASS__ . '::' . strtoupper($name);
        if (!defined($const)) {
            throw new UnexpectedValueException(sprintf(
                    'Enum %s has no value defined for name %s', __CLASS__, $name));
        }
        return constant($const);
    }
}


