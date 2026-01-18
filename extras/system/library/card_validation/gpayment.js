  var serverTransId;
  var _callbackFn;
  var challengeResultReady = false;
  var iframeContainer = $('#iframeDiv');
  var iframeId = String(Math.floor(100000 + Math.random() * 900000));
  function executeIframes(data) {
      $('<iframe id="' + "3ds_" + iframeId
          + '" width="0" height="0" style="border:0;visibility: hidden;" src="'
          + data.threeDSServerCallbackUrl + '"></iframe>')
      .appendTo(iframeContainer);
      if (data.monUrl) {
          $('<iframe id="' + "mon_" + iframeId
              + '" width="0" height="0" style="border:0;visibility: hidden;" src="'
              + data.monUrl + '"></iframe>')
          .appendTo(iframeContainer);
      }
  }
    function gpaymentInit(){
        $('.p_loader').show();
        $('.card_response').html('');
        var pdata = {accountID:$('#cardNo').val()};
        $.post(app_base_url+'index.php/gpayments/initAuth',pdata,function(res){ 
            console.log('initAuth res',res);
            if(res.threeDSServerTransID){ 
                serverTransId = res.threeDSServerTransID;
                if (res.threeDSServerCallbackUrl) {
                    executeIframes(res);
                }
            }
        },'json').fail(function(xhr, status, error) {
            console.error("Error: ", status, error);
            showError(error);
        });
    }
    function gpaymentAuth(reqTransId){
        $("#3ds_" + iframeId).remove();
        var url = app_base_url + 'index.php/gpayments/auth';
        var authData = {
            reqTransId:reqTransId,
            acctNumber:$('#cardNo').val(),
            expYear:$('#card_expiry_year').val(),
            expMonth:$('#card_expiry_month').val(),
            ownrName:$('#ownrName').val(),
            amount:$('#new_total_booking_amount').val(),
        };
        $.post(app_base_url+'index.php/gpayments/auth',authData,function(res){
            console.log('authResp:',res);
            if(res.error){
                showError(res.error);
            }else{
                if(res.status=='OK'){
                    console.log("approved");
                    showSuccess(res.msg);
                }else{
                    _onDoAuthSuccess(res);
                }
            }
        },'json').fail(function(xhr, status, error) {
            console.error("Error: ", status, error);
            showError(error);
        });
    }
    function getBrwResult(threeDSServerTransID, callbackFn) {
        console.log("Get brw authentication result for threeDSServerTransID: ",threeDSServerTransID);
        $.get(app_base_url+'index.php/gpayments/getThreeRIResult/'+threeDSServerTransID,function(res){ 
            console.log('getThreeRIResult res',res);
            if(res.error){
                showError(res.error);
            }else{
                if(res.status=='OK'){
                    console.log("approved");
                    showSuccess(res.msg);
                }else{
                    _onDoAuthSuccess(res);
                }
            }
        },'json').fail(function(xhr, status, error) {
            console.error("Error: ", status, error);
            showError(error);
        });
    }
  /**
   Default _callbackFn method for challenge flow. 3DS Server will call this method to get auth result when the challenge finish
  **/
  function _onAuthResult(data) {
      console.log('authentication result is ready: ',data);
      getChallengeAuthResult(serverTransId, _callbackFn);
  }

  /**
   * Method used to get challenge authentication result
   * @param threeDSServerTransID
   * @param callbackFn
   */
  function getChallengeAuthResult(threeDSServerTransID, callbackFn) {
      if (!challengeResultReady) {
          //remove challenge iframe if exist
          if (document.getElementById("cha_" + iframeId)) {
              console.log("Remove challenge iframe");
              $("#cha_" + iframeId).remove();
              $('.paraload').show();
          }
          challengeResultReady = true;
          getBrwResult(threeDSServerTransID, callbackFn);
      } else {
          console.log("Auth result has been retrieved.");
      }
  }
  function _onDoAuthSuccess(data) {
      console.log('auth returns:', data);
      if (data.transStatus) {
          if (data.transStatus === "Y") {
              console.log("transaction verified success!.");
          }else if (data.transStatus === "C") {
              startChallenge(data);
          } else if (data.transStatus === "D") {
              //_startDecoupledAuth(data, getBrwResult);
          } else {
              _callbackFn("onAuthResult", data);
          }
      } else {
          showError("transStatus status not found");
      }
  }
  function startChallenge(data) {

      if (data.challengeUrl) {
          challengeResultReady = false;
          _callbackFn("onChallengeStart");
          //create the iframe
          $('.paraload').hide();
          $('<iframe src="' + data.challengeUrl
              + '" width="100%" height="100%" style="border:0" id="' + "cha_"
              + iframeId
              + '"></iframe>')
          .appendTo(iframeContainer).height(430);
          if (data.resultMonUrl) {
              console.log("Start polling for challenge result");
              _doPolling(data.resultMonUrl, getChallengeAuthResult);
          } else {
              console.log("No resultMonUrl provided, challenge timout monitoring is skipped.");
          }
      } else {
          showError("Invalid Challenge Callback Url");
      }
  }
  function _doPolling(url, authReadyCallback) {
      console.log("call mon url: ", url);
      $.get(url)
      .done(function (data) {
          console.log('returns:', data);
          if (!data.event) {
              showError("Invalid mon url result");
          }
          if (data.event === "AuthResultNotReady") {
              console.log("AuthResultNotReady, retry in 2 seconds");
              setTimeout(function () {
                  _doPolling(url, authReadyCallback)
              }, 2000);
          } else if (data.event === "AuthResultReady") {
              console.log('AuthResultReady');
              authReadyCallback(serverTransId, _callbackFn);
          } else {
              showError("Invalid mon url result event type");
          }
      })
      .fail(function (error) {
          callbackFn("onError", error.responseJSON);
      });
  }
  function _callbackFn(type, data) {
      console.log('_callbackFn:',type,data);
      switch (type) {
          case "onAuthResult":
              //display "Show results in separate page"
              //$("#sepButton").removeClass("d-none");
              showResult(data);
              break;

          case "on3RIResult":
              showResult(data);
              break;

          case "onEnrolResult":
              showResult(data);
              break;

          case "onChallengeStart":
              //remove the spinner to show the challenge window
              //$("#spinner").remove();
              console.log('challenge window opened.');
              $("#iframeDiv").removeClass("d-none");
              break;

          case "onDecoupledAuthStart":
              $("#decoupledLabelTag").removeClass("d-none");
              showDecoupleInfo(data);
              break;

          case "onError":
              showError(data.Error);
              break;

          default:
              showError("Unknown callback type");
              break;
      }
  }
  function showResult(data) {
      //showData(data);
  }

  function showError(msg) {
      $('.card_response').html('<div class="alert alert-danger"><strong>Error!</strong> '+(msg ? msg :'Invalid Payment Deatils!')+'</div>');
      $('html, body').animate({ scrollTop: $('.card_response').offset().top }, 1000);
      $('.p_loader').hide();
  }
  function showSuccess(msg) {
      $('.p_loader').hide();
      gbuild_confirm_modal('flight'); 
      // $('.card_response').html('<div class="alert alert-success"><strong>Success!</strong> '+(msg ? msg :'Transaction verified successful.')+'</div>');
      // $('html, body').animate({ scrollTop: $('.card_response').offset().top }, 1000);
  }