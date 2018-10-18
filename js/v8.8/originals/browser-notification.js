document.addEventListener('DOMContentLoaded', function() {
    if (navigator.userAgent.search("Chrome")!=-1) {
            if (navigator.geolocation){
                navigator.geolocation.getCurrentPosition(checkCity,showError);
            }
            else{
                console.log("Geolocation is not supported by this browser.");
            }
        }
});

function showError(error) {
    switch(error.code) {
        case error.PERMISSION_DENIED:
            console.log("User denied the requesta for Geolocation.");
            break;
        case error.POSITION_UNAVAILABLE:
            console.log("Location information is unavailable.");
            break;
        case error.TIMEOUT:
            console.log("The request to get user location timed out.");
            break;
        case error.UNKNOWN_ERROR:
            console.log("An unknown error occurred.");
            break;
    }
}

function checkCity(position){
    // Get location data
    userLat = position.coords.latitude;
    userLong =position.coords.longitude;
    if(userLat!="" && userLong!==""){
    var params = "latitude="+userLat+"&longitude="+userLong;
    var ajax = new XMLHttpRequest();
    ajax.open("POST", baseUrl+"/user-location/", true);
    ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
    ajax.onload = function() {
            try{
                var jsonResponse = JSON.parse(this.responseText);
                console.log(jsonResponse['status']);
                if (ajax.status === 200) {
                console.log(jsonResponse['locality']);
                  if(jsonResponse['locality'] =="Hyderabad"||jsonResponse['locality'] =="Chennai"){
                      getNotificationAccess();
                    }
                }

            }
                catch(e){
                    return;
                }
        };
        ajax.send(params);
    }
}

function getNotificationAccess(){
    if(Notification.permission !== 'granted') {
        Notification.requestPermission().then(function(permission) {
            if(permission === 'granted' && 'serviceWorker' in navigator) {
                //navigator.serviceWorker.register("<?php echo HOST_NAME.Yii::app()->baseUrl.SERVICE_WORKER?>").then(initialiseState);
                navigator.serviceWorker.register(baseUrl+'/service-worker.js').then(initialiseState);
            } else {
                console.log('service worker not present');
            }
        });
    }
    //get subscription token if already subscribed
    if(Notification.permission === 'granted') {
        navigator.serviceWorker.ready.then(function(registration) {
            registration.pushManager.getSubscription().then(function(subscription){
                getToken(subscription);
            });
        });
    }
}

function initialiseState() {

    //check if notification is supported or not
    console.log("test");
    if(!('showNotification' in ServiceWorkerRegistration.prototype)) {
        console.warn('Notificaiton are not supported');
        console.log("test1");
        return;
    }
    //check if user has blocked push notification
    if(Notification.permission === 'denied'){
        console.warn('User has blocked the notification');
        console.log("test2");
    }
    //check if push messaging is supported or not
    if(!('PushManager' in window)) {
        console.warn('Push messaging is not supported');
        console.log("test3");
        return;
    }

    //subscribe to GCM
    navigator.serviceWorker.ready.then(function(serviceWorkerRegistration) {
        //call subscribe method on serviceWorkerRegistration object
        console.log("test4");
        serviceWorkerRegistration.pushManager.subscribe({userVisibleOnly: true})
            .then(function(subscription){
            console.log("test5");
            getToken(subscription);
        }).catch(function(err){
            console.error('Error occured while subscribe(): ', err);
        });
    });
}

function getToken(subscription) {
    console.log(subscription);
    var token = subscription.endpoint.substring(40, subscription.endpoint.length);
    //console.log("Token: "+token);
    //document.querySelector("#token").innerHTML = token;
    //adding the token to database.
    var postCategoryId=217;
    var params = "categoryId="+postCategoryId+"&recipient="+token;
    //var XMLHttpRequest = require('xhr2');
    var ajax = new XMLHttpRequest();
    ajax.open("POST", baseUrl+"/blog/BrowserNotificationSubscription/", true);
    ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
    ajax.onload = function() {
         try{

             var jsonResponse = JSON.parse(this.responseText);
             console.log("STATUS: "+jsonResponse['status']);
             console.log("DESC: "+jsonResponse['desc']);
             if (ajax.status === 200 && jsonResponse['status'] != 'fail') {
                 console.log("AJAX call success");
             }
         }
         catch(e){
             console.log("AJAX call failed");
             return;
         }
     };
     ajax.send(params);
}

//The below code was used for testing
function sendNotification() {
    $(document).ready(function () {
        var token = $("#token").val();
        var serverKey = "AIzaSyATqztxpRaf-zneOPESUC5KgyWdlbEDfRg";
        var host = "https://android.googleapis.com/gcm/send";
        var pars = {
            "to" : token
        }

        var request = $.ajax({
            url: host,
            type: 'post',
            data: JSON.stringify(pars),
            headers: {
                "Authorization": "key=" + serverKey,
                "Content-Type": "application/json"
            },
            dataType: 'json'
        });

        request.done(function(data){
            console.log("response: ", data);
        })

        request.fail(function(err) {
            console.log('error: ' , err);
        });
    });
}