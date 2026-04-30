<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use Illuminate\Validation\ValidationException;

class EventController extends Controller
{
    public function index($groupId)
    {
        $group = auth()->user()->groups()->findOrFail($groupId);
        // ログインユーザーが所属しているグループのみ取得

        $events = Event::with('user')
            ->where('group_id', $group->id)
            ->get();

        $data = [];

        foreach ($events as $event) {
            $title = '';

            if ($event->title) {
                $title .= $event->title . ' ';
            }

            if ($event->is_all_day) {
                $title .= '終日OK';
            } elseif ($event->start_time && $event->end_time) {
                $title .= substr($event->start_time, 0, 5) . '-' . substr($event->end_time, 0, 5);
            }

            if (!$event->title && !$event->is_all_day && !$event->start_time && !$event->end_time) {
                $title = '予定';
            }

            $data[] = [
                'id' => $event->id,
                'title' => $title,
                'start' => $event->event_date,
                'extendedProps' => [
                    'raw_title' => $event->title,
                    'start_time' => $event->start_time,
                    'end_time' => $event->end_time,
                    'is_all_day' => $event->is_all_day ? 1 : 0,
                    'is_mine' => $event->user_id === auth()->id() ? 1 : 0,
                    'user_name' => $event->user->name,
                ]
            ];
        }

        return response()->json($data);
    }

    public function store(Request $request, $groupId)
    {
        $group = auth()->user()->groups()->findOrFail($groupId);

        $validated = $request->validate([
            'title' => ['nullable', 'string', 'max:255'],
            'event_date' => ['required', 'date'],
            'start_time' => ['nullable', 'date_format:H:i:s'],
            'end_time' => ['nullable', 'date_format:H:i:s'],
            'is_all_day' => ['nullable', 'boolean'],
        ]);

        $isAllDay = !empty($validated['is_all_day']);

        if (!$isAllDay && (!($validated['start_time'] ?? null) || !($validated['end_time'] ?? null))) {
            throw ValidationException::withMessages([
                'start_time' => '開始時間と終了時間を入力してください。'
            ]);
        }

        if (!$isAllDay && strtotime($validated['start_time']) >= strtotime($validated['end_time'])) {
            throw ValidationException::withMessages([
                'end_time' => '終了時間は開始時間より後にしてください。'
            ]);
        }

        Event::create([
            'group_id' => $group->id,
            'user_id' => auth()->id(),
            'title' => $validated['title'] ?? null,
            'event_date' => $validated['event_date'],
            'start_time' => $isAllDay ? null : ($validated['start_time'] ?? null),
            'end_time' => $isAllDay ? null : ($validated['end_time'] ?? null),
            'is_all_day' => $isAllDay ? 1 : 0,
        ]);

        return response()->json(['message' => '保存しました']);
    }

    public function update(Request $request, $groupId, $eventId)
    {
        $group = auth()->user()->groups()->findOrFail($groupId);

        $validated = $request->validate([
            'title' => ['nullable', 'string', 'max:255'],
            'event_date' => ['required', 'date'],
            'start_time' => ['nullable', 'date_format:H:i:s'],
            'end_time' => ['nullable', 'date_format:H:i:s'],
            'is_all_day' => ['nullable', 'boolean'],
        ]);

        $isAllDay = !empty($validated['is_all_day']);

        if (!$isAllDay && (!($validated['start_time'] ?? null) || !($validated['end_time'] ?? null))) {
            throw ValidationException::withMessages([
                'start_time' => '開始時間と終了時間を入力してください。'
            ]);
        }

        if (!$isAllDay && strtotime($validated['start_time']) >= strtotime($validated['end_time'])) {
            throw ValidationException::withMessages([
                'end_time' => '終了時間は開始時間より後にしてください。'
            ]);
        }

        $event = Event::where('group_id', $group->id)
            ->where('user_id', auth()->id()) // 自分の予定のみ更新可能
            ->findOrFail($eventId);

        $event->update([
            'title' => $validated['title'] ?? null,
            'event_date' => $validated['event_date'],
            'start_time' => $isAllDay ? null : ($validated['start_time'] ?? null),
            'end_time' => $isAllDay ? null : ($validated['end_time'] ?? null),
            'is_all_day' => $isAllDay ? 1 : 0,
        ]);

        return response()->json(['message' => '更新しました！']);
    }

    public function destroy($groupId, $eventId)
    {
        $group = auth()->user()->groups()->findOrFail($groupId);

        $event = Event::where('group_id', $group->id)
            ->where('user_id', auth()->id()) // 自分の予定のみ削除可能
            ->findOrFail($eventId);

        $event->delete();

        return response()->json(['message' => '削除しました！']);
    }

    public function getByDate($groupId, $eventDate)
    {
        $group = auth()->user()->groups()->findOrFail($groupId);

        $events = Event::with('user')
            ->where('group_id', $group->id)
            ->where('event_date', $eventDate)
            ->orderBy('is_all_day', 'desc')
            ->orderBy('start_time', 'asc')
            ->get();

        $data = [];

        foreach ($events as $event) {
            $data[] = [
                'id' => $event->id,
                'user_id' => $event->user_id,
                'user_name' => $event->user->name,
                'title' => $event->title,
                'event_date' => $event->event_date,
                'start_time' => $event->start_time,
                'end_time' => $event->end_time,
                'is_all_day' => $event->is_all_day ? 1 : 0,
                'is_mine' => $event->user_id === auth()->id() ? 1 : 0,
            ];
        }

        return response()->json($data);
    }
}