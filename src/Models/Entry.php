<?php

namespace Acms\Plugins\GoogleTranslate\Models;

use Acms\Plugins\GoogleTranslate\Contracts\Model;
use Acms\Services\Facades\Application;
use DB;
use SQL;
use Field;
use Common;
use Acms\Services\Entry\Helper as EntryHelper;

class Entry extends Model
{
    private const SORT_ENTRY    = 1;
    private const SORT_USER     = 2;
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
        $this->datetime = $now;
        $this->start_datetime = '1000-01-01 00:00:00';
        $this->end_datetime = '9999-12-31 23:59:59';
        $this->posted_datetime = $now;
        $this->updated_datetime = $now;
        $this->summary_range = null;
        $this->indexing = 'on';
        $this->primary_image = null;
        $this->current_rev_id = 0;
        $this->last_update_user_id = 1;
        $this->hash = md5(SYSTEM_GENERATED_DATETIME . $now);
        $this->category_id = null;
        $this->user_id = 1;
        $this->form_id = 0;
        $this->blog_id = 1;
        $this->fields = new Field();
        $this->units = [];

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
        if (method_exists(EntryHelper::class, 'saveColumn')) {
            $this->units = loadColumn($this->id);
            foreach ($this->units as & $unit) {
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

            foreach ($this->units as & $unit) {
                $type = detectUnitTypeSpecifier($unit->getType());
                if ($type === 'custom') {
                    $unit->setField6(acmsUnserialize($unit->getField6()));
                }
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
        if (method_exists(EntryHelper::class, 'saveColumn')) {
            $entryHelper = new EntryHelper();
            $entryHelper->saveColumn($this->units, $this->id, $this->blog_id);
        } else {
            $unitRepository = Application::make('unit-repository');
            assert($unitRepository instanceof \Acms\Services\Unit\Repository);
            $unitRepository->saveUnits($this->units, $this->id, $this->blog_id);
        }

        Common::saveField('eid', $this->id, $this->fields);
        Common::saveFulltext('eid', $this->id, Common::loadEntryFulltext($this->id));
    }

    /**
     * Delete entry
     */
    public function delete()
    {
        Entry::entryDelete($this->id);
        Entry::revisionDelete($this->id);
    }

    /**
     * @param string $type
     * @return int
     */
    protected function getNextSort($type)
    {
        $DB = DB::singleton(dsn());
        $SQL = SQL::newSelect('entry');

        switch ($type) {
            case self::SORT_ENTRY:
                $SQL->setSelect('entry_sort');
                $SQL->addWhereOpr('entry_blog_id', $this->blog_id);
                $SQL->setOrder('entry_sort', 'DESC');
                break;
            case self::SORT_USER:
                $SQL->setSelect('entry_user_sort');
                $SQL->addWhereOpr('entry_user_id', $this->user_id);
                $SQL->addWhereOpr('entry_blog_id', $this->blog_id);
                $SQL->setOrder('entry_user_sort', 'DESC');
                break;
            case self::SORT_CATEGORY:
                $SQL->setSelect('entry_category_sort');
                $SQL->addWhereOpr('entry_category_id', $this->category_id);
                $SQL->addWhereOpr('entry_blog_id', $this->blog_id);
                $SQL->setOrder('entry_category_sort', 'DESC');
                break;
            default:
                return 0;
        }

        $SQL->setLimit(1);
        $next = intval($DB->query($SQL->get(dsn()), 'one')) + 1;

        return $next;
    }
}
