<!DOCTYPE html>
<html>
<head>
	<title>提示</title>
</head>
<body>



	<div>
		{{$tips['msg']}} 
		<p></p>
	</div>

</body>

  <!-- jQuery -->
    <script src="{{asset('page/table/vendor/jquery/jquery.min.js')}}"></script>
<script type="text/javascript">
	
	

	setTimeout(function () {
       window.location.href=document.referrer;
    }, 3000)

</script>



</html>