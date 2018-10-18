     function trackTask(capture_type,url){
                                $.ajax({
                                        type: "POST",
                                        url: baseUrl+"/analytics/trackingTask/",
                                        data: {'captureType': capture_type,'URL':url},
                                        success: function(data) { },
                                        error: function() {
                                            console.log('A problem Occurred!! Please contact to support');
                                        }
                                 });
     }

    $(document).ready(function() {
            var start = "START";
            var end = "END";
            flag= false;
            var url = window.location.href;
            if(document.hasFocus() === true){
            trackTask(start,url);
            }
            setInterval((function() {
                      if (document.hasFocus() === false && flag === false) {
                                trackTask(end,url);
                                flag=true;
                      } else if(document.hasFocus() === true && flag === true){
                                trackTask(start,url);
                                flag = false;
                      }
            }), 200);
            $(window).unload(function() {
                    trackTask(end,url);
            });
    });


    function trackEvents(eventName) {
        var url = window.location.href;
        var eventData = '{"eventType":"'+eventName+'", "eventUrl":"'+url+'"}';
        $.ajax({
                    type: "POST",
                    url: baseUrl+"/analytics/TrackEvent/",
                    data: {
                       'eventData':eventData,
                      },
                    success: function(data) {
                    },
                     error: function() {
                        console.log('A problem Occured!! Please contact to support');
                    }
                });
    }