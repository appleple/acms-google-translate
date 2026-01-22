<?php

namespace Acms\Plugins\GoogleTranslate;

use Acms\Plugins\GoogleTranslate\Models\Entry as EntryModel;
use Acms\Plugins\GoogleTranslate\Exceptions\NotFoundException;
use SQL;
use DB;

class Import
{
    /**
     * @var string
     */
    protected $source;

    /**
     * @var string
     */
    protected $schema;

    /**
     * Import constructor.
     * @param $schema
     */
    public function __construct($schema)
    {
        $this->schema = $schema;
    }

    /**
     * JSONをオブジェクトにデコード
     *
     * @param string $json
     * @return object
     */
    public function decode($json)
    {
        $data = json_decode($json);
        // $errors = $this->validator->validate($data, $this->schema);

        // if (count($errors) > 0) {
        //     foreach ($errors as $error) {
        //         App::exception($error); // stack exception
        //     }
        // }
        // App::checkException(); // throw exception

        return $data;
    }

    /**
     * @param int $eid
     * @param string $json
     * @param \Acms\Services\Unit\UnitCollection $units
     * @throws \Acms\Plugins\GoogleTranslate\Exceptions\NotFoundException
     */
    public function import($eid, $json, $units)
    {
        $entry = $this->decode($json);
        if ($entry->langCode === 'ja') {
            throw new \RuntimeException('Can\'t update original entry.');
        }
        if (empty($entry)) {
            throw new \RuntimeException('Invalid request data.');
        }
        $model = EntryModel::find($eid);
        if (empty($model)) {
            throw new NotFoundException('Target not found.');
        }
        $this->buildEntry($model, $entry, $units);
        $model->save();
        $this->updateStatus($eid);
    }

    protected function updateStatus($eid)
    {
        $sql = SQL::newUpdate('google_translate_entry');
        $sql->addUpdate('status', 'complete');
        $sql->addWhereOpr('relation_eid', $eid);
        DB::query($sql->get(dsn()), 'exec');
    }

    /**
     * @param \Acms\Plugins\GoogleTranslate\Contracts\Model $model
     * @param object $entry
     * @param \Acms\Services\Unit\UnitCollection $units
     */
    protected function buildEntry(&$model, $entry, $units)
    {
        // entry
        foreach ($model->columns as $column) {
            if (property_exists($entry, $column) && $column !== 'id') {
                $model->{$column} = $entry->{$column};
            }
        }

        // units
        $model->units = $units;

        // field
        if (property_exists($entry, 'fields') && is_array($entry->fields)) {
            foreach ($entry->fields as $field) {
                $search = !(property_exists($field, 'search') && $field->search === 'off');
                $model->fields->delete($field->key);
                $model->fields->add($field->key, $field->value);
                $model->fields->setMeta($field->key, 'search', $search);
            }
        }

        // field group
        if (property_exists($entry, 'fieldGroups') && is_array($entry->fieldGroups)) {
            foreach ($entry->fieldGroups as $group) {
                $name = $group->group;
                $model->fields->delete('@' . $name);
                foreach ($group->items as $i => $item) {
                    foreach ($item->fields as $field) {
                        if ($i === 0) {
                            $model->fields->delete($field->key);

                            $model->fields->add('@' . $name, $field->key);
                            $model->fields->setMeta('@' . $name, 'search', false);
                            $model->fields->setMeta($field->key, 'search', $field->search === 'on');
                        }
                        $model->fields->add($field->key, $field->value);
                    }
                }
            }
        }
    }
}
