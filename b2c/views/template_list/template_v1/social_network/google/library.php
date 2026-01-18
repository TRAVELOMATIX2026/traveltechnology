<html>
  <body>
      <script src="https://accounts.google.com/gsi/client" async defer></script>
      <script>
        function handleCredentialResponse(response) 
        {
          //console.log("Encoded JWT ID token: " + response.credential);
         // document.getElementById("bodyLoading").style.opacity = "0.5"; 
        	//console.log("Encoded JWT ID token: " + response.credential);
        	const responsePayload = decodeJwtResponse(response.credential);
					var email = responsePayload.email,
					name = responsePayload.name ;
    			var dataString = 'email='+email+'&name='+name;
	    		$.ajax({
		        type: "POST",
		        url: app_base_url + "index.php/auth/social_network_login_auth/google",
		        data: dataString,
		        dataType:"json",
		        cache: false,
		        success: function(html){
		        	//alert(html);
		        	if (html.status == 1) {
		        		$("body").css("opacity", ".1");
	            		location.reload();
		        	}
		           /*1 == html.status ? ($("body").css("opacity", ".1"), location.reload()) : ($("body").css("opacity", "1"), location.reload())*/
		        }
	        });
        }
        window.onload = function () {
          google.accounts.id.initialize({
            client_id: "<?=$client_id?>",
            callback: handleCredentialResponse
          });
          google.accounts.id.renderButton(
            document.getElementById("buttonDiv"),
            { theme: "outline", size: "large" }  // customization attributes
          );
		  google.accounts.id.renderButton(
            document.getElementById("buttonBookingDiv"),
            { theme: "outline", size: "large" }  // customization attributes
          );
          //google.accounts.id.prompt(); // also display the One Tap dialog
        }
        function decodeJwtResponse (token) {
					var base64Url = token.split('.')[1];
					var base64 = base64Url.replace(/-/g, '+').replace(/_/g, '/');
					var jsonPayload = decodeURIComponent(atob(base64).split('').map(function(c) {
					        return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2);
					}).join(''));

					return JSON.parse(jsonPayload);
				};
    </script>
  </body>
</html>