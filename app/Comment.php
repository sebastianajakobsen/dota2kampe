<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Baum\Node;

use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Node
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $table = 'comments';

    // protected $scoped = array('news_id');
    // 'parent_id' column name
    protected $parentColumn = 'parent_id';

    // 'lft' column name
    protected $leftColumn = 'lft';

    // 'rgt' column name
    protected $rightColumn = 'rgt';

    // 'depth' column name
    protected $depthColumn = 'depth';

    // guard attributes from mass-assignment
    protected $guarded = array('id', 'parent_id', 'lft', 'rgt', 'depth');


    public function user()
    {
        return $this->belongsTo('App\User')->withTrashed();
    }

    public function commentable()
    {
        return $this->morphTo();
    }



}
