document.onreadystatechange = function () {
    
  		if(document.readyState === "complete"){

        

        var url = window.location.href;
        var user_id = '';
        var api_key= '';

        var headername1 = 'Content-Type';
        var headerval1 = 'application/x-www-form-urlencoded';

        //var regex_pattern = /([^/]*)$/;
        //var matches = url.match(regex_pattern);
        
        //asin = document.getElementById("ASIN").value;
        
        
        var obj = {};
        obj[headername1] = headerval1;


        //asin = url.substring(start_asin, end_asin);

        if(asin){  
        function getcredentials(callback){  
            chrome.runtime.sendMessage({from: "content",action: "getstorage"}, (response) => {
              username = response.username;
              password = response.password;
              credential = btoa(username+':'+password);
              callback(credential);
            });
        }

        
        
        
        function findcompany(username,password){
          headerval2 = credential;
          
          obj['Authorization'] = 'Basic ' + headerval2;  
         
          $.ajax({
            type: "POST",
            url: 'https://app.sourcingsecret.com/backend/company/findcompany.php' ,
            
            data: {asinID: asin},
            headers:  obj,
            cache: false,
            success: function(data)
            {
              //console_log(data);
              response = JSON.parse(data);  

              if (document.getElementById('rightCol') !== null){
                  var x = document.getElementById("rightCol");
                  info = document.createElement("div");
                   
                  if(response.message == 'Not Found'){
                      
                      info.innerHTML = '<div style="width: 244px; font-style: italic;border: 2px solid #2222aa;border-radius: 10px; padding: 10px; background-color: #e7eff; word-wrap: break-word"><img src="https://app.sourcingsecret.com/img/ss_logo_website3.png" height="40px" style="float: right;"/>Status: Not Found<br><br></div><br>';
                    } else {
                      var website = response.website;
                      if(website.substring(0, 7) != 'http://' && website.substring(0, 8) != 'https://' ){
                        if(website.length > 0){
                            website = 'http://'+response.website;    
                        } else {
                          website = '';
                        } 
                      }

                      


                      info.innerHTML = '<div style="width: 244px; font-style: italic;border: 2px solid #2222aa;border-radius: 10px; padding: 10px; background-color: #e7eff; word-wrap: break-word"><img src="https://app.sourcingsecret.com/img/ss_logo_website3.png" height="40px" style="float: right;"/>Brand: <b>'+response.brand+'</b><br> Address: <b>'+response.address1+' '+response.address2+'</b><br> City: <b>'+response.city+'</b><br> State: <b>'+response.state+'</b><br> Zip: <b>'+response.zip+'</b><br> Phone: <b>'+response.phone+'</b><br> Email: <b><a href="mailto:'+response.email+'">'+response.email+'</a></b><br> Contact: <b>'+response.contactname+'</b><br> Contact Phone: <b>'+response.contactphone+'</b><br> Email: <b>'+response.contactemail+'</b><br> Website: <b><a href="'+website+'" target="_blank" >'+website+'</a></b><br> Note: <b>'+response.note+'</b><br><br></div><br>';
                    }
                    x.insertBefore(info, x.firstChild);
              } else if (document.getElementById('actionPanel') !== null){
                  var x = document.getElementById("actionPanel");
                  info = document.createElement("div");
                   
                  if(response.message == 'Not Found'){
                      
                      info.innerHTML = '<div style="width: 244px; font-style: italic;border: 2px solid #2222aa;border-radius: 10px; padding: 10px; background-color: #e7eff; word-wrap: break-word"><img src="https://app.sourcingsecret.com/img/ss_logo_website3.png" height="40px" style="float: right;"/>Status: Not Found<br><br></div><br>';
                    } else {
                      var website = response.website;
                      if(website.substring(0, 7) != 'http://' && website.substring(0, 8) != 'https://' ){
                        if(website.length > 0){
                            website = 'http://'+response.website;    
                        } else {
                          website = '';
                        } 
                      }

                      


                      info.innerHTML = '<div style="width: 244px; font-style: italic;border: 2px solid #2222aa;border-radius: 10px; padding: 10px; background-color: #e7eff; word-wrap: break-word"><img src="https://app.sourcingsecret.com/img/ss_logo_website3.png" height="40px" style="float: right;"/>Brand: <b>'+response.brand+'</b><br> Address: <b>'+response.address1+' '+response.address2+'</b><br> City: <b>'+response.city+'</b><br> State: <b>'+response.state+'</b><br> Zip: <b>'+response.zip+'</b><br> Phone: <b>'+response.phone+'</b><br> Email: <b><a href="mailto:'+response.email+'">'+response.email+'</a></b><br> Contact: <b>'+response.contactname+'</b><br> Contact Phone: <b>'+response.contactphone+'</b><br> Email: <b>'+response.contactemail+'</b><br> Website: <b><a href="'+website+'" target="_blank" >'+website+'</a></b><br> Note: <b>'+response.note+'</b><br><br></div><br>';
                    }
                    x.insertBefore(info, x.firstChild);
              } else if (document.getElementById('prodDetails') !== null){
                  var x = document.getElementById("prodDetails");
                    if(response.message == 'Not Found'){
                      x.querySelector("h2").outerHTML += '<div style="font-style: italic;border: 1px solid #ccc;border-radius: 5px;padding-left: 28px; width: 500px;background-color: #e7eff5"><br><h4 style="color: #4451b7;"><b>Sourcing Secret Info:</b></h4>ASIN: <b>'+asin+'</b><br> Status: <b>Not Found</b><br><br></div>';
                    } else {
                      var website = response.website;
                      if(website.substring(0, 7) != 'http://' && website.substring(0, 8) != 'https://' ){
                        if(website.length > 0){
                            website = 'http://'+response.website;    
                        } else {
                          website = '';
                        }  
                      }

                      x.querySelector("h2").outerHTML += '<div style="font-style: italic;border: 1px solid #ccc;border-radius: 5px;padding-left: 28px; width: 500px;background-color: #e7eff5"><br><h4 style="color: #4451b7;"><b>Sourcing Secret Info:</b></h4>ASIN: <b>'+asin+'</b><br> Brand: <b>'+response.brand+'</b><br> Address: <b>'+response.address1+' '+response.address2+'</b><br> City: <b>'+response.city+'</b><br> State: <b>'+response.state+'</b><br> Zip: <b>'+response.zip+'</b><br> Phone: <b>'+response.phone+'</b><br> Email: <b><a href="mailto:'+response.email+'">'+response.email+'</a></b><br> Contact: <b>'+response.contactname+'</b><br> Contact Phone: <b>'+response.contactphone+'</b><br> Email: <b>'+response.contactemail+'</b><br> Website: <b><a href="'+website+'" target="_blank" >'+website+'</a></b><br> Note: <b>'+response.note+'</b><br><br></div>';
                    }

              
              } else if (document.getElementById('sp_detail2') !== null){
                  var x = document.getElementById("sp_detail2");
                  info = document.createElement("div");
                   
                  if(response.message == 'Not Found'){
                      
                      info.innerHTML = '<br><div style="font-style: italic;border: 1px solid #ccc;border-radius: 5px;padding-left: 28px; width: 500px;background-color: #e7eff5"><br><h4 style="color: #4451b7;"><b>Sourcing Secret Info:</b></h4>ASIN: '+asin+'<br> Status: Not Found<br><br></div><br>';
                    } else {
                      var website = response.website;
                      if(website.substring(0, 7) != 'http://' && website.substring(0, 8) != 'https://' ){
                        if(website.length > 0){
                            website = 'http://'+response.website;    
                        } else {
                          website = '';
                        }  
                      }
                      info.innerHTML = '<br><div style="font-style: italic;border: 1px solid #ccc;border-radius: 5px;padding-left: 28px; width: 500px;background-color: #e7eff5"><br><h4 style="color: #4451b7;"><b>Sourcing Secret Info:</b></h4>ASIN: <b>'+asin+'</b><br> Brand: <b>'+response.brand+'</b><br> Address: <b>'+response.address1+' '+response.address2+'</b><br> City: <b>'+response.city+'</b><br> State: <b>'+response.state+'</b><br> Zip: <b>'+response.zip+'</b><br> Phone: <b>'+response.phone+'</b><br> Email: <b><a href="mailto:'+response.email+'">'+response.email+'</a></b><br> Contact: <b>'+response.contactname+'</b><br> Contact Phone: <b>'+response.contactphone+'</b><br> Email: <b>'+response.contactemail+'</b><br> Website: <b><a href="'+website+'" target="_blank" >'+website+'</a></b><br> Note: <b>'+response.note+'</b><br><br></div><br>';
                    }
                    x.insertBefore(info, x.firstChild);  
              } else if (document.getElementById('sp_detail') !== null){
                  var x = document.getElementById("sp_detail");
                  info = document.createElement("div");
                   
                  if(response.message == 'Not Found'){
                      
                      info.innerHTML = '<br><div style="font-style: italic;border: 1px solid #ccc;border-radius: 5px;padding-left: 28px; width: 500px;background-color: #e7eff5"><br><h4 style="color: #4451b7;"><b>Sourcing Secret Info:</b></h4>ASIN: '+asin+'<br> Status: Not Found<br><br></div><br>';
                    } else {
                      var website = response.website;
                      if(website.substring(0, 7) != 'http://' && website.substring(0, 8) != 'https://' ){
                        if(website.length > 0){
                            website = 'http://'+response.website;    
                        } else {
                          website = '';
                        } 
                      }
                      info.innerHTML = '<br><div style="font-style: italic;border: 1px solid #ccc;border-radius: 5px;padding-left: 28px; width: 500px;background-color: #e7eff5"><br><h4 style="color: #4451b7;"><b>Sourcing Secret Info:</b></h4>ASIN: <b>'+asin+'</b><br> Brand: <b>'+response.brand+'</b><br> Address: <b>'+response.address1+' '+response.address2+'</b><br> City: <b>'+response.city+'</b><br> State: <b>'+response.state+'</b><br> Zip: <b>'+response.zip+'</b><br> Phone: <b>'+response.phone+'</b><br> Email: <b><a href="mailto:'+response.email+'">'+response.email+'</a></b><br> Contact: <b>'+response.contactname+'</b><br> Contact Phone: <b>'+response.contactphone+'</b><br> Email: <b>'+response.contactemail+'</b><br> Website: <b><a href="'+website+'" target="_blank" >'+website+'</a></b><br> Note: <b>'+response.note+'</b><br><br></div><br>';
                    }
                    x.insertBefore(info, x.firstChild);  
              } else if (document.getElementById('reviewsMedley') !== null){
                  var x = document.getElementById("reviewsMedley");
                  info = document.createElement("div");
                   
                  if(response.message == 'Not Found'){
                      
                      info.innerHTML = '<br><div style="font-style: italic;border: 1px solid #ccc;border-radius: 5px;padding-left: 28px; width: 500px;background-color: #e7eff5"><br><h4 style="color: #4451b7;"><b>Sourcing Secret Info:</b></h4>ASIN: '+asin+'<br> Status: Not Found<br><br></div><br>';
                    } else {
                      var website = response.website;
                      if(website.substring(0, 7) != 'http://' && website.substring(0, 8) != 'https://'){
                        if(website.length > 0){
                            website = 'http://'+response.website;    
                        } else {
                          website = '';
                        }
                        
                      }
                      info.innerHTML = '<br><div style="font-style: italic;border: 1px solid #ccc;border-radius: 5px;padding-left: 28px; width: 500px;background-color: #e7eff5"><br><h4 style="color: #4451b7;"><b>Sourcing Secret Info:</b></h4>ASIN: <b>'+asin+'</b><br> Brand: <b>'+response.brand+'</b><br> Address: <b>'+response.address1+' '+response.address2+'</b><br> City: <b>'+response.city+'</b><br> State: <b>'+response.state+'</b><br> Zip: <b>'+response.zip+'</b><br> Phone: <b>'+response.phone+'</b><br> Email: <b><a href="mailto:'+response.email+'">'+response.email+'</a></b><br> Contact: <b>'+response.contactname+'</b><br> Contact Phone: <b>'+response.contactphone+'</b><br> Email: <b>'+response.contactemail+'</b><br> Website: <b><a href="'+website+'" target="_blank" >'+website+'</a></b><br> Note: <b>'+response.note+'</b><br><br></div><br>';
                    }
                    x.insertBefore(info, x.firstChild);  
              }

              //
              
            }, 
            complete: function(data)
            {

            }
        
          });
          } 
          
            
        }              
        
        getcredentials(findcompany);

        }
    
}