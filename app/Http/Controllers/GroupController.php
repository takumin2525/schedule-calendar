<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Group;
use App\Models\User;

class GroupController extends Controller
{
    // web.phpの Route::get('/groups', [GroupController::class, 'index']); から呼ばれる
    public function index()
    {
        $groups = auth()->user()->groups;

        return view('groups.index', compact('groups'));
    }

    public function create()
    {
        return view('groups.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        $group = Group::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'owner_id' => auth()->id(),
        ]);

        // 重複防止のためsyncWithoutDetachingを使用
        $group->users()->syncWithoutDetaching([auth()->id()]);

        return redirect()->route('groups.index');
    }

    public function show($id)
    {
        $group = auth()->user()->groups()->findOrFail($id);

        return view('groups.show', compact('group'));
    }

    // メンバー追加
    public function addMember(Request $request, $id)
    {
        $group = auth()->user()->groups()->findOrFail($id);

        if ($group->owner_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
        ], [
            'email.required' => 'メールアドレスを入力してください。',
            'email.email' => 'メールアドレスの形式で入力してください。',
            'email.exists' => 'こちらのメールアドレスは登録されていません。',
        ]);

        $user = User::where('email', $validated['email'])->firstOrFail();

        if ($group->users()->where('users.id', $user->id)->exists()) {
            return redirect()->route('groups.show', $group->id)
                ->withErrors(['email' => 'このユーザーはすでにメンバーです。']);
        }

        $group->users()->syncWithoutDetaching([$user->id]);

        return redirect()->route('groups.show', $group->id)
            ->with('success', 'メンバーを追加しました！');
    }

    // メンバー削除
    public function removeMember($groupId, $userId)
    {
        $group = auth()->user()->groups()->findOrFail($groupId);

        if ($group->owner_id !== auth()->id()) {
            abort(403);
        }

        if ((int) $userId === (int) $group->owner_id) {
            abort(403);
        }

        if (!$group->users()->where('users.id', $userId)->exists()) {
            abort(404);
        }

        $group->users()->detach($userId);

        return redirect()->route('groups.show', $group->id)
            ->with('success', 'メンバーを追放しました。');
    }
}