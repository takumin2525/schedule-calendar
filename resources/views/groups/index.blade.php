<style>
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

  .group-list li {
    margin-bottom: 8px;
  }
</style>

<h1>グループ一覧</h1>

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