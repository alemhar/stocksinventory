document.onreadystatechange = function () {
    
  		if(document.readyState === "complete"){

        

        //var url = window.location.href;
        var user_id = '';
        var api_key= '';
        var obj = {};
        


        //asin = url.substring(start_asin, end_asin);

          function getcredentials(callback){  
              chrome.runtime.sendMessage({from: "content",action: "getstorage"}, (response) => {
                user_id = response.user_id;
                api_key = response.api_key;
               
                callback(user_id,api_key);
              });
          }

        
        
        
          function fill_content(user_id,api_key){
            obj['Content-Type'] = 'application/x-www-form-urlencoded';
            obj['Authorization'] = 'Bearer ' + api_key;  
            obj['Accept'] = 'application/json';  
          

            $.ajax({
              type: "POST",
              url: 'https://books.thinkerhut.com/api/efps' ,
              
              data: {company_id: user_id},
              headers:  obj,
              cache: false,
              success: function(data)
              {
                console_log(data);
                //response = JSON.parse(data);  
                
              }, 
              complete: function(data)
              {

              }
          
            });
            } 
        
          

          chrome.runtime.onMessage.addListener(
            (request, sender, sendResponse) => {
              if (request.from === "popup" && request.action === "fill"){
                
                getcredentials(fill_content);
                //user_id = localStorage.getItem('user_id');
                //api_key = localStorage.getItem('api_key');		
                //sendResponse({user_id: user_id,api_key: api_key});
              }
            });


        }
    
}