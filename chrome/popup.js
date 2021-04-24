// Initialize butotn with users's prefered color
let loginBtn = document.getElementById("login-btn");
<<<<<<< HEAD
let login = document.getElementById("login");
let loginOut = document.getElementById("login-out");
let username,password;

chrome.storage.sync.get(['token'], function(result) {
  console.log('Value currently is ' + result.token);
  notify('token='+result.token);
  if(result.token){
    chrome.runtime.sendMessage({cmd: "check.login",params:{token:result.token}}, function(response) {
      console.log(response);
    });
=======
let testBtn = document.getElementById("test-btn");


chrome.storage.sync.get(['token'], function(result) {
  if(result.token){
    document.getElementById("login").style.display = 'none';
    document.getElementById("options").style.display = 'block';
>>>>>>> ef5616327c20161ef96f769993449daabe998db5
  }
});


// When the button is clicked, inject setPageBackgroundColor into current page
loginBtn.addEventListener("click", async () => {
<<<<<<< HEAD

  username = document.getElementById('username').value;
  password = document.getElementById('password').value;
=======
  let username = document.getElementById('username').value;
  let password = document.getElementById('password').value;
>>>>>>> ef5616327c20161ef96f769993449daabe998db5

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
port.onMessage.addListener(function(data) {
  if (data.cmd == "user.login"){
      if (data.err_code == 0) {
        chrome.storage.sync.set({'username': username,'password':password,'token':data.token}, function() {

        });
        login.style.display = 'none';
        loginOut.style.display = 'block';
      }
      //notify(data.content.err_msg);
      //notify(username+',token='+data.content.token);
  }
});