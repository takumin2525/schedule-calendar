<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ $group->name }} のカレンダー</title>

  <meta name="csrf-token" content="{{ csrf_token() }}">

  <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>

  <style>
    body {
      font-family: sans-serif;
      margin: 0;
      padding: 0;
      background: #f7f8fb;
      color: #222;
    }

    .page-wrap {
      padding: 24px;
    }

    .page-title {
      margin: 0 0 6px;
      font-size: 32px;
      font-weight: bold;
    }

    .page-subtitle {
      margin: 0 0 18px;
      color: #666;
      font-size: 14px;
    }

    #calendar {
      max-width: 950px;
      margin: 24px auto;
      background: #fff;
      border-radius: 18px;
      box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
      padding: 16px;
      box-sizing: border-box;
    }

    .fc .fc-toolbar-title {
      font-size: 22px;
      font-weight: bold;
    }

    .fc .fc-button {
      border-radius: 10px !important;
      border: none !important;
      box-shadow: none !important;
      padding: 8px 12px !important;
    }

    .fc .fc-daygrid-event {
      border-radius: 8px;
      padding: 2px 6px;
      font-size: 12px;
    }

    .fc .fc-daygrid-day.fc-day-today {
      background: #eef4ff !important;
    }

    #sidebar-backdrop {
      position: fixed;
      inset: 0;
      background: rgba(0, 0, 0, 0.28);
      opacity: 0;
      pointer-events: none;
      transition: opacity 0.25s ease;
      z-index: 998;
    }

    #sidebar-backdrop.open {
      opacity: 1;
      pointer-events: auto;
    }

    #sidebar {
      position: fixed;
      top: 0;
      right: -420px;
      width: 400px;
      max-width: 90%;
      height: 100vh;
      background: #fff;
      z-index: 1000;
      transition: right 0.25s ease;
      display: flex;
      flex-direction: column;
    }

    #sidebar.open {
      right: 0;
    }

    .sidebar-header {
      position: sticky;
      top: 0;
      background: #fff;
      z-index: 2;
      padding: 20px 20px 14px;
      border-bottom: 1px solid #eee;
    }

    .sidebar-header-top {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 12px;
    }

    .sidebar-title {
      margin: 0;
      font-size: 24px;
    }

    .icon-close {
      border: none;
      background: #f3f4f6;
      width: 38px;
      height: 38px;
      border-radius: 50%;
      cursor: pointer;
      font-size: 18px;
    }

    .icon-close:hover {
      background: #e5e7eb;
    }

    #selected-date-text {
      font-size: 18px;
      font-weight: bold;
      margin: 12px 0 0;
      color: #333;
    }

    .sidebar-body {
      flex: 1;
      padding: 18px 20px 32px;
      overflow-y: auto;
    }

    .sidebar-section-title {
      font-size: 15px;
      font-weight: bold;
      margin: 0 0 12px;
      color: #555;
    }

    #day-events-list {
      margin-bottom: 20px;
    }

    .event-card {
      border: 1px solid #ddd;
      border-left: 5px solid #ccc;
      border-radius: 14px;
      padding: 14px;
      margin-bottom: 12px;
      background: #fafafa;
      cursor: pointer;
      transition: transform 0.15s ease, box-shadow 0.15s ease, background 0.15s ease;
    }

    .event-card:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
      background: #fff;
    }

    .event-card.mine {
      border-left-color: #2f6fed;
      background: #eef4ff;
    }

    .event-card.other {
      border-left-color: #b8b8b8;
      background: #f8f8f8;
      cursor: default;
    }

    .event-card.mine:hover {
      background: #e3edff;
    }

    .event-card.other:hover {
      background: #fff;
    }

    .event-card-title {
      font-weight: bold;
      margin-bottom: 8px;
      font-size: 16px;
      line-height: 1.4;
    }

    .event-card-time {
      color: #555;
      font-size: 14px;
      margin-bottom: 6px;
    }

    .event-card-note {
      margin-top: 8px;
      font-size: 13px;
      color: #777;
      line-height: 1.5;
    }

    .badge {
      display: inline-block;
      padding: 3px 10px;
      border-radius: 999px;
      font-size: 12px;
      font-weight: bold;
      margin-right: 6px;
      vertical-align: middle;
    }

    .badge-all-day {
      background: #39a96b;
      color: #fff;
    }

    .badge-mine {
      background: #2f6fed;
      color: #fff;
    }

    .badge-other {
      background: #9aa0a6;
      color: #fff;
    }

    .empty-message {
      color: #666;
      font-size: 14px;
      background: #fafafa;
      border: 1px dashed #ccc;
      border-radius: 12px;
      padding: 16px;
      line-height: 1.7;
    }

    .sidebar-footer {
      position: sticky;
      bottom: 0;
      background: #fff;
      border-top: 1px solid #eee;
      padding: 14px 20px 18px;
      display: flex;
      gap: 10px;
      box-sizing: border-box;
    }

    .sidebar-footer button,
    .form-button-group button,
    .detail-button-group button {
      padding: 10px 16px;
      font-size: 15px;
      cursor: pointer;
      border: none;
      border-radius: 12px;
      transition: opacity 0.15s ease, transform 0.15s ease;
    }

    .sidebar-footer button:hover,
    .form-button-group button:hover,
    .detail-button-group button:hover {
      opacity: 0.94;
      transform: translateY(-1px);
    }

    #open-form-button,
    #save-button,
    #detail-edit-button {
      background: #2f6fed;
      color: white;
    }

    #close-sidebar-button,
    #cancel-button,
    #detail-close-button {
      background: #e5e7eb;
      color: #222;
    }

    #delete-button,
    #detail-delete-button {
      background: #e5484d;
      color: white;
    }

    .modal-overlay {
      position: fixed;
      inset: 0;
      background: rgba(0, 0, 0, 0.45);
      display: none;
      align-items: center;
      justify-content: center;
      z-index: 2000;
      padding: 16px;
      box-sizing: border-box;
    }

    .modal-overlay.open {
      display: flex;
    }

    .modal-box {
      width: 100%;
      max-width: 520px;
      background: #fff;
      border-radius: 20px;
      padding: 24px;
      box-shadow: 0 16px 40px rgba(0, 0, 0, 0.2);
      box-sizing: border-box;
      max-height: 90vh;
      overflow-y: auto;
    }

    .modal-box h3 {
      margin-top: 0;
      margin-bottom: 10px;
      font-size: 24px;
      line-height: 1.4;
    }

    .modal-date {
      font-size: 15px;
      color: #555;
      margin-bottom: 18px;
      font-weight: bold;
    }

    .form-group {
      margin-bottom: 16px;
    }

    .form-group label {
      display: block;
      margin-bottom: 6px;
      font-weight: bold;
    }

    input[type="text"],
    input[type="time"],
    textarea {
      width: 100%;
      max-width: 100%;
      padding: 10px;
      font-size: 16px;
      box-sizing: border-box;
      border: 1px solid #ccc;
      border-radius: 12px;
      background: #fff;
    }

    input[type="text"]:focus,
    input[type="time"]:focus,
    textarea {
      outline: none;
      border-color: #2f6fed;
      box-shadow: 0 0 0 3px rgba(47, 111, 237, 0.12);
    }

    .form-help {
      font-size: 12px;
      color: #777;
      margin-top: 6px;
      line-height: 1.5;
    }

    .form-error {
      display: none;
      margin-bottom: 14px;
      padding: 12px 14px;
      background: #fff1f2;
      color: #b42318;
      border: 1px solid #f4c7cc;
      border-radius: 12px;
      font-size: 14px;
      line-height: 1.6;
    }

    .form-button-group,
    .detail-button-group {
      margin-top: 20px;
      display: flex;
      flex-wrap: wrap;
      gap: 10px;
    }

    .checkbox-label {
      display: flex;
      align-items: center;
      gap: 8px;
      cursor: pointer;
    }

    .detail-line {
      margin-bottom: 16px;
      line-height: 1.6;
    }

    .detail-label {
      font-weight: bold;
      margin-bottom: 4px;
      color: #444;
    }

    /* メンバー関連 */
    .member-section {
      margin-top: 32px;
      padding: 24px;
      background: #fff;
      border-radius: 20px;
      box-shadow: 0 12px 30px rgba(15, 23, 42, 0.08);
    }

    .member-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 16px;
      margin-bottom: 16px;
    }

    .member-header h2 {
      margin: 0;
      font-size: 22px;
    }

    .member-add-button {
      border: none;
      background: #2563eb;
      color: #fff;
      padding: 10px 16px;
      border-radius: 999px;
      font-weight: 700;
      cursor: pointer;
    }

    .member-list {
      list-style: none;
      padding: 0;
      margin: 0;
    }

    .member-item {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 16px;
      padding: 18px 12px;
      border-radius: 12px;
      border-bottom: 1px solid #e5e7eb;
    }

    .member-item:hover {
      background: #f9fafb;
    }

    .member-item form {
      margin: 0;
    }

    .member-item:last-child {
      border-bottom: none;
    }

    .member-info {
      min-width: 0;
    }

    .member-name {
      font-weight: 700;
    }

    .member-email {
      color: #6b7280;
      font-size: 14px;
      margin-top: 4px;
    }

    .member-modal-backdrop {
      display: none;
      position: fixed;
      inset: 0;
      background: rgba(15, 23, 42, 0.35);
      z-index: 900;
    }

    .member-modal {
      display: none;
      position: fixed;
      inset: 0;
      z-index: 901;
      align-items: center;
      justify-content: center;
      padding: 20px;
    }

    .member-modal.is-open {
      display: flex;
    }

    .member-modal-backdrop.is-open {
      display: block;
    }

    .member-modal-card {
      width: min(420px, 100%);
      background: #fff;
      border-radius: 20px;
      padding: 24px;
      box-shadow: 0 24px 60px rgba(15, 23, 42, 0.22);
    }

    .member-modal-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 16px;
    }

    .member-modal-header h2 {
      margin: 0;
    }

    .member-modal-close {
      border: none;
      background: #f3f4f6;
      border-radius: 999px;
      width: 36px;
      height: 36px;
      cursor: pointer;
    }

    .member-email-input {
      width: 100%;
      box-sizing: border-box;
      margin-top: 8px;
      padding: 12px 14px;
      border: 1px solid #d1d5db;
      border-radius: 12px;
      font-size: 16px;
    }

    .member-submit-button {
      margin-top: 16px;
      width: 100%;
      border: none;
      background: #2563eb;
      color: #fff;
      padding: 12px 16px;
      border-radius: 12px;
      font-weight: 700;
      cursor: pointer;
    }

    .member-remove-button {
      border: 1px solid #ef4444;
      background: #fff;
      color: #ef4444;
      padding: 8px 14px;
      border-radius: 999px;
      font-weight: 700;
      cursor: pointer;
      white-space: nowrap;
    }

    .member-remove-button:hover {
      background: #ef4444;
      color: #fff;
    }

    @media (max-width: 768px) {
      #sidebar {
        width: 100%;
        max-width: 100%;
        right: -100%;
      }

      #sidebar.open {
        right: 0;
      }

      .page-wrap {
        padding: 12px;
      }

      .page-title {
        font-size: 26px;
      }

      .page-subtitle {
        font-size: 13px;
      }

      #calendar {
        margin: 16px auto;
        padding: 10px;
      }

      .sidebar-footer {
        flex-direction: column;
      }

      .sidebar-footer button {
        width: 100%;
      }

      .member-section {
        padding: 18px;
      }

      .member-header {
        flex-direction: column;
        align-items: stretch;
      }

      .member-add-button {
        width: 100%;
      }

      .member-item {
        align-items: center;
        flex-direction: row;
      }

      .member-info {
        flex: 1;
        min-width: 0;
      }

      .member-email {
        word-break: break-all;
      }
    }

    /* アラート共通 */
    .alert {
      margin-bottom: 16px;
      padding: 14px 18px;
      border-radius: 12px;
      font-size: 14px;
      font-weight: 500;
    }

    /* 成功 */
    .alert-success {
      background: #ecfdf5;
      color: #065f46;
      border: 1px solid #6ee7b7;
    }

    .member-form-error {
      color: #dc2626;
      font-size: 14px;
      margin-top: 8px;
      font-weight: 600;
    }


    .toast {
      position: fixed;
      bottom: 24px;
      right: 24px;
      background: #333;
      color: #fff;
      padding: 14px 18px;
      border-radius: 12px;
      font-size: 14px;
      opacity: 0;
      transform: translateY(20px);
      transition: all 0.3s ease;
      z-index: 3000;
      pointer-events: none;
    }

    .toast.show {
      opacity: 1;
      transform: translateY(0);
    }

    .toast.success {
      background: #16a34a;
    }

    .toast.error {
      background: #dc2626;
    }

    /* グループ一覧へ戻るボタン */
    .back-button {
      display: inline-block;
      margin-bottom: 16px;
      color: #374151;
      text-decoration: none;
      font-weight: bold;
    }

    .back-button:hover {
      text-decoration: underline;
    }

    /* 説明 */
    .group-description {
      margin: 8px 0 12px;
      color: #555;
      font-size: 14px;
    }
  </style>
</head>

<body>
  <div class="page-wrap">
    <a href="{{ route('groups.index') }}" class="back-button">
      ← グループ一覧に戻る
    </a>

    <h1 class="page-title">{{ $group->name }} のカレンダー</h1>

    @if ($group->description)
      <p class="group-description">
        {{ $group->description }}
      </p>
    @endif

    <p class="page-subtitle">日付を押すと、その日の予定一覧を右から確認できます。</p>

    <div id="calendar"></div>
  </div>

  <!-- メンバー関連 -->
  <hr>
  <section class="member-section">
    <div class="member-header">
      <h2>メンバー一覧</h2>

      @if ($group->owner_id === auth()->id())
        <button type="button" id="open-member-modal" class="member-add-button">
          追加
        </button>
      @endif
    </div>

    <ul class="member-list">
      @foreach ($group->users as $member)
        <li class="member-item">
          <div class="member-info">
            <div class="member-name">{{ $member->name }}</div>
            <div class="member-email">{{ $member->email }}</div>
          </div>
          @if ($group->owner_id === auth()->id() && $member->id !== $group->owner_id)
            <form method="POST" action="{{ route('groups.members.remove', [$group->id, $member->id]) }}">
              @csrf
              @method('DELETE')
              <button type="submit" class="member-remove-button" onclick="return confirm('このメンバーを追放しますか？')">
                追放
              </button>
            </form>
          @endif
        </li>
      @endforeach
    </ul>
  </section>

  <div id="member-modal-backdrop" class="member-modal-backdrop"></div>

  <div id="member-modal" class="member-modal">
    <div class="member-modal-card">
      <div class="member-modal-header">
        <h2>メンバー追加</h2>
        <button type="button" id="close-member-modal" class="member-modal-close">×</button>
      </div>

      <form method="POST" action="{{ route('groups.members.add', $group->id) }}">
        @csrf

        <label for="member-email-input">追加するユーザーのメールアドレス</label>

        <input type="email" name="email" id="member-email-input" value="{{ old('email') }}" class="member-email-input"
          required autocomplete="email">

        @error('email')
          <div class="member-form-error" role="alert">
            {{ $message }}
          </div>
        @enderror

        <button type="submit" class="member-submit-button">追加する</button>

      </form>
    </div>
  </div>


  <div id="sidebar-backdrop"></div>

  <div id="sidebar">
    <div class="sidebar-header">
      <div class="sidebar-header-top">
        <h2 class="sidebar-title">予定一覧</h2>
        <button type="button" class="icon-close" id="close-sidebar-icon">✕</button>
      </div>
      <p id="selected-date-text"></p>
    </div>

    <div class="sidebar-body">
      <div class="sidebar-section-title">この日の予定</div>
      <div id="day-events-list"></div>
    </div>

    <div class="sidebar-footer">
      <button type="button" id="open-form-button">予定入力</button>
      <button type="button" id="close-sidebar-button">閉じる</button>
    </div>
  </div>

  <!-- 入力/編集モーダル -->
  <div id="event-modal" class="modal-overlay">
    <div class="modal-box">
      <h3 id="form-title">予定を入力</h3>
      <p id="modal-date-text" class="modal-date"></p>

      <div id="form-error" class="form-error" role="alert"></div>

      <input type="hidden" id="event_date">
      <input type="hidden" id="event_id">

      <div class="form-group">
        <label for="title">やりたいことは決まってる？</label>
        <input type="text" id="title" name="title" placeholder="例：カフェ、映画、買い物">
        <div class="form-help">未定なら空欄でもOKです。</div>
      </div>

      <div class="form-group">
        <label class="checkbox-label">
          <input type="checkbox" id="is_all_day" name="is_all_day">
          1日空いてる
        </label>
      </div>

      <div class="form-group">
        <label for="start_time">何時から空いてる？</label>
        <input type="time" id="start_time" name="start_time" step="900">
      </div>

      <div class="form-group">
        <label for="end_time">何時まで空いてる？</label>
        <input type="time" id="end_time" name="end_time" step="900">
        <div class="form-help">15分単位で選べます。</div>
      </div>

      <div class="form-button-group">
        <button type="button" id="save-button">保存</button>
        <button type="button" id="delete-button" style="display: none;">削除</button>
        <button type="button" id="cancel-button">キャンセル</button>
      </div>
    </div>
  </div>

  <!-- 詳細モーダル -->
  <div id="detail-modal" class="modal-overlay" role="dialog">
    <div class="modal-box">
      <h3>予定の詳細</h3>
      <p id="detail-date-text" class="modal-date"></p>

      <div class="detail-line">
        <div class="detail-label">やりたいこと</div>
        <div id="detail-title"></div>
      </div>

      <div class="detail-line">
        <div class="detail-label">空いている時間</div>
        <div id="detail-time">未設定</div>
      </div>

      <div class="detail-line">
        <div class="detail-label">入力者</div>
        <div id="detail-owner">未設定</div>
      </div>

      <div class="detail-button-group">
        <button type="button" id="detail-edit-button" style="display: none;">編集</button>
        <button type="button" id="detail-delete-button" style="display: none;">削除</button>
        <button type="button" id="detail-close-button">閉じる</button>
      </div>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      var calendarEl = document.getElementById('calendar');
      var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

      var sidebar = document.getElementById('sidebar');
      var sidebarBackdrop = document.getElementById('sidebar-backdrop');
      var closeSidebarIcon = document.getElementById('close-sidebar-icon');
      var selectedDateText = document.getElementById('selected-date-text');
      var dayEventsList = document.getElementById('day-events-list');
      var openFormButton = document.getElementById('open-form-button');
      var closeSidebarButton = document.getElementById('close-sidebar-button');

      var eventModal = document.getElementById('event-modal');
      var detailModal = document.getElementById('detail-modal');

      var formTitle = document.getElementById('form-title');
      var modalDateText = document.getElementById('modal-date-text');
      var formError = document.getElementById('form-error');
      var eventDateInput = document.getElementById('event_date');
      var eventIdInput = document.getElementById('event_id');
      var titleInput = document.getElementById('title');
      var isAllDayInput = document.getElementById('is_all_day');
      var startTimeInput = document.getElementById('start_time');
      var endTimeInput = document.getElementById('end_time');
      var saveButton = document.getElementById('save-button');
      var deleteButton = document.getElementById('delete-button');
      var cancelButton = document.getElementById('cancel-button');

      var detailDateText = document.getElementById('detail-date-text');
      var detailTitle = document.getElementById('detail-title');
      var detailTime = document.getElementById('detail-time');
      var detailOwner = document.getElementById('detail-owner');
      var detailEditButton = document.getElementById('detail-edit-button');
      var detailDeleteButton = document.getElementById('detail-delete-button');
      var detailCloseButton = document.getElementById('detail-close-button');

      var currentDetailEvent = null;

      function formatDateText(dateStr) {
        const parts = dateStr.split('-');
        const month = Number(parts[1]);
        const day = Number(parts[2]);
        return month + '月' + day + '日';
      }

      function showFormError(message) {
        formError.textContent = message;
        formError.style.display = 'block';
      }

      function clearFormError() {
        formError.textContent = '';
        formError.style.display = 'none';
      }

      function closeEventModal() {
        eventModal.classList.remove('open');
        clearFormError();
      }

      function openEventModal() {
        eventModal.classList.add('open');
      }

      function closeDetailModal() {
        detailModal.classList.remove('open');
        currentDetailEvent = null;
      }

      function openDetailModal() {
        detailModal.classList.add('open');
      }

      function resetForm() {
        eventIdInput.value = '';
        titleInput.value = '';
        isAllDayInput.checked = false;
        startTimeInput.value = '';
        endTimeInput.value = '';
        startTimeInput.disabled = false;
        endTimeInput.disabled = false;
        deleteButton.style.display = 'none';
        formTitle.textContent = '予定を入力';
        modalDateText.textContent = '';
        clearFormError();
      }

      function toggleTimeInputs() {
        if (isAllDayInput.checked) {
          startTimeInput.disabled = true;
          endTimeInput.disabled = true;
          startTimeInput.value = '';
          endTimeInput.value = '';
        } else {
          startTimeInput.disabled = false;
          endTimeInput.disabled = false;
        }
      }

      function openSidebar() {
        sidebar.classList.add('open');
        sidebarBackdrop.classList.add('open');
      }

      function closeSidebar() {
        sidebar.classList.remove('open');
        sidebarBackdrop.classList.remove('open');
        selectedDateText.textContent = '';
        dayEventsList.innerHTML = '';
      }

      function openCreateForm() {
        resetForm();
        formTitle.textContent = '予定を入力';
        modalDateText.textContent = formatDateText(eventDateInput.value) + 'の予定';
        openEventModal();
      }

      function openEditForm(eventData) {
        resetForm();

        formTitle.textContent = '予定を編集';
        eventIdInput.value = eventData.id;
        eventDateInput.value = eventData.event_date;
        modalDateText.textContent = formatDateText(eventData.event_date) + 'の予定';
        titleInput.value = eventData.raw_title ?? eventData.title ?? '';
        isAllDayInput.checked = eventData.is_all_day;
        startTimeInput.value = eventData.start_time ? eventData.start_time.substring(0, 5) : '';
        endTimeInput.value = eventData.end_time ? eventData.end_time.substring(0, 5) : '';

        toggleTimeInputs();

        deleteButton.style.display = 'inline-block';
        openEventModal();
      }

      function renderDayEvents(events) {
        dayEventsList.innerHTML = '';

        if (!events.length) {
          dayEventsList.innerHTML = `
            <div class="empty-message">
              📭 まだ予定がありません。<br>
              「予定入力」から追加してみよう！
            </div>
          `;
          return;
        }

        events.forEach(function (event) {
          var card = document.createElement('div');
          card.classList.add('event-card');

          var isMine = Number(event.is_mine) === 1;
          var isAllDay = Number(event.is_all_day) === 1;

          card.classList.add(isMine ? 'mine' : 'other');

          var titleText = event.title || '未定';

          var titleDiv = document.createElement('div');
          titleDiv.classList.add('event-card-title');

          var badge = document.createElement('span');
          badge.classList.add('badge', isMine ? 'badge-mine' : 'badge-other');
          badge.textContent = isMine ? '自分' : (event.user_name || 'メンバー');

          titleDiv.appendChild(badge);
          titleDiv.appendChild(document.createTextNode(titleText));

          var timeDiv = document.createElement('div');
          timeDiv.classList.add('event-card-time');

          if (isAllDay) {
            var allDayBadge = document.createElement('span');
            allDayBadge.classList.add('badge', 'badge-all-day');
            allDayBadge.textContent = '終日OK';
            timeDiv.appendChild(allDayBadge);
          } else if (event.start_time && event.end_time) {
            timeDiv.textContent = '🕐 ' + event.start_time.substring(0, 5) + '〜' + event.end_time.substring(0, 5);
          } else {
            timeDiv.textContent = '時間未設定';
          }

          var noteDiv = document.createElement('div');
          noteDiv.classList.add('event-card-note');
          noteDiv.textContent = isMine
            ? 'クリックで詳細確認・編集できます'
            : 'クリックで詳細確認できます';

          card.appendChild(titleDiv);
          card.appendChild(timeDiv);
          card.appendChild(noteDiv);

          card.addEventListener('click', function () {
            openDetailFromSidebarEvent(event);
          });

          dayEventsList.appendChild(card);
        });
      }

      function loadDayEvents(dateStr) {
        fetch('/groups/{{ $group->id }}/events/date/' + dateStr, {
          headers: {
            'Accept': 'application/json'
          }
        })
          .then(response => response.json())
          .then(data => {
            renderDayEvents(data);
          })
          .catch(() => {
            dayEventsList.innerHTML = '<p class="empty-message">予定の取得に失敗しました。</p>';
          });
      }

      function openDetailFromSidebarEvent(eventData) {
        currentDetailEvent = eventData;

        detailDateText.textContent = formatDateText(eventData.event_date) + 'の予定';
        detailTitle.textContent = eventData.title ? eventData.title : '未定';
        detailTime.innerHTML = '';

        if (Number(eventData.is_all_day) === 1) {
          var allDayBadge = document.createElement('span');
          allDayBadge.classList.add('badge', 'badge-all-day');
          allDayBadge.textContent = '終日OK';
          detailTime.appendChild(allDayBadge);
        } else if (eventData.start_time && eventData.end_time) {
          detailTime.textContent = eventData.start_time.substring(0, 5) + '〜' + eventData.end_time.substring(0, 5);
        } else {
          detailTime.textContent = '時間未設定';
        }

        detailOwner.textContent = Number(eventData.is_mine) === 1
          ? '自分'
          : (eventData.user_name || 'メンバー');

        if (Number(eventData.is_mine) === 1) {
          detailEditButton.style.display = 'inline-block';
          detailDeleteButton.style.display = 'inline-block';
        } else {
          detailEditButton.style.display = 'none';
          detailDeleteButton.style.display = 'none';
        }

        openDetailModal();
      }

      function openDetailFromCalendarEvent(info) {
        const event = info.event;
        const rawTitle = event.extendedProps.raw_title ?? '';
        const isAllDay = Number(event.extendedProps.is_all_day) === 1;
        const startTime = event.extendedProps.start_time;
        const endTime = event.extendedProps.end_time;
        const isMine = event.extendedProps.is_mine;

        currentDetailEvent = {
          id: event.id,
          event_date: event.startStr,
          title: rawTitle,
          start_time: startTime,
          end_time: endTime,
          is_all_day: isAllDay ? 1 : 0,
          is_mine: isMine ? 1 : 0
        };

        detailDateText.textContent = formatDateText(event.startStr) + 'の予定';
        detailTitle.textContent = rawTitle ? rawTitle : '未定';
        detailTime.innerHTML = '';

        if (isAllDay) {
          var allDayBadge = document.createElement('span');
          allDayBadge.classList.add('badge', 'badge-all-day');
          allDayBadge.textContent = '終日OK';
          detailTime.appendChild(allDayBadge);
        } else if (startTime && endTime) {
          detailTime.textContent = startTime.substring(0, 5) + '〜' + endTime.substring(0, 5);
        } else {
          detailTime.textContent = '時間未設定';
        }

        detailOwner.textContent = isMine
          ? '自分'
          : (event.extendedProps.user_name || 'メンバー');

        if (isMine) {
          detailEditButton.style.display = 'inline-block';
          detailDeleteButton.style.display = 'inline-block';
        } else {
          detailEditButton.style.display = 'none';
          detailDeleteButton.style.display = 'none';
        }

        openDetailModal();
      }

      isAllDayInput.addEventListener('change', toggleTimeInputs);

      openFormButton.addEventListener('click', function () {
        if (!eventDateInput.value) {
          alert('先に日付を選択してください');
          return;
        }

        openCreateForm();
      });

      closeSidebarButton.addEventListener('click', closeSidebar);
      closeSidebarIcon.addEventListener('click', closeSidebar);
      sidebarBackdrop.addEventListener('click', closeSidebar);

      document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
          closeDetailModal();
          closeEventModal();
          closeSidebar();
        }
      });

      cancelButton.addEventListener('click', function () {
        closeEventModal();
        resetForm();
      });

      detailCloseButton.addEventListener('click', closeDetailModal);

      detailEditButton.addEventListener('click', function (e) {
        e.preventDefault();

        if (!currentDetailEvent || !currentDetailEvent.is_mine) {
          return;
        }

        const eventData = currentDetailEvent;

        closeDetailModal();
        openEditForm(eventData);
      });

      detailDeleteButton.addEventListener('click', function () {
        if (!currentDetailEvent || !currentDetailEvent.is_mine) {
          return;
        }

        if (!confirm('この予定を削除しますか？')) {
          return;
        }

        fetch('/groups/{{ $group->id }}/events/' + currentDetailEvent.id, {
          method: 'DELETE',
          headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
          }
        })
          .then(response => response.json())
          .then(data => {
            calendar.refetchEvents();
            loadDayEvents(currentDetailEvent.event_date);
            closeDetailModal();
          })
          .catch(error => {
            alert('削除に失敗しました');
          });
      });

      saveButton.addEventListener('click', function () {
        var eventId = eventIdInput.value;
        var title = titleInput.value.trim();
        var eventDate = eventDateInput.value;
        var isAllDay = isAllDayInput.checked ? 1 : 0;
        var startTime = startTimeInput.value;
        var endTime = endTimeInput.value;

        clearFormError();

        if (!eventDate) {
          showFormError('日付を選択してください。');
          return;
        }

        if (!isAllDay && (!startTime || !endTime)) {
          showFormError('開始時間と終了時間を入力してください。');
          return;
        }

        if (!isAllDay && startTime && endTime && startTime >= endTime) {
          showFormError('終了時間は開始時間より後にしてください。');
          return;
        }

        saveButton.disabled = true;

        var url = '/groups/{{ $group->id }}/events';
        var method = 'POST';

        if (eventId) {
          url = '/groups/{{ $group->id }}/events/' + eventId;
          method = 'PUT';
        }

        fetch(url, {
          method: method,
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
          },
          body: JSON.stringify({
            title: title,
            event_date: eventDate,
            start_time: startTime ? startTime + ':00' : null,
            end_time: endTime ? endTime + ':00' : null,
            is_all_day: isAllDay
          })
        })
          .then(async response => {
            const data = await response.json();

            if (!response.ok) {
              throw data;
            }

            return data;
          })
          .then(() => {
            calendar.refetchEvents();
            loadDayEvents(eventDate);
            closeEventModal();
            resetForm();
          })
          .catch(error => {
            if (error.errors) {
              const firstError = Object.values(error.errors)[0][0];
              showFormError(firstError);
            } else if (error.message) {
              showFormError(error.message);
            } else {
              showFormError('保存に失敗しました。');
            }
          })
          .finally(() => {
            saveButton.disabled = false;
          });
      });

      deleteButton.addEventListener('click', function () {
        var eventId = eventIdInput.value;
        var eventDate = eventDateInput.value;

        if (!eventId) {
          return;
        }

        if (!confirm('この予定を削除しますか？')) {
          return;
        }

        fetch('/groups/{{ $group->id }}/events/' + eventId, {
          method: 'DELETE',
          headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
          }
        })
          .then(response => response.json())
          .then(() => {
            calendar.refetchEvents();
            loadDayEvents(eventDate);
            closeEventModal();
            resetForm();
          })
          .catch(() => {
            alert('削除に失敗しました');
          });
      });

      var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        events: '/groups/{{ $group->id }}/events',
        eventDisplay: 'block',
        dayMaxEvents: true,

        dateClick: function (info) {
          resetForm();
          eventDateInput.value = info.dateStr;
          selectedDateText.textContent = formatDateText(info.dateStr) + 'の予定一覧';
          openSidebar();
          loadDayEvents(info.dateStr);
        },

        eventClick: function (info) {
          info.jsEvent.preventDefault();
          openDetailFromCalendarEvent(info);
        }
      });

      calendar.render();


      // メンバー関連
      const openMemberModalButton = document.getElementById('open-member-modal');
      const closeMemberModalButton = document.getElementById('close-member-modal');
      const memberModal = document.getElementById('member-modal');
      const memberModalBackdrop = document.getElementById('member-modal-backdrop');

      function openMemberModal() {
        clearMemberError();
        memberModal.classList.add('is-open');
        memberModalBackdrop.classList.add('is-open');

        setTimeout(() => {
          const input = document.querySelector('.member-email-input');
          if (input) input.focus();
        }, 100);
      }

      function closeMemberModal() {
        clearMemberError();
        memberModal.classList.remove('is-open');
        memberModalBackdrop.classList.remove('is-open');
      }

      if (openMemberModalButton) {
        openMemberModalButton.addEventListener('click', openMemberModal);
      }

      if (closeMemberModalButton) {
        closeMemberModalButton.addEventListener('click', closeMemberModal);
      }

      if (memberModalBackdrop) {
        memberModalBackdrop.addEventListener('click', closeMemberModal);
      }

      document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
          closeMemberModal();
        }
      });

      function clearMemberError() {
        const error = document.getElementById('member-email-error');

        if (error) {
          error.remove();
        }
      }



      function showToast(message, type = 'success') {
        const toast = document.getElementById('toast');
        if (!toast) return;

        toast.textContent = message;

        toast.className = 'toast ' + type;
        toast.classList.add('show');

        setTimeout(() => {
          toast.classList.remove('show');
        }, 3000);
      }

      // const memberForm = document.querySelector('#member-modal form');

      // if (memberForm) {
      //   memberForm.addEventListener('submit', function () {
      //     const submitButton = this.querySelector('.member-submit-button');
      //     if (submitButton) {
      //       submitButton.disabled = true;
      //     }
      //   });
      // }

    });


  </script>

  @if ($errors->has('email'))
    <script>
      document.addEventListener('DOMContentLoaded', function () {
        const memberModal = document.getElementById('member-modal');
        const memberModalBackdrop = document.getElementById('member-modal-backdrop');
        const input = document.querySelector('.member-email-input');

        if (memberModal) memberModal.classList.add('is-open');
        if (memberModalBackdrop) memberModalBackdrop.classList.add('is-open');

        if (input) {
          setTimeout(() => input.focus(), 100);
        }
      });
    </script>
  @endif

  @if (session('success'))
    <script>
      document.addEventListener('DOMContentLoaded', function () {
        const toast = document.getElementById('toast');

        if (!toast) return;

        toast.textContent = @json(session('success'));
        toast.className = 'toast success';
        toast.classList.add('show');

        setTimeout(function () {
          toast.classList.remove('show');
        }, 3000);
      });
    </script>
  @endif

  <div id="toast" class="toast" role="status"></div>

</body>

</html>

<div id="toast" class="toast" role="status"></div>

</body>

</html>