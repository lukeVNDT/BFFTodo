<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TodayTsk extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'completed', 'approved', 'taskId','project_id'];

    public function taskbyidproject() {
        return $this->belongsTo(Project::class, 'project_id');
    }
}
