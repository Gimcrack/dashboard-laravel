<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
  /**
   * The database table that the model references
   *
   * @var string
   */
  protected $table = 'groups';

  /**
   * The mass assignable fields
   *
   * @var array
   */
  protected $fillable = [
      'name'
    , 'description'
  ];

  /**
     * Polymorphic relationship. Second parameter to morphOne/morphMany
     * should be the same as the prefix for the *_id/*_type fields.
     */
    public function recordLock()
    {
        return $this->morphOne('App\RecordLock', 'lockable');
    }

  /**
   * A group is comprised of many users
   *
   * @return [type] [description]
   */
  public function users()
  {
    return $this->belongsToMany('App\User')->withTimestamps();
  }

  /**
   * A group can be assigned to many modules
   * @method modules
   * @return [type]  [description]
   */
  public function modules()
  {
    return $this->belongsToMany('App\Module')->withTimestamps();

  }
}
