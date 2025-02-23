<nav class="navbar navbar-expand-lg navbar-light bg-light navbar-static-top">
<div class="container">
    <!-- Branding Image -->
    <a class="navbar-brand " href="{{ url('/') }}">
   BBS
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <!-- Left Side Of Navbar -->
   <!-- Left Side Of Navbar -->
   <ul class="navbar-nav mr-auto">
        <li class="nav-item"><a class="nav-link {{ active_class(if_route('topics.index')) }}" href="{{ route('topics.index') }}">话题</a></li>
        
          <?php
             use App\Models\Category;
          ?>
          @foreach(Category::all() as $v=>$k)
            <li class="nav-item"><a class="nav-link {{ category_nav_active($k->id) }}" href="{{ route('categories.show', $k->id) }}">{{ $k->name }}</a></li>
            <!-- <li class="nav-item"><a class="nav-link {{ category_nav_active(2) }}" href="{{ route('categories.show', 2) }}">教程</a></li>
            <li class="nav-item"><a class="nav-link {{ category_nav_active(3) }}" href="{{ route('categories.show', 3) }}">问答</a></li>
            <li class="nav-item"><a class="nav-link {{ category_nav_active(4) }}" href="{{ route('categories.show', 4) }}">公告</a></li> -->
          @endforeach

    </ul>

    <!-- Right Side Of Navbar -->
    <ul class="navbar-nav navbar-right">
        <!-- Authentication Links -->
        @guest
        <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">登录</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">注册</a></li>
        @else
        <li class="nav-item">
            <a class="nav-link mt-1 mr-3 font-weight-bold" href="{{ route('topics.create') }}">
              <i class="fa fa-plus"></i>
            </a>
          </li>
          <li class="nav-item notification-badge">
            <a class="nav-link mr-3 badge badge-pill badge-{{ Auth::user()->notification_count > 0 ? 'hint' : 'secondary' }} text-white" href="{{ route('notifications.index') }}">
              {{ Auth::user()->notification_count }}
            </a>
          </li>
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
           <img  src="{{ Auth::user()->avatar?:'http://gips3.baidu.com/it/u=3886271102,3123389489&fm=3028&app=3028&f=JPEG&fmt=auto?w=1280&h=960' }}" class="img-responsive img-circle" alt="{{ Auth::user()->name }}" width="30px" height="30px">

            {{ Auth::user()->name }}
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
            <a class="dropdown-item" href="{{ route('users.show', Auth::id()) }}">个人中心</a>
            <a class="dropdown-item" href="{{ route('users.edit', Auth::id()) }}">编辑资料</a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" id="logout" href="#">
                <form action="{{ route('logout') }}" method="POST" onsubmit="return confirm('您确定要退出吗？');">
                  {{ csrf_field() }}
                  <button class="btn btn-block btn-danger" type="submit" name="button">退出</button>
                </form>
              </a>
            </div>
        </li>
        @endguest
    </ul>
    </div>
</div>
</nav>
