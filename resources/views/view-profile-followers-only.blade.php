  <div class="list-group">
  @foreach ($followers as $item)
  <a href="/profile/{{ $item->follower->username }}" class="list-group-item list-group-item-action">
    <img class="avatar-tiny" src="{{ $item->follower->avatar   }}" />
    <strong>{{ $item->follower->username }}</strong> became member on  {{ $item->follower->created_at->format("d/m/Y")}}
  </a>
  @endforeach
    </div>
