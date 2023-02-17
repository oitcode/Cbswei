<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TeamMember extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'team_member';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'team_member_id';

    protected $fillable = [
         'name', 'phone',
         'email', 'comment',
         'team_id', 'image_path',
         'address',
    ];


    /*-------------------------------------------------------------------------
     * Relationships
     *-------------------------------------------------------------------------
     *
     */

    /*
     * team table.
     *
     */
    public function team()
    {
        return $this->belongsTo('App\Team', 'team_id', 'team_id');
    }
}
