self.addEventListener('push', function(event) {
    console.log('Push message received');

    var recipient,data,message,url,notificationTitle;
    var notificationOptions = {};

    self.registration.pushManager.getSubscription().then(subscription => {
        recipient=subscription.endpoint.substring(40, subscription.endpoint.length);
        data = {id: recipient};
        //console.log("ID: "+recipient);
        //console.log("Data: "+JSON.stringify(data));

        //Calling API to get message
        //console.log("Check: "+location.hostname);
        var med_host=location.hostname;

        if(med_host=="localhost"){
            apiUrl="http://localhost/blog-new-browser-notifications";
        }
        else if(med_host=="52.76.146.208"){
            apiUrl="https://52.76.146.208/blog-new-browser-notifications";

        }
        else{
            apiUrl="https://www.medinfi.com/blog";
        }
        apiUrl=apiUrl+"/notificationMessage";
        fetch(apiUrl, {

              method: "POST",
              body: JSON.stringify(data),
              headers: {
                  "Accept": "application/json",
                  "Content-Type": "application/json"
              }
              }).then(function(response) {
                  return response.json()
              }).then(function(result) {
                  console.log("Result: ");
                  console.log(result);

                  notificationOptions = {
                          body: result.message,
                          icon: './images/backend/logo.jpg',
                          data: {
                              url: result.url
                          }
                  };
                  notificationTitle=result.title;

                //event.waitUntil(

                    Promise.all([
                      self.registration.showNotification(
                        notificationTitle, notificationOptions)
                    ])
                  //);
          })
    });

});

self.addEventListener('notificationclick', function(event) {
  event.notification.close();

  var clickResponsePromise = Promise.resolve();
  if (event.notification.data && event.notification.data.url) {
    clickResponsePromise = clients.openWindow(event.notification.data.url);
  }

  ga('send', {hitType: 'event',eventCategory: "Browser Notification",eventAction:event.notification.data.url,eventLabel:event.notification.title});

  event.waitUntil(
    Promise.all([
      clickResponsePromise
    ])
  );
});