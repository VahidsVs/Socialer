<div class="list-group">
    @foreach ($posts as $item)
    <a href="/view-post/{{ $item->id }}" class="list-group-item list-group-item-action">
      <img class="avatar-tiny" src="{{ $item->user->avatar  }}" />
      <strong>{{ $item->title }}</strong> by {{ $item->user->username  }} on {{ $item->created_at->format("d/m/Y")}}
    </a>
    @endforeach
    </div>