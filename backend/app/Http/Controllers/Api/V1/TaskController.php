<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\TaskAssignment;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $query = Task::with(['assignments.employee.person', 'createdBy.person'])
            ->where('is_active', true);

        if ($request->has('category')) {
            $query->where('category', $request->category);
        }

        if ($request->has('priority')) {
            $query->where('priority', $request->priority);
        }

        $tasks = $query->orderBy('created_at', 'desc')->paginate($request->per_page ?? 20);

        return response()->json([
            'data' => $tasks->map(function ($task) {
                return [
                    'id' => $task->id,
                    'title' => $task->title,
                    'description' => $task->description,
                    'priority' => $task->priority,
                    'priority_color' => $task->priority_color,
                    'category' => $task->category,
                    'estimated_minutes' => $task->estimated_minutes,
                    'requires_evidence' => $task->requires_evidence,
                    'is_recurring' => $task->is_recurring,
                    'active_assignments' => $task->assignments->whereIn('status', ['pending', 'in_progress'])->count(),
                    'completed_assignments' => $task->assignments->whereIn('status', ['completed', 'approved'])->count(),
                ];
            }),
            'meta' => [
                'current_page' => $tasks->currentPage(),
                'last_page' => $tasks->lastPage(),
                'per_page' => $tasks->perPage(),
                'total' => $tasks->total(),
            ],
        ]);
    }

    public function myTasks(Request $request)
    {
        $request->validate(['employee_id' => 'required|exists:employees,id']);

        $assignments = TaskAssignment::with(['task', 'employee.person'])
            ->where('employee_id', $request->employee_id)
            ->whereDate('assigned_at', now()->toDateString())
            ->orderBy('due_time')
            ->get();

        return response()->json([
            'data' => $assignments->map(function ($assignment) {
                return [
                    'id' => $assignment->id,
                    'task_id' => $assignment->task_id,
                    'title' => $assignment->task?->title,
                    'description' => $assignment->task?->description,
                    'priority' => $assignment->effective_priority,
                    'priority_color' => $assignment->task?->priority_color,
                    'status' => $assignment->status,
                    'due_date' => $assignment->due_date,
                    'due_time' => $assignment->due_time?->format('H:i'),
                    'started_at' => $assignment->started_at?->format('H:i'),
                    'completed_at' => $assignment->completed_at?->format('H:i'),
                    'requires_evidence' => $assignment->task?->requires_evidence,
                    'evidence_type' => $assignment->task?->evidence_type,
                    'is_overdue' => $assignment->isOverdue(),
                    'duration_minutes' => $assignment->duration_minutes,
                ];
            }),
            'summary' => [
                'total' => $assignments->count(),
                'pending' => $assignments->where('status', 'pending')->count(),
                'in_progress' => $assignments->where('status', 'in_progress')->count(),
                'completed' => $assignments->whereIn('status', ['completed', 'approved'])->count(),
                'overdue' => $assignments->filter->isOverdue()->count(),
            ],
        ]);
    }

    public function startTask(Request $request)
    {
        $request->validate(['assignment_id' => 'required|exists:task_assignments,id']);
        $assignment = TaskAssignment::findOrFail($request->assignment_id);

        if ($assignment->status !== 'pending') {
            return response()->json(['message' => 'La tarea ya fue iniciada o completada'], 422);
        }

        $assignment->update([
            'status' => 'in_progress',
            'started_at' => now(),
        ]);

        return response()->json(['message' => 'Tarea iniciada', 'assignment' => $assignment->fresh()]);
    }

    public function completeTask(Request $request)
    {
        $request->validate([
            'assignment_id' => 'required|exists:task_assignments,id',
            'evidence_url' => 'nullable|string',
            'evidence_description' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $assignment = TaskAssignment::findOrFail($request->assignment_id);

        if ($assignment->status !== 'in_progress') {
            return response()->json(['message' => 'La tarea debe estar en progreso para completarla'], 422);
        }

        $assignment->update([
            'status' => 'completed',
            'completed_at' => now(),
            'evidence_url' => $request->evidence_url,
            'evidence_description' => $request->evidence_description,
            'notes' => $request->notes,
        ]);

        return response()->json(['message' => 'Tarea completada', 'assignment' => $assignment->fresh()]);
    }

    public function assignTask(Request $request)
    {
        $request->validate([
            'task_id' => 'required|exists:tasks,id',
            'employee_id' => 'required|exists:employees,id',
            'due_date' => 'required|date',
            'due_time' => 'nullable|date_format:H:i',
            'priority_override' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $assignment = TaskAssignment::create([
            'task_id' => $request->task_id,
            'employee_id' => $request->employee_id,
            'assigned_by' => auth()->id(),
            'due_date' => $request->due_date,
            'due_time' => $request->due_time ? now()->parse($request->due_time) : null,
            'priority_override' => $request->priority_override,
            'notes' => $request->notes,
        ]);

        return response()->json(['message' => 'Tarea asignada', 'assignment' => $assignment], 201);
    }
}
