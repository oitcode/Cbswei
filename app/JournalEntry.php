<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JournalEntry extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'journal_entry';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'journal_entry_id';

    protected $fillable = [
        'date', 'notes',
    ];


    /*-------------------------------------------------------------------------
     * Relationships
     *-------------------------------------------------------------------------
     *
     */

    /*
     * journal_entry_item table.
     *
     */
    public function journalEntryItems()
    {
        return $this->hasMany('App\JournalEntryItem', 'journal_entry_id', 'journal_entry_id');
    }
}
