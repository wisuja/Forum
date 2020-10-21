<?php

namespace App\Filters;

use App\Models\User;

class ThreadFilters extends Filters
{
  protected $filters = ['by', 'popular', 'unanswered'];

  public function by($username)
  {
    $user = User::where('name', $username)->firstOrFail();

    return $this->builder->where('user_id', $user->id);
  }

  public function popular()
  {
    return $this->builder->orderBy('replies_count', 'desc')
                          ->orderBy('visits', 'desc');
  }

  public function unanswered()
  {
    return $this->builder->doesntHave('replies');
  }
}
