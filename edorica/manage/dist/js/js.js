var api_url = '../api.php'
$("#provSelect").on('change', function(ev){
	selected = $(this).find("option:selected").val();
	
	if(selected){
		//Loading districts in province
		$.post(api_url, {'action':'get_districts', 'province':selected}, function(data, status){
			try{
			dists = JSON.parse(data);

			//adding districts
			diselem = $("#diSelect");

			diselem.html($('<option>', {
			    value: 1,
			    text: 'District'
			}));

			for(n=0; n<dists.length; n++){
				dist = dists[n];
				diselem.append($('<option>', {
				    value: dist,
				    text: dist,
				}));
			}
			}catch(err){
				log("Error with JSON"+data);
				log(err)
			}
		})

	}
});
$("#diSelect").on('change', function(ev){
	selected = $(this).find("option:selected").val();
	
	if(selected){
		//Loading districts in province
		$.post(api_url, {'action':'get_sectors', 'district':selected}, function(data, status){
			try{
			sects = JSON.parse(data);

			//adding districts
			diselem = $("#sectSelect");

			diselem.html($('<option>', {
			    value: 0,
			    text: 'Sector'
			}));


			for(n=0; n<sects.length; n++){
				sect = sects[n];
				diselem.append($('<option>', {
				    value: sect,
				    text: sect,
				}));

			}

			}catch(err){
				log("Error with JSON"+data);
				log(err)
			}
		})

	}
});

function districts(province, got_data=0){
	//Function to return districts in a province

	if(got_data){
		log(province)
		return province;
	}

	var dists = [];

	$.post(api_url, {'action':'get_districts', 'province':province}, function(data, status){
	})
	.always(function(data){
		try{
			dists = JSON.parse(data);
		}catch(err){
		}
		//Returning data
		return districts(dists, 1)
	})
}
function log(data){
	console.log(data);
}