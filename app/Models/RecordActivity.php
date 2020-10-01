<?php

namespace App\Models;

use ReflectionClass;

trait RecordActivity
{

  protected static function bootRecordActivity()
  {
    if (auth()->guest()) return;

    foreach (static::getActivityEventsToRecord() as $event) {
      static::$event(function ($model) use ($event) {
        $model->recordActivity($event);
      });
    };
  }

  protected static function getActivityEventsToRecord()
  {
    return ['created', 'updated', 'deleted'];
  }

  protected function recordActivity($event)
  {
    $this->activity()->create([
      'user_id' => auth()->id(),
      'type' => $this->getActivityType($event)
    ]);
  }

  public function activity()
  {
    return $this->morphMany(Activity::class, 'subject');
  }

  protected function getActivityType($event)
  {
    $type = strtolower((new ReflectionClass($this))->getShortName());

    return "{$event}_{$type}";
  }
}
