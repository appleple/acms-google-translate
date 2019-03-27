<?php

namespace Acms\Plugins\GoogleTranslate;

use DB;
use SQL;
use ACMS_RAM;
use Common;
use Entry;
use Storage;

class DuplicateEntry
{
    public function dupe($eid, $newEid, $targetBid)
    {
        $DB = DB::singleton(dsn());
        $bid = ACMS_RAM::entryBlog($eid);
        if (empty($targetBid)) {
            $targetBid = $bid;
        }

        //--------
        // column
        $map = array();
        $SQL = SQL::newSelect('column');
        $SQL->addWhereOpr('column_entry_id', $eid);
        $SQL->addWhereOpr('column_blog_id', $bid);
        $q  = $SQL->get(dsn());
        if ( $DB->query($q, 'fetch') and ($row = $DB->fetch($q)) ) { do {
            $type = detectUnitTypeSpecifier($row['column_type']);
            switch ( $type ) {
                case 'image':
                    $oldAry = explodeUnitData($row['column_field_2']);
                    $newAry = array();
                    foreach ( $oldAry as $old ) {
                        $info   = pathinfo($old);
                        $dirname= empty($info['dirname']) ? '' : $info['dirname'].'/';
                        $ext    = empty($info['extension']) ? '' : '.'.$info['extension'];
                        $newOld = $dirname.uniqueString().$ext;
                        $path   = ARCHIVES_DIR.$old;
                        $large  = otherSizeImagePath($path, 'large');
                        $tiny   = otherSizeImagePath($path, 'tiny');
                        $square = otherSizeImagePath($path, 'square');
                        $newPath    = ARCHIVES_DIR.$newOld;
                        $newLarge   = otherSizeImagePath($newPath, 'large');
                        $newTiny    = otherSizeImagePath($newPath, 'tiny');
                        $newSquare  = otherSizeImagePath($newPath, 'square');
                        copyFile($path, $newPath);
                        copyFile($large, $newLarge);
                        copyFile($tiny, $newTiny);
                        copyFile($square, $newSquare);
                        $newAry[]   = $newOld;
                    }
                    $row['column_field_2']  = implodeUnitData($newAry);
                    break;
                case 'file':
                    $oldAry = explodeUnitData($row['column_field_2']);
                    $newAry = array();
                    foreach ( $oldAry as $old ) {
                        $info   = pathinfo($old);
                        $dirname= empty($info['dirname']) ? '' : $info['dirname'].'/';
                        $ext    = empty($info['extension']) ? '' : '.'.$info['extension'];
                        $newOld = $dirname.uniqueString().$ext;
                        $path   = ARCHIVES_DIR.$old;
                        $newPath    = ARCHIVES_DIR.$newOld;
                        copyFile($path, $newPath);

                        $newAry[]   = $newOld;
                    }
                    $row['column_field_2']  = implodeUnitData($newAry);
                    break;
                case 'custom':
                    $oldAry = explodeUnitData($row['column_field_6']);
                    $newAry = array();
                    foreach ( $oldAry as $old ) {
                        $Field = acmsUnserialize($old);
                        $this->fieldDupe($Field, $targetBid);

                        $newAry[]   = acmsSerialize($Field);
                    }
                    $row['column_field_6']  = implodeUnitData($newAry);
                default:
                    break;
            }
            $newClid = $DB->query(SQL::nextval('column_id', dsn()), 'seq');
            $map[intval($row['column_id'])] = $newClid;
            $row['column_id']       = $newClid;
            $row['column_entry_id'] = $newEid;
            $row['column_blog_id'] = $targetBid;

            $SQL    = SQL::newInsert('column');
            foreach ( $row as $fd => $val ) {
                $SQL->addInsert($fd, $val);
            }
            $DB->query($SQL->get(dsn()), 'exec');
        } while ( $row = $DB->fetch($q) );}

        //-------
        // entry
        $SQL    = SQL::newSelect('entry');
        $SQL->addWhereOpr('entry_id', $eid);
        $SQL->addWhereOpr('entry_blog_id', $bid);
        $row = $DB->query($SQL->get(dsn()), 'row');
        $title = $row['entry_title'];
        $code = $row['entry_code'];

        $uid    = intval($row['entry_user_id']);
        if ( !($cid = intval($row['entry_category_id'])) ) $cid = null;;

        //------
        // sort
        $SQL    = SQL::newSelect('entry');
        $SQL->setSelect('entry_sort');
        $SQL->addWhereOpr('entry_blog_id', $bid);
        $SQL->setOrder('entry_sort', 'DESC');
        $SQL->setLimit(1);
        $esort  = intval($DB->query($SQL->get(dsn()), 'one')) + 1;

        $SQL    = SQL::newSelect('entry');
        $SQL->setSelect('entry_user_sort');
        $SQL->addWhereOpr('entry_user_id', $uid);
        $SQL->addWhereOpr('entry_blog_id', $bid);
        $SQL->setOrder('entry_user_sort', 'DESC');
        $SQL->setLimit(1);
        $usort  = intval($DB->query($SQL->get(dsn()), 'one')) + 1;

        $SQL    = SQL::newSelect('entry');
        $SQL->setSelect('entry_category_sort');
        $SQL->addWhereOpr('entry_category_id', $cid);
        $SQL->addWhereOpr('entry_blog_id', $bid);
        $SQL->setOrder('entry_category_sort', 'DESC');
        $SQL->setLimit(1);
        $csort  = intval($DB->query($SQL->get(dsn()), 'one')) + 1;

        $row['entry_id']        = $newEid;
        $row['entry_status']    = 'close';
        $row['entry_title']     = $title;
        $row['entry_code']      = $code;
        if (config('google_translate_app_update_datetime_as_duplicate_entry') === 'on') {
            $row['entry_datetime'] = date('Y-m-d H:i:s', REQUEST_TIME);
        }
        $row['entry_posted_datetime']   = date('Y-m-d H:i:s', REQUEST_TIME);
        $row['entry_updated_datetime']  = date('Y-m-d H:i:s', REQUEST_TIME);
        $row['entry_hash']              = md5(SYSTEM_GENERATED_DATETIME.date('Y-m-d H:i:s', REQUEST_TIME));
        if (isset($row['entry_primary_image']) && isset($map[$row['entry_primary_image']]) && !empty($map[$row['entry_primary_image']])) {
            $row['entry_primary_image'] = $map[$row['entry_primary_image']];
        } else {
            $row['entry_primary_image'] = null;
        }
        $row['entry_sort']              = $esort;
        $row['entry_user_sort']         = $usort;
        $row['entry_category_sort']     = $csort;
        $row['entry_user_id']           = SUID;
        $row['entry_blog_id']           = $targetBid;
        $SQL    = SQL::newInsert('entry');
        foreach ( $row as $fd => $val ) {
            if ( $fd == 'entry_current_rev_id' ) {
                continue;
            }
            $SQL->addInsert($fd, $val);
        }
        $DB->query($SQL->get(dsn()), 'exec');

        //-----
        // tag
        $SQL    = SQL::newSelect('tag');
        $SQL->addWhereOpr('tag_entry_id', $eid);
        $SQL->addWhereOpr('tag_blog_id', $bid);
        $q  = $SQL->get(dsn());
        if ( $DB->query($q, 'fetch') and ($row = $DB->fetch($q)) ) { do {
            $row['tag_entry_id'] = $newEid;
            $rpw['tag_blog_id'] = $targetBid;
            $Insert = SQL::newInsert('tag');
            foreach ( $row as $fd => $val ) $Insert->addInsert($fd, $val);
            $DB->query($Insert->get(dsn()), 'exec');
        } while ( $row = $DB->fetch($q) ); }

        //--------------
        // sub category
        $subCategory = loadSubCategories($eid);
        Entry::saveSubCategory($newEid, $cid, implode(',', $subCategory['id']), $targetBid);

        //-------
        // field
        $Field  = loadEntryField($eid);
        $this->fieldDupe($Field, $targetBid);
        Common::saveField('eid', $newEid, $Field, null, null, $targetBid);
        Common::saveFulltext('eid', $newEid, Common::loadEntryFulltext($newEid), $targetBid);

        //---------------
        // related entry
        $this->relationDupe($eid, $newEid);

        //----------
        // geo data
        $this->geoDuplicate($eid, $newEid, $targetBid);
    }

    public function approvalDupe($eid, $newEid, $bid = null)
    {
        if (empty($bid)) {
            $bid = ACMS_RAM::entryBlog($eid);
        }
        $DB         = DB::singleton(dsn());
        $approval   = ACMS_RAM::entryApproval($eid);
        $sourceDir  = ARCHIVES_DIR;
        $sourceRev  = false;

        if ( $approval === 'pre_approval' ) {
            $sourceDir  = REVISON_ARCHIVES_DIR;
            $sourceRev  = true;
        }

        //--------
        // column
        $map    = array();
        if ( $sourceRev ) {
            $SQL    = SQL::newSelect('column_rev');
            $SQL->addWhereOpr('column_rev_id', 1);
        } else {
            $SQL    = SQL::newSelect('column');
        }
        $SQL->addWhereOpr('column_entry_id', $eid);
        $SQL->addWhereOpr('column_blog_id', $bid);
        $q  = $SQL->get(dsn());
        if ( $DB->query($q, 'fetch') and ($row = $DB->fetch($q)) ) { do {
            $type = detectUnitTypeSpecifier($row['column_type']);
            switch ( $type ) {
                case 'image':
                    $oldAry = explodeUnitData($row['column_field_2']);
                    $newAry = array();
                    foreach ( $oldAry as $old ) {
                        $info   = pathinfo($old);
                        $dirname= empty($info['dirname']) ? '' : $info['dirname'].'/';
                        $ext    = empty($info['extension']) ? '' : '.'.$info['extension'];
                        $newOld = $dirname.uniqueString().$ext;
                        $path   = $sourceDir.$old;
                        $large  = otherSizeImagePath($path, 'large');
                        $tiny   = otherSizeImagePath($path, 'tiny');
                        $square = otherSizeImagePath($path, 'square');
                        $newPath    = REVISON_ARCHIVES_DIR.$newOld;
                        $newLarge   = otherSizeImagePath($newPath, 'large');
                        $newTiny    = otherSizeImagePath($newPath, 'tiny');
                        $newSquare  = otherSizeImagePath($newPath, 'square');
                        copyFile($path, $newPath);
                        copyFile($large, $newLarge);
                        copyFile($tiny, $newTiny);
                        copyFile($square, $newSquare);

                        $newAry[]   = $newOld;
                    }
                    $row['column_field_2']  = implodeUnitData($newAry);
                    break;
                case 'file':
                    $oldAry = explodeUnitData($row['column_field_2']);
                    $newAry = array();
                    foreach ( $oldAry as $old ) {
                        $old    = $row['column_field_2'];
                        $info   = pathinfo($old);
                        $dirname= empty($info['dirname']) ? '' : $info['dirname'].'/';
                        $ext    = empty($info['extension']) ? '' : '.'.$info['extension'];
                        $newOld = $dirname.uniqueString().$ext;
                        $path   = $sourceDir.$old;
                        $newPath    = REVISON_ARCHIVES_DIR.$newOld;
                        copyFile($path, $newPath);

                        $newAry[]   = $newOld;
                    }
                    $row['column_field_2']  = implodeUnitData($newAry);
                    break;
                case 'custom':
                    $oldAry = explodeUnitData($row['column_field_6']);
                    $newAry = array();
                    foreach ( $oldAry as $old ) {
                        $Field = acmsUnserialize($old);
                        foreach ( $Field->listFields() as $fd ) {
                            if ( !strpos($fd, '@path') ) {
                                continue;
                            }
                            $base = substr($fd, 0, (-1 * strlen('@path')));
                            $set = false;
                            foreach ( $Field->getArray($fd, true) as $i => $path ) {
                                if ( !Storage::isFile($sourceDir.$path) ) continue;
                                $info       = pathinfo($path);
                                $dirname    = empty($info['dirname']) ? '' : $info['dirname'].'/';
                                Storage::makeDirectory(REVISON_ARCHIVES_DIR.$dirname);
                                $ext        = empty($info['extension']) ? '' : '.'.$info['extension'];
                                $newPath    = $dirname.uniqueString().$ext;

                                $path       = $sourceDir.$path;
                                $largePath  = otherSizeImagePath($path, 'large');
                                $tinyPath   = otherSizeImagePath($path, 'tiny');
                                $squarePath = otherSizeImagePath($path, 'square');

                                $newLargePath   = otherSizeImagePath($newPath, 'large');
                                $newTinyPath    = otherSizeImagePath($newPath, 'tiny');
                                $newSquarePath  = otherSizeImagePath($newPath, 'square');

                                Storage::copy($path, REVISON_ARCHIVES_DIR.$newPath);
                                Storage::copy($largePath, REVISON_ARCHIVES_DIR.$newLargePath);
                                Storage::copy($tinyPath, REVISON_ARCHIVES_DIR.$newTinyPath);
                                Storage::copy($squarePath, REVISON_ARCHIVES_DIR.$newSquarePath);


                                if ( !$set ) {
                                    $Field->delete($fd);
                                    $Field->delete($base.'@largePath');
                                    $Field->delete($base.'@tinyPath');
                                    $Field->delete($base.'@squarePath');
                                    $set = true;
                                }
                                $Field->add($fd, $newPath);
                                if ( Storage::isReadable($largePath) ) {
                                    $Field->add($base.'@largePath', $newLargePath);
                                }
                                if ( Storage::isReadable($tinyPath) ) {
                                    $Field->add($base.'@tinyPath', $newTinyPath);
                                }
                                if ( Storage::isReadable($squarePath) ) {
                                    $Field->add($base.'@squarePath', $newSquarePath);
                                }
                            }
                        }
                        $newAry[]   = acmsSerialize($Field);
                    }
                    $row['column_field_6']  = implodeUnitData($newAry);
                    break;
                default:
                    break;
            }
            $newClid    = $DB->query(SQL::nextval('column_id', dsn()), 'seq');
            $map[intval($row['column_id'])] = $newClid;
            $row['column_id']       = $newClid;
            $row['column_entry_id'] = $newEid;

            $SQL    = SQL::newInsert('column_rev');
            foreach ( $row as $fd => $val ) {
                $SQL->addInsert($fd, $val);
            }
            if ( !$sourceRev ) {
                $SQL->addInsert('column_rev_id', 1);
            }
            $DB->query($SQL->get(dsn()), 'exec');
        } while ( $row = $DB->fetch($q) );}

        //-------
        // entry
        if ( $sourceRev ) {
            $SQL    = SQL::newSelect('entry_rev');
            $SQL->addWhereOpr('entry_rev_id', 1);
        } else {
            $SQL    = SQL::newSelect('entry');
        }
        $SQL->addWhereOpr('entry_id', $eid);
        $SQL->addWhereOpr('entry_blog_id', $bid);
        $row = $DB->query($SQL->get(dsn()), 'row');
        $title = $row['entry_title'];
        $code = $row['entry_code'];

        $uid    = intval($row['entry_user_id']);
        if ( !($cid = intval($row['entry_category_id'])) ) $cid = null;;

        //------
        // sort
        $SQL    = SQL::newSelect('entry');
        $SQL->setSelect('entry_sort');
        $SQL->addWhereOpr('entry_blog_id', $bid);
        $SQL->setOrder('entry_sort', 'DESC');
        $SQL->setLimit(1);
        $esort  = intval($DB->query($SQL->get(dsn()), 'one')) + 1;

        $SQL    = SQL::newSelect('entry');
        $SQL->setSelect('entry_user_sort');
        $SQL->addWhereOpr('entry_user_id', $uid);
        $SQL->addWhereOpr('entry_blog_id', $bid);
        $SQL->setOrder('entry_user_sort', 'DESC');
        $SQL->setLimit(1);
        $usort  = intval($DB->query($SQL->get(dsn()), 'one')) + 1;

        $SQL    = SQL::newSelect('entry');
        $SQL->setSelect('entry_category_sort');
        $SQL->addWhereOpr('entry_category_id', $cid);
        $SQL->addWhereOpr('entry_blog_id', $bid);
        $SQL->setOrder('entry_category_sort', 'DESC');
        $SQL->setLimit(1);
        $csort  = intval($DB->query($SQL->get(dsn()), 'one')) + 1;

        $row['entry_id']        = $newEid;
        $row['entry_status']    = 'close';
        $row['entry_title']     = $title;
        $row['entry_code']      = $code;
        if (config('google_translate_app_update_datetime_as_duplicate_entry') === 'on') {
            $row['entry_datetime'] = date('Y-m-d H:i:s', REQUEST_TIME);
        }
        $row['entry_posted_datetime']   = date('Y-m-d H:i:s', REQUEST_TIME);
        $row['entry_updated_datetime']  = date('Y-m-d H:i:s', REQUEST_TIME);
        $row['entry_hash']              = md5(SYSTEM_GENERATED_DATETIME.date('Y-m-d H:i:s', REQUEST_TIME));
        if (isset($row['entry_primary_image']) && isset($map[$row['entry_primary_image']]) && !empty($map[$row['entry_primary_image']])) {
            $row['entry_primary_image'] = $map[$row['entry_primary_image']];
        } else {
            $row['entry_primary_image'] = null;
        }
        $row['entry_sort']              = $esort;
        $row['entry_user_sort']         = $usort;
        $row['entry_category_sort']     = $csort;
        $row['entry_user_id']           = SUID;
        $SQL    = SQL::newInsert('entry');
        foreach ( $row as $fd => $val ) {
            if ( !in_array($fd, array(
                'entry_approval',
                'entry_approval_public_point',
                'entry_approval_reject_point',
                'entry_last_update_user_id',
                'entry_rev_id',
                'entry_rev_status',
                'entry_rev_memo',
                'entry_rev_user_id',
                'entry_rev_datetime',
                'entry_current_rev_id'
            )) ) {
                $SQL->addInsert($fd, $val);
            }
        }
        $SQL->addInsert('entry_approval', 'pre_approval');
        $SQL->addInsert('entry_last_update_user_id', SUID);
        $DB->query($SQL->get(dsn()), 'exec');

        $SQL    = SQL::newInsert('entry_rev');
        foreach ( $row as $fd => $val ) {
            if ( !in_array($fd, array(
                'entry_current_rev_id',
                'entry_last_update_user_id',
                'entry_rev_id',
                'entry_rev_user_id',
                'entry_rev_datetime'
            )) ) {
                $SQL->addInsert($fd, $val);
            }
        }
        $SQL->addInsert('entry_rev_id', 1);
        $SQL->addInsert('entry_rev_user_id', SUID);
        $SQL->addInsert('entry_rev_datetime', date('Y-m-d H:i:s', REQUEST_TIME));
        $DB->query($SQL->get(dsn()), 'exec');

        //-----
        // tag
        $SQL    = SQL::newSelect('tag');
        $SQL->addWhereOpr('tag_entry_id', $eid);
        $SQL->addWhereOpr('tag_blog_id', $bid);
        $q  = $SQL->get(dsn());
        if ( $DB->query($q, 'fetch') and ($row = $DB->fetch($q)) ) { do {
            $row['tag_entry_id']    = $newEid;
            $Insert = SQL::newInsert('tag_rev');
            foreach ( $row as $fd => $val ) $Insert->addInsert($fd, $val);
            if ( !$sourceRev ) $Insert->addInsert('tag_rev_id', 1);
            $DB->query($Insert->get(dsn()), 'exec');
        } while ( $row = $DB->fetch($q) ); }

        //--------------
        // sub category
        if ($sourceRev) {
            $subCategory = loadSubCategories($eid, 1);
        } else {
            $subCategory = loadSubCategories($eid);
        }
        Entry::saveSubCategory($newEid, $cid, implode(',', $subCategory['id']), $bid, 1);

        //-------
        // field
        if ( $sourceRev ) {
            $Field  = loadEntryField($eid, 1);
        } else {
            $Field  = loadEntryField($eid);
        }

        foreach ( $Field->listFields() as $fd ) {
            $this->conversionId($Field, $fd, $bid);
            if ( !strpos($fd, '@path') ) {
                continue;
            }
            $set = false;
            $base   = substr($fd, 0, (-1 * strlen('@path')));
            foreach ( $Field->getArray($fd, true) as $i => $path ) {
                if ( !Storage::isFile($sourceDir.$path) ) continue;
                $info       = pathinfo($path);
                $dirname    = empty($info['dirname']) ? '' : $info['dirname'].'/';
                Storage::makeDirectory(REVISON_ARCHIVES_DIR.$dirname);
                $ext        = empty($info['extension']) ? '' : '.'.$info['extension'];
                $newPath    = $dirname.uniqueString().$ext;

                $path       = $sourceDir.$path;
                $largePath  = otherSizeImagePath($path, 'large');
                $tinyPath   = otherSizeImagePath($path, 'tiny');
                $squarePath = otherSizeImagePath($path, 'square');

                $newLargePath   = otherSizeImagePath($newPath, 'large');
                $newTinyPath    = otherSizeImagePath($newPath, 'tiny');
                $newSquarePath  = otherSizeImagePath($newPath, 'square');

                Storage::copy($path, REVISON_ARCHIVES_DIR.$newPath);
                Storage::copy($largePath, REVISON_ARCHIVES_DIR.$newLargePath);
                Storage::copy($tinyPath, REVISON_ARCHIVES_DIR.$newTinyPath);
                Storage::copy($squarePath, REVISON_ARCHIVES_DIR.$newSquarePath);

                if ( !$set ) {
                    $Field->delete($fd);
                    $Field->delete($base.'@largePath');
                    $Field->delete($base.'@tinyPath');
                    $Field->delete($base.'@squarePath');
                    $set = true;
                }
                $Field->add($fd, $newPath);
                if ( Storage::isReadable($largePath) ) {
                    $Field->add($base.'@largePath', $newLargePath);
                }
                if ( Storage::isReadable($tinyPath) ) {
                    $Field->add($base.'@tinyPath', $newTinyPath);
                }
                if ( Storage::isReadable($squarePath) ) {
                    $Field->add($base.'@squarePath', $newSquarePath);
                }
            }
        }
        Entry::saveFieldRevision($newEid, $Field, 1);
    }

    protected function relationDupe($eid, $newEid)
    {
        $DB = DB::singleton(dsn());
        $SQL = SQL::newSelect('relationship');
        $SQL->addWhereOpr('relation_id', $eid);
        $all = $DB->query($SQL->get(dsn()), 'all');

        foreach ( $all as $row ) {
            $SQL = SQL::newInsert('relationship');
            $SQL->addInsert('relation_id', $newEid);
            $SQL->addInsert('relation_eid', $row['relation_eid']);
            $SQL->addInsert('relation_order', $row['relation_order']);
            $DB->query($SQL->get(dsn()), 'exec');
        }
    }

    protected function geoDuplicate($eid, $newEid, $targetBid = BID)
    {
        $DB = DB::singleton(dsn());
        $SQL = SQL::newSelect('geo');
        $SQL->addWhereOpr('geo_eid', $eid);
        if ( $row = $DB->query($SQL->get(dsn()), 'row') ) {
            $SQL = SQL::newInsert('geo');
            $SQL->addInsert('geo_eid', $newEid);
            $SQL->addInsert('geo_geometry', $row['geo_geometry']);
            $SQL->addInsert('geo_zoom', $row['geo_zoom']);
            $SQL->addInsert('geo_blog_id', $targetBid);
            $DB->query($SQL->get(dsn()), 'exec');
        }
    }

    protected function fieldDupe(& $Field, $targetBid = BID)
    {
        foreach ( $Field->listFields() as $fd ) {

            $this->conversionId($Field, $fd, $targetBid);

            if ( preg_match('/(.*?)@path$/', $fd, $match) ) {
                $_fd    = $match[1];

                // カスタムフィールドグループ対応
                $ary_path = $Field->getArray($_fd.'@path');
                if( is_array( $ary_path ) && count( $ary_path ) > 0 ){

                    $int_filedindex = 0;
                    $Old_Field = new Field();
                    $Old_Field->set($_fd.'@path',$Field->getArray($_fd.'@path') );
                    $Old_Field->set($_fd.'@largePath',$Field->getArray($_fd.'@largePath') );
                    $Old_Field->set($_fd.'@tinyPath',$Field->getArray($_fd.'@tinyPath') );
                    $Old_Field->set($_fd.'@squarePath',$Field->getArray($_fd.'@squarePath') );

                    foreach( $ary_path as $path ){
                        if ( 1
                            and Storage::isFile(ARCHIVES_DIR.$path)
                            and preg_match('@^(.*?)([^/]+)(\.[^.]+)$@', $path, $match)
                        ) {
                            $dirname    = $match[1];
                            $basename   = $match[2];
                            $extension  = $match[3];

                            $info = array(
                                'field'         => $_fd,
                                'dirname'       => $dirname,
                                'newBasename'   => uniqueString(),
                                'extension'     => $extension,
                            );

                            foreach ( array(
                                          ''          => '@path',
                                          'large-'    => '@largePath',
                                          'tiny-'     => '@tinyPath',
                                          'square-'   => '@squarePath',
                                      ) as $pfx => $name ) {
                                $info['name']   = $name;
                                $info['pfx']    = $pfx;
                                $this->_filesDupe($Field, $Old_Field, $info, $int_filedindex);
                            }
                            $int_filedindex++;
                        }
                    }
                }
            }
        }
    }

    protected function _filesDupe(& $Field, & $Old_Field, $info, $int_filedindex)
    {
        $key = $info['name'];
        $pfx = $info['pfx'];
        $_fd = $info['field'];
        $dirname = $info['dirname'];
        $newBasename = $info['newBasename'];
        $extension = $info['extension'];

        if ( 1
            and $path = $Old_Field->get($_fd.$key, NULL, $int_filedindex)
            and Storage::isFile(ARCHIVES_DIR.$path)
        ) {
            $newPath   = $dirname.$pfx.$newBasename.$extension;

            Storage::copy(ARCHIVES_DIR.$path, ARCHIVES_DIR.$newPath);
            if ( HOOK_ENABLE ) {
                $Hook = ACMS_Hook::singleton();
                $Hook->call('mediaCreate', ARCHIVES_DIR.$newPath);
            }
            if ( $int_filedindex == 0 ) {
                $Field->setField($_fd.$key, $newPath);
            } else {
                $Field->addField($_fd.$key, $newPath);
            }
        }
    }

    /**
     * @param $Field
     * @param $fd
     * @param $targetBid
     */
    protected function conversionId(& $Field, $fd, $targetBid)
    {
        $this->conversionEntryId($Field, $fd, $targetBid);
        $this->conversionBlogId($Field, $fd, $targetBid);
        $this->conversionCategoryId($Field, $fd, $targetBid);
    }

    /**
     * フィールドのeidデータを変換
     *
     * @param $Field
     * @param $fd
     * @param $targetBid
     */
    protected function conversionEntryId(& $Field, $fd, $targetBid)
    {
        $translationEidFields = configArray('translationEidFieldName');
        if (in_array($fd, $translationEidFields)) {
            $eidValue = $Field->getArray($fd);

            $Field->delete($fd);
            foreach ($eidValue as $eid) {
                $SQL = SQL::newSelect('google_translate_entry');
                $SQL->addSelect('relation_eid');
                $SQL->addWhereOpr('base_entry_id', $eid);
                $SQL->addWhereOpr('relation_bid', $targetBid);
                if ($translationEid = DB::query($SQL->get(dsn()), 'one')) {
                    $Field->add($fd, $translationEid);
                } else {
                    $Field->add($fd, $eid);
                }
            }
        }
    }

    /**
     * フィールドのbidデータを変換
     *
     * @param $Field
     * @param $fd
     * @param $targetBid
     */
    protected function conversionBlogId(& $Field, $fd, $targetBid)
    {
        $translationBidFields = configArray('translationBidFieldName');
        if (in_array($fd, $translationBidFields)) {
            $bidValue = $Field->getArray($fd);
            $Field->delete($fd);

            $SQL = SQL::newSelect('google_translate_blog');
            $SQL->addSelect('lang_code');
            $SQL->addWhereOpr('base_blog_id', BID);
            $SQL->addWhereOpr('relation_bid', $targetBid);
            $langCode = DB::query($SQL->get(dsn()), 'one');

            foreach ($bidValue as $bid) {
                $bid = intval($bid);
                $SQL = SQL::newSelect('google_translate_blog');
                $SQL->addSelect('relation_bid');
                $SQL->addWhereOpr('base_blog_id', $bid);
                $SQL->addWhereOpr('lang_code', $langCode);
                if ($translationBid = DB::query($SQL->get(dsn()), 'one')) {
                    $Field->add($fd, $translationBid);
                } else {
                    $Field->add($fd, $bid);
                }
            }
        }
    }

    /**
     * フィールドのcidデータを変換
     *
     * @param $Field
     * @param $fd
     * @param $targetBid
     */
    protected function conversionCategoryId(& $Field, $fd, $targetBid)
    {
        $translationCidFields = configArray('translationCidFieldName');
        if (in_array($fd, $translationCidFields)) {
            $cidValue = $Field->getArray($fd);
            $Field->delete($fd);
            foreach ($cidValue as $cid) {
                $cid = intval($cid);
                if ($targetCid = $this->checkCategory($cid, $targetBid)) {
                    $Field->add($fd, $targetCid);
                } else {
                    $Field->add($fd, $cid);
                }
            }
        }
    }
}
