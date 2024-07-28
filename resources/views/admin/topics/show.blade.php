
<div class="box box-info">

   
    
<div class="box-header with-border">
          <h1 class="text-center mt-3 mb-3">
            {{ $topic->title }}
          </h1>
          <div class="text-center">
            作者：{{ $topic->user->name }}
          </div>
</div>
<div class="box-body">
          <div class="article-meta text-center text-secondary">
            {{ $topic->created_at->diffForHumans() }}
            ⋅
            <i class="far fa-comment"></i>
            {{ $topic->reply_count }}
          </div>

          <div class="topic-body mt-4 mb-4">
            {!! $topic->body !!}
          </div>
</div>


      


  </div>
