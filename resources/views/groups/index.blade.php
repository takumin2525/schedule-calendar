<style>
  body {
    font-family: sans-serif;
    background: #f7f8fb;
    color: #222;
    margin: 0;
    padding: 24px;
  }

  .page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
  }

  .page-title {
    margin: 0;
    font-size: 32px;
    font-weight: bold;
  }

  .logout-button {
    background: #374151;
    color: #fff;
    border: none;
    padding: 8px 14px;
    border-radius: 8px;
    cursor: pointer;
    font-size: 14px;
  }

  .logout-button:hover {
    opacity: 0.9;
  }

  .create-button {
    display: inline-block;
    margin-bottom: 16px;
    padding: 8px 14px;
    background: #2f6fed;
    color: #fff;
    border-radius: 8px;
    text-decoration: none;
  }

  .create-button:hover {
    opacity: 0.9;
  }

  .group-link {
    text-decoration: none;
    color: #222;
  }

  .group-link:hover {
    text-decoration: underline;
  }

  .group-list {
    padding-left: 24px;
  }

  .group-list li {
    margin-bottom: 8px;
  }
</style>

<div class="page-header">
  <h1 class="page-title">グループ一覧</h1>

  <form method="POST" action="{{ route('logout') }}">
    @csrf

    <button type="submit" class="logout-button">
      ログアウト
    </button>
  </form>
</div>

<a href="{{ route('groups.create') }}" class="create-button">
  新しいグループを作成
</a>

@if ($groups->isEmpty())
  <p>グループがまだありません</p>
@else
  <ul class="group-list">
    @foreach ($groups as $group)
      <li>
        <a href="{{ route('groups.show', $group->id) }}" class="group-link">
          {{ $group->name }}
        </a>
      </li>
    @endforeach
  </ul>
@endif