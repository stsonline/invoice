var Names = function()
{
    this.data = this.getJSONFromDB();
};

Names.prototype = 
{
        getJSONFromDB: function()
        {
            var JSON;
                
            var jqxhr = $.ajax(
            {
                  type: 'GET',
                  url: WEBROOT+"js/names_db2.json",
                  //data: {'page_id': pageId}, //only use data for POST
                  async: false
            })
            .success(function(result) 
            { 
                    JSON = result;        
            })
            .error(function() 
            { 
                alert("error getting data"); 
            })
            .complete(function() { });
            
            type = typeof(JSON);
            if(type=="string")
            {
            	return eval('(' + JSON + ')');
            }else
            {
            	 return /*eval('(' +*/ JSON /*+ ')')*/;
            }
        },
};


