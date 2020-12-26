<!doctype html>
<html class="no-js" lang="en">
@include('layouts.head.head_billing')
<body>
@include('layouts.aside.aside1')
<div id="right-panel" class="right-panel">
@include('layouts.menu.menu1')
@yield('content')
</div>
@include('layouts.script.script_billnotActive')
</body>
</html>