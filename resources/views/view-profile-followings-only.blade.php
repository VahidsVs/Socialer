  <div class="list-group">
  @foreach ($followings as $item)
  <a href="/profile/{{ $item->following->username }}" class="list-group-item list-group-item-action">
    <img class="avatar-tiny" src="{{ $item->following->avatar }}" />
    <strong>{{ $item->following->username }}</strong> became member on {{ $item->following->created_at->format("d/m/Y")}}
  </a>
  @endforeach
    </div>
