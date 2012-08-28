@layout('layouts.main')

@section('buttons')
	<li>{{ HTML::link($id.'/raw', 'Raw') }}</li>
	<li>{{ HTML::link($id.'/fork', 'Fork') }}</li>
	<li>{{ HTML::link('/', 'New') }}</li>
@endsection


@section('content')
<pre class="prettyprint linenums"><code>{{ $paste }}</code></pre>
@endsection

@section('footer')
<div class="footer">
	Created {{ $created }} and viewed {{ $views }}
</div>
@endsection
