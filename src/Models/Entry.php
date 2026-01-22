<?php

namespace Acms\Plugins\GoogleTranslate\Models;

use Acms\Plugins\GoogleTranslate\Contracts\Model;
use Acms\Services\Facades\Application;
use Acms\Services\Facades\Entry as EntryHelper;
use Acms\Services\Facades\Common;
use Acms\Services\Facades\Database as DB;
use SQL;
use Field;

class Entry extends Model
{
    private const SORT_ENTRY = 1;
    private const SORT_USER = 2;
    private const SORT_CATEGORY = 3;

    /**
     * @var \Field
     */
    protected $fields;

    /**
     * Model constructor.
     *
     * @param null|object $entry
     */
    public function __construct($entry = null)
    {
        parent::__construct();

        $now = date('Y-m-d H:i:s', REQUEST_TIME);
        $this->id = 0;
        $this->status = 'open';
        $this->approval = 'none';
        $this->form_status = '';
        $this->title = '';
        $this->link = '';
        $this->datetime = $now;
        $this->start_datetime = '1000-01-01 00:00:00';
        $this->end_datetime = '9999-12-31 23:59:59';
        $this->posted_datetime = $now;
        $this->updated_datetime = $now;
        $this->summary_range = null;
        $this->indexing = 'on';
        $this->members_only = 'off';
        $this->primary_image = null;
        $this->current_rev_id = 0;
        $this->reserve_rev_id = 0;
        $this->last_update_user_id = 1;
        $this->hash = md5(SYSTEM_GENERATED_DATETIME . $now);
        $this->category_id = null;
        $this->user_id = 1;
        $this->form_id = 0;
        $this->blog_id = 1;
        $this->delete_uid = null;
        $this->lock_datetime = '1000-01-01 00:00:00';
        $this->lock_uid = 0;

        $this->fields = new Field();
        $this->units = null;

        if (empty($entry)) {
            $this->init();
            $this->update = false;
        } else {
            $this->load($entry);
            $this->update = true;
        }
    }

    /**
     * Get columns
     *
     * @return string[]
     */
    protected function getColumns()
    {
        if (count($this->columns) > 0) {
            return $this->columns;
        }
        $DB = DB::singleton(dsn());
        $SQL = SQL::newSelect('entry');
        $SQL->setLimit(0);
        $statement = $DB->query($SQL->get(dsn()), 'exec');
        $columns = [];
        for ($i = 0; $i < $statement->columnCount(); $i++) {
            $meta = $statement->getColumnMeta($i);
            $columns[] = preg_replace('/entry_/', '', $meta['name']);
        }
        return $columns;
    }

    /**
     * @static
     * @param int $id
     * @return self|false
     */
    public static function find($id)
    {
        $DB = DB::singleton(dsn());
        $SQL = SQL::newSelect('entry');
        $SQL->addWhereOpr('entry_id', $id);
        if ($entry = $DB->query($SQL->get(dsn()), 'row')) {
            $self = new self($entry);
            $self->id = $id;
            return $self;
        } else {
            return false;
        }
    }

    /**
     * Initialize model
     *
     * @return void
     */
    public function init()
    {
        $DB = DB::singleton(dsn());

        $this->id = intval($DB->query(SQL::nextval('entry_id', dsn()), 'seq'));
        $this->sort = $this->getNextSort(self::SORT_ENTRY);
        $this->user_sort = $this->getNextSort(self::SORT_USER);
        $this->category_sort = $this->getNextSort(self::SORT_CATEGORY);
        $this->title = 'csv_import-' . $this->id;
        $this->code = 'entry-' . $this->id . '.html';
    }

    /**
     * Load model
     *
     * @param array $item
     * @return void
     */
    public function load($item)
    {
        foreach ($item as $key => $value) {
            $key = preg_replace('/entry_/', '', $key);
            $this->{$key} = $value;
        }

        $this->fields = loadEntryField($this->id);

        // ablogcms v3.1.23 からリファクタリングによりメソッドがなくなっている問題の解決
        if (method_exists(\Acms\Services\Entry\Helper::class, 'saveColumn')) {
            $this->units = loadColumn($this->id);
            foreach ($this->units as &$unit) {
                $type = detectUnitTypeSpecifier($unit['type']);
                if ($type === 'custom') {
                    $unit['field'] = acmsUnserialize($unit['field']);
                }
                $unit['id'] = uniqueString();
            }
        } else {
            /** @var \Acms\Services\Unit\Repository $unitService */
            $unitService = Application::make('unit-repository');
            $this->units = $unitService->loadUnits($this->id);

            foreach ($this->units as &$unit) {
                $unit->setTempId(uniqueString());
            }
        }
    }

    /**
     * Update or Insert entry
     *
     * @return void
     */
    public function save()
    {
        $DB = DB::singleton(dsn());

        if ($this->update) {
            $SQL = SQL::newUpdate('entry');
            foreach ($this->columns as $column) {
                $SQL->addUpdate('entry_' . $column, $this->{$column});
            }
            $SQL->addWhereOpr('entry_id', $this->id);
            $SQL->addWhereOpr('entry_blog_id', $this->blog_id);
            $DB->query($SQL->get(dsn()), 'exec');
        } else {
            $SQL = SQL::newInsert('entry');
            foreach ($this->columns as $column) {
                $SQL->addInsert('entry_' . $column, $this->{$column});
            }
            $DB->query($SQL->get(dsn()), 'exec');
        }

        // ablogcms v3.1.23 からリファクタリングによりメソッドがなくなっている問題の解決
        if (method_exists(\Acms\Services\Entry\Helper::class, 'saveColumn')) {
            EntryHelper::saveColumn($this->units, $this->id, $this->blog_id);
        } else {
            $unitRepository = Application::make('unit-repository');
            assert($unitRepository instanceof \Acms\Services\Unit\Repository);
            $unitRepository->saveAllUnits($this->units, $this->id, $this->blog_id);
        }

        Common::saveField('eid', $this->id, $this->fields);
        Common::saveFulltext('eid', $this->id, Common::loadEntryFulltext($this->id));
    }

    /**
     * Delete entry
     */
    public function delete()
    {
        EntryHelper::entryDelete($this->id);
        EntryHelper::revisionDelete($this->id);
    }

    /**
     * @param string $type
     * @return int
     */
    protected function getNextSort($type)
    {
        $entryRepository = Application::make('entry.repository');
        assert($entryRepository instanceof \Acms\Services\Entry\EntryRepository);

        if ($type === self::SORT_ENTRY) {
            return $entryRepository->nextSort($this->blog_id);
        } elseif ($type === self::SORT_USER) {
            return $entryRepository->nextUserSort($this->user_id, $this->blog_id);
        } elseif ($type === self::SORT_CATEGORY) {
            return $entryRepository->nextCategorySort($this->category_id, $this->blog_id);
        }
        return 0;
    }
}
