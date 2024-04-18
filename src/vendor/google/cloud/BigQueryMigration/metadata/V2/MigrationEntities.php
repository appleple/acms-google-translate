<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: google/cloud/bigquery/migration/v2/migration_entities.proto

namespace GPBMetadata\Google\Cloud\Bigquery\Migration\V2;

class MigrationEntities
{
    public static $is_initialized = false;

    public static function initOnce() {
        $pool = \Google\Protobuf\Internal\DescriptorPool::getGeneratedPool();

        if (static::$is_initialized == true) {
          return;
        }
        \GPBMetadata\Google\Api\FieldBehavior::initOnce();
        \GPBMetadata\Google\Api\Resource::initOnce();
        \GPBMetadata\Google\Cloud\Bigquery\Migration\V2\MigrationErrorDetails::initOnce();
        \GPBMetadata\Google\Cloud\Bigquery\Migration\V2\MigrationMetrics::initOnce();
        \GPBMetadata\Google\Cloud\Bigquery\Migration\V2\TranslationConfig::initOnce();
        \GPBMetadata\Google\Protobuf\Timestamp::initOnce();
        \GPBMetadata\Google\Rpc\ErrorDetails::initOnce();
        $pool->internalAddGeneratedFile(
            '
�
;google/cloud/bigquery/migration/v2/migration_entities.proto"google.cloud.bigquery.migration.v2google/api/resource.proto@google/cloud/bigquery/migration/v2/migration_error_details.proto:google/cloud/bigquery/migration/v2/migration_metrics.proto;google/cloud/bigquery/migration/v2/translation_config.protogoogle/protobuf/timestamp.protogoogle/rpc/error_details.proto"�
MigrationWorkflow
name (	B�A�A
display_name (	O
tasks (2@.google.cloud.bigquery.migration.v2.MigrationWorkflow.TasksEntryO
state (2;.google.cloud.bigquery.migration.v2.MigrationWorkflow.StateB�A/
create_time (2.google.protobuf.Timestamp4
last_update_time (2.google.protobuf.Timestamp_

TasksEntry
key (	@
value (21.google.cloud.bigquery.migration.v2.MigrationTask:8"Q
State
STATE_UNSPECIFIED 	
DRAFT
RUNNING

PAUSED
	COMPLETED:u�Ar
2bigquerymigration.googleapis.com/MigrationWorkflow<projects/{project}/locations/{location}/workflows/{workflow}"�
MigrationTaskb
translation_config_details (2<.google.cloud.bigquery.migration.v2.TranslationConfigDetailsH 
id (	B�A�A
type (	K
state (27.google.cloud.bigquery.migration.v2.MigrationTask.StateB�A4
processing_error (2.google.rpc.ErrorInfoB�A/
create_time (2.google.protobuf.Timestamp4
last_update_time (2.google.protobuf.Timestamp"r
State
STATE_UNSPECIFIED 
PENDING
ORCHESTRATING
RUNNING

PAUSED
	SUCCEEDED

FAILEDB
task_details"�
MigrationSubtask
name (	B�A�A
task_id (	
type (	N
state (2:.google.cloud.bigquery.migration.v2.MigrationSubtask.StateB�A4
processing_error (2.google.rpc.ErrorInfoB�A\\
resource_error_details (27.google.cloud.bigquery.migration.v2.ResourceErrorDetailB�A
resource_error_count (/
create_time (2.google.protobuf.Timestamp4
last_update_time (2.google.protobuf.Timestamp?
metrics (2..google.cloud.bigquery.migration.v2.TimeSeries"v
State
STATE_UNSPECIFIED 

ACTIVE
RUNNING
	SUCCEEDED

FAILED

PAUSED
PENDING_DEPENDENCY:��A�
1bigquerymigration.googleapis.com/MigrationSubtaskOprojects/{project}/locations/{location}/workflows/{workflow}/subtasks/{subtask}B�
&com.google.cloud.bigquery.migration.v2BMigrationEntitiesProtoPZDcloud.google.com/go/bigquery/migration/apiv2/migrationpb;migrationpb�"Google.Cloud.BigQuery.Migration.V2�"Google\\Cloud\\BigQuery\\Migration\\V2bproto3'
        , true);

        static::$is_initialized = true;
    }
}

