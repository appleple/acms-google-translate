<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: google/cloud/dialogflow/v2/session_entity_type.proto

namespace GPBMetadata\Google\Cloud\Dialogflow\V2;

class SessionEntityType
{
    public static $is_initialized = false;

    public static function initOnce() {
        $pool = \Google\Protobuf\Internal\DescriptorPool::getGeneratedPool();

        if (static::$is_initialized == true) {
          return;
        }
        \GPBMetadata\Google\Api\Annotations::initOnce();
        \GPBMetadata\Google\Api\Client::initOnce();
        \GPBMetadata\Google\Api\FieldBehavior::initOnce();
        \GPBMetadata\Google\Api\Resource::initOnce();
        \GPBMetadata\Google\Cloud\Dialogflow\V2\EntityType::initOnce();
        \GPBMetadata\Google\Protobuf\GPBEmpty::initOnce();
        \GPBMetadata\Google\Protobuf\FieldMask::initOnce();
        $pool->internalAddGeneratedFile(
            '
�%
4google/cloud/dialogflow/v2/session_entity_type.protogoogle.cloud.dialogflow.v2google/api/client.protogoogle/api/field_behavior.protogoogle/api/resource.proto,google/cloud/dialogflow/v2/entity_type.protogoogle/protobuf/empty.proto google/protobuf/field_mask.proto"�
SessionEntityType
name (	B�Ac
entity_override_mode (2@.google.cloud.dialogflow.v2.SessionEntityType.EntityOverrideModeB�AD
entities (2-.google.cloud.dialogflow.v2.EntityType.EntityB�A"�
EntityOverrideMode$
 ENTITY_OVERRIDE_MODE_UNSPECIFIED !
ENTITY_OVERRIDE_MODE_OVERRIDE#
ENTITY_OVERRIDE_MODE_SUPPLEMENT:��A�
+dialogflow.googleapis.com/SessionEntityTypeEprojects/{project}/agent/sessions/{session}/entityTypes/{entity_type}mprojects/{project}/agent/environments/{environment}/users/{user}/sessions/{session}/entityTypes/{entity_type}Zprojects/{project}/locations/{location}/agent/sessions/{session}/entityTypes/{entity_type}�projects/{project}/locations/{location}/agent/environments/{environment}/users/{user}/sessions/{session}/entityTypes/{entity_type}"�
ListSessionEntityTypesRequestC
parent (	B3�A�A-+dialogflow.googleapis.com/SessionEntityType
	page_size (B�A

page_token (	B�A"�
ListSessionEntityTypesResponseK
session_entity_types (2-.google.cloud.dialogflow.v2.SessionEntityType
next_page_token (	"`
GetSessionEntityTypeRequestA
name (	B3�A�A-
+dialogflow.googleapis.com/SessionEntityType"�
CreateSessionEntityTypeRequestC
parent (	B3�A�A-+dialogflow.googleapis.com/SessionEntityTypeO
session_entity_type (2-.google.cloud.dialogflow.v2.SessionEntityTypeB�A"�
UpdateSessionEntityTypeRequestO
session_entity_type (2-.google.cloud.dialogflow.v2.SessionEntityTypeB�A4
update_mask (2.google.protobuf.FieldMaskB�A"c
DeleteSessionEntityTypeRequestA
name (	B3�A�A-
+dialogflow.googleapis.com/SessionEntityType2�
SessionEntityTypes�
ListSessionEntityTypes9.google.cloud.dialogflow.v2.ListSessionEntityTypesRequest:.google.cloud.dialogflow.v2.ListSessionEntityTypesResponse"�����4/v2/{parent=projects/*/agent/sessions/*}/entityTypesZMK/v2/{parent=projects/*/agent/environments/*/users/*/sessions/*}/entityTypesZB@/v2/{parent=projects/*/locations/*/agent/sessions/*}/entityTypesZYW/v2/{parent=projects/*/locations/*/agent/environments/*/users/*/sessions/*}/entityTypes�Aparent�
GetSessionEntityType7.google.cloud.dialogflow.v2.GetSessionEntityTypeRequest-.google.cloud.dialogflow.v2.SessionEntityType"�����4/v2/{name=projects/*/agent/sessions/*/entityTypes/*}ZMK/v2/{name=projects/*/agent/environments/*/users/*/sessions/*/entityTypes/*}ZB@/v2/{name=projects/*/locations/*/agent/sessions/*/entityTypes/*}ZYW/v2/{name=projects/*/locations/*/agent/environments/*/users/*/sessions/*/entityTypes/*}�Aname�
CreateSessionEntityType:.google.cloud.dialogflow.v2.CreateSessionEntityTypeRequest-.google.cloud.dialogflow.v2.SessionEntityType"�����"4/v2/{parent=projects/*/agent/sessions/*}/entityTypes:session_entity_typeZb"K/v2/{parent=projects/*/agent/environments/*/users/*/sessions/*}/entityTypes:session_entity_typeZW"@/v2/{parent=projects/*/locations/*/agent/sessions/*}/entityTypes:session_entity_typeZn"W/v2/{parent=projects/*/locations/*/agent/environments/*/users/*/sessions/*}/entityTypes:session_entity_type�Aparent,session_entity_type�
UpdateSessionEntityType:.google.cloud.dialogflow.v2.UpdateSessionEntityTypeRequest-.google.cloud.dialogflow.v2.SessionEntityType"�����2H/v2/{session_entity_type.name=projects/*/agent/sessions/*/entityTypes/*}:session_entity_typeZv2_/v2/{session_entity_type.name=projects/*/agent/environments/*/users/*/sessions/*/entityTypes/*}:session_entity_typeZk2T/v2/{session_entity_type.name=projects/*/locations/*/agent/sessions/*/entityTypes/*}:session_entity_typeZ�2k/v2/{session_entity_type.name=projects/*/locations/*/agent/environments/*/users/*/sessions/*/entityTypes/*}:session_entity_type�Asession_entity_type�Asession_entity_type,update_mask�
DeleteSessionEntityType:.google.cloud.dialogflow.v2.DeleteSessionEntityTypeRequest.google.protobuf.Empty"�����*4/v2/{name=projects/*/agent/sessions/*/entityTypes/*}ZM*K/v2/{name=projects/*/agent/environments/*/users/*/sessions/*/entityTypes/*}ZB*@/v2/{name=projects/*/locations/*/agent/sessions/*/entityTypes/*}ZY*W/v2/{name=projects/*/locations/*/agent/environments/*/users/*/sessions/*/entityTypes/*}�Anamex�Adialogflow.googleapis.com�AYhttps://www.googleapis.com/auth/cloud-platform,https://www.googleapis.com/auth/dialogflowB�
com.google.cloud.dialogflow.v2BSessionEntityTypeProtoPZ>cloud.google.com/go/dialogflow/apiv2/dialogflowpb;dialogflowpb��DF�Google.Cloud.Dialogflow.V2bproto3'
        , true);

        static::$is_initialized = true;
    }
}

