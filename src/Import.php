<?php

namespace Acms\Plugins\GoogleTranslate;

use Acms\Plugins\GoogleTranslate\Models\Entry as EntryModel;
use Acms\Plugins\GoogleTranslate\Exceptions\NotFoundException;
use App;
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
     * @throws \Acms\Plugins\GoogleTranslate\Exceptions\NotFoundException
     */
    public function import($eid, $json)
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
        $this->buildEntry($model, $entry);
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
     */
    protected function buildEntry(& $model, $entry)
    {
        // entry
        foreach ($model->columns as $column) {
            if (property_exists($entry, $column) && $column !== 'id') {
                $model->{$column} = $entry->{$column};
            }
        }

        // unit
        if (property_exists($entry, 'units') && is_array($entry->units)) {
            foreach ($entry->units as $new) {
                $clid = $new->clid;
                foreach ($model->units as $i => $current) {
                    if ($current['clid'] === $clid) {
                        $type = detectUnitTypeSpecifier($new->type);
                        switch ($type) {
                            case 'text':
                                $current['text'] = $new->text;
                                break;
                            case 'table':
                                $current['table'] = $new->table;
                                break;
                            case 'media':
                            case 'image':
                                $current['caption'] = $new->caption;
                                $current['alt'] = $new->alt;
                                break;
                            case 'file':
                                $current['caption'] = $new->caption;
                                break;
                        }
                        $current['id'] = uniqueString();
                        $model->units[$i] = $current;
                        break;
                    }
                }
            }
        }

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
