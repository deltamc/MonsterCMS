
$(
	function ()
	{
		$('#delcat').attr('disabled','false');
		$('#editcat').attr('disabled','false');

		$('[name=idcat]').change
		(
			function ()
			{


				val = $(this).val();


				if(val == 0)
				{
					$('#delcat').attr('disabled',true);
					$('#editcat').attr('disabled',true);

				}
				else
				{
					$('#delcat').attr('disabled',false);
					$('#editcat').attr('disabled',false);
				}
			}
		);

		$('#addcat').click
		(
			function ()
			{
				val = $('[name=idcat]:checked').val();


					location.href = location.href+'&addcat='+val;

			}
		)

		$('#editcat').click
		(
			function ()
			{
				val = $('[name=idcat]:checked').val();

				if(val!=0)
				{
					location.href = location.href+'&editcat='+val;
				}
			}
		)

		$('#delcat').click
		(
			function ()
			{
				val = $('[name=idcat]:checked').val();

				if(val!=0)
				{
					if (confirm("Удалить каталог?"))
					{
						location.href = location.href+'&delcat='+val;
					}
				}
			}
		)
	}
)