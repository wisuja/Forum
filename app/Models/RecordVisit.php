<?php

namespace App\Models;

use Illuminate\Support\Facades\Redis;

trait RecordVisit {
  
  public function recordVisit() 
  {
      return Redis::incr($this->cacheKey());
  }
  
  public function visits() 
  {
      return Redis::get($this->cacheKey()) ?? 0;
  }

  public function resetVisits()
  {
      return Redis::del($this->cacheKey());
  }

  protected function cacheKey() 
  {
      return "threads.{$this->id}.visits";
  }
}