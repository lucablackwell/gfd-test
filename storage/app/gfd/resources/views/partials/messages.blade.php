@if ($errors->any())
<div class="alert alert-danger">
  <ul class="mb-0 pl-3">
    @foreach ($errors->all() as $error)
      <li>{{ $error }}</li>
    @endforeach
  </ul>
</div>
@endif
@if (session('success'))
<div class="alert alert-brand">
  {{ session('success') }}
</div>
@endif
@if (session('danger'))
  <div class="alert alert-danger">
      {{ session('danger') }}
  </div>
@endif