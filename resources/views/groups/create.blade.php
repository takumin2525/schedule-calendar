<style>
  .form-group {
    margin-bottom: 16px;
  }

  label {
    display: block;
    margin-bottom: 6px;
    font-weight: bold;
  }

  input[type="text"],
  textarea {
    width: 100%;
    max-width: 400px;
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 8px;
    box-sizing: border-box;
  }

  input[type="text"]:focus,
  textarea:focus {
    outline: none;
    border-color: #2f6fed;
    box-shadow: 0 0 0 2px rgba(47, 111, 237, 0.2);
  }

  textarea {
    min-height: 80px;
    resize: vertical;
  }

  .form-error {
    margin-top: 6px;
    color: #dc2626;
    font-size: 14px;
  }

  button {
    margin-top: 12px;
    padding: 10px 16px;
    background: #2f6fed;
    color: #fff;
    border: none;
    border-radius: 8px;
    cursor: pointer;
  }

  button:hover {
    opacity: 0.9;
  }

  form {
    max-width: 420px;
  }
</style>

<h1>グループ作成</h1>

<form method="POST" action="{{ route('groups.store') }}">
  @csrf

  <div class="form-group">
    <label for="name">グループ名</label>
    <input type="text" id="name" name="name" value="{{ old('name') }}">
    @error('name')
      <div class="form-error">{{ $message }}</div>
    @enderror
  </div>

  <div class="form-group">
    <label for="description">説明</label>
    <textarea id="description" name="description">{{ old('description') }}</textarea>
    @error('description')
      <div class="form-error">{{ $message }}</div>
    @enderror
  </div>

  <button type="submit">グループを作成</button>
</form>