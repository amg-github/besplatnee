<!DOCTYPE html>
<html>
	<head>
		<script src="//ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>

		<script type="text/javascript">
			function request(offset, limit) {
				$.ajax({
					url: '/test',
					method: 'get',
					data: {
						offset: offset,
						limit: limit
					},
					success: function (r) {
						console.clear();
						if(r == '1') {
							console.log('process ' + (offset + limit));
							request(offset + limit, limit);
						} else {
							console.log('complete');
						}
					}
				});
			}

			request(200200, 100);
		</script>
	</head>
	<body>

	</body>
</html>