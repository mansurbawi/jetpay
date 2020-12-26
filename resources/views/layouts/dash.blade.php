<!doctype html>
<html class="no-js" lang="en">
@include('layouts.head.head_dashboard')
<body>
@include('layouts.aside.aside1')
<div id="right-panel" class="right-panel">
@include('layouts.menu.menu1')
@yield('content')
</div>
@include('layouts.script.script1')
</body>
</html>