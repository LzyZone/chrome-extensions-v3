// Initialize butotn with users's prefered color
let loginBtn = document.getElementById("login-btn");
let testBtn = document.getElementById("test-btn");


chrome.storage.sync.get(['token'], function(result) {
  if(result.token){
    document.getElementById("login").style.display = 'none';
    document.getElementById("options").style.display = 'block';
  }
});


// When the button is clicked, inject setPageBackgroundColor into current page
loginBtn.addEventListener("click", async () => {
  let username = document.getElementById('username').value;
  let password = document.getElementById('password').value;

  chrome.runtime.sendMessage({cmd: "user.login",params:{username:username,password:password}}, function(response) {
    console.log(response);
  });
});

testBtn.addEventListener("click", async () => {
  chrome.runtime.sendMessage({cmd: "msg.listings",params:{}}, function(response) {

  });
});



function notify(msg,title) {
  chrome.notifications.getPermissionLevel(function (level) {
    if(level == 'granted'){
      chrome.notifications.create('', {
        type: "basic",
        title: title || '系统通知',
        message: msg,
        iconUrl:"/images/get_started128.png"
      }, callback);
    }
  });
}

function callback(notificationId) {
  //alert(notificationId);
  //console.log("Last error:", chrome.runtime.lastError);
}


var port = chrome.runtime.connect({name: "popup"});
//port.postMessage({joke: "Knock knock"});
port.onMessage.addListener(function(msg) {
  if (msg.cmd == "user.login"){
      notify(msg.data.err_msg);
  }
});