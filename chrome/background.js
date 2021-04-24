let color = '#3aa757';

chrome.runtime.onInstalled.addListener(() => {
  chrome.storage.sync.set({ color });
  console.log('Default background color set to %cgreen', `color: ${color}`);
});

chrome.runtime.onMessage.addListener(
    function(request, sender, sendResponse) {
      console.log(sender.tab ?
          "from a content script:" + sender.tab.url :
          "from the extension");
      let params = request.params;
      switch (request.cmd) {
        case 'notify':
          notify(params.msg,params.title);
          break;
        case 'user.login':
          login(params.username,params.password);
          break;
        case 'test':
          sokect.send('test',{});
          break;
      }
      sendResponse({});
    }
);

function login(username,password) {
<<<<<<< HEAD
    let content = {
      v:'1.0.0',
      time:'xxxx',
      token:token,
      cmd:'user.login',
      body:{
        username:username,
        password:password
      }
    }
    socket.send(JSON.stringify(content));
=======

    let params = {
      username:username,
      password:password
    }
    sokect.send('user.login',params);
>>>>>>> ef5616327c20161ef96f769993449daabe998db5
}


var ws = function (url) {
  this.socket = null;
  this.url = url;
  this.reconnectTimer = null;
  this.reconnectTimeStep = 500;
  this.connect = function () {
    let self = this;
    this.socket = new WebSocket(this.url);
    this.socket.onopen = function () {
      console.log('open');
    }
    this.socket.onmessage = function (evt) {
      var received_msg = evt.data;
<<<<<<< HEAD
      received_data = JSON.parse(received_msg);
      console.log('数据已接收:',received_data);
      switch (received_data.cmd) {
        case 'user.login':
          if(received_data.err_code == 0){
            console.log('ping...');
            socket.ping();
          }
          popupPort.postMessage(received_data);
          break;
        case 'ping':

          break;
      }
=======
      received_msg = JSON.parse(received_msg);
      if(received_msg.cmd == 'user.login' && received_msg.err_code == 0){
          chrome.storage.sync.set({token: received_msg.body.token}, function() {
            console.log('Value is set to ' + received_msg.body.token);
          });
      }
      popupPort.postMessage({cmd:received_msg.cmd,data:received_msg});
      console.log('数据已接收:',received_msg);
>>>>>>> ef5616327c20161ef96f769993449daabe998db5
    }

    this.socket.onclose = function()
    {
      // 关闭 websocket
      console.log('连接已关闭');
      self.reconnect();
    };

    this.socket.onerror = function () {
      self.reconnect();
    }
  }

  this.reconnect = function () {
    let self = this;
    if(this.reconnectTimer == null){
      this.reconnectTimer = setTimeout(function () {
        console.log('reconnect');
        self.connect();
        self.reconnectTimer = null;
      },500);
    }
  }

<<<<<<< HEAD
  this.send = function (content) {
    if(this.socket.readyState == 1){
      this.socket.send(content);
=======
  this.send = function (cmd,params) {
    if(this.sokect.readyState == 1){
      let content = {
        v:'1.0.0',
        time:'xxxx',
        token:token,
        cmd:cmd,
        body:params
      }
      this.sokect.send(JSON.stringify(content));
>>>>>>> ef5616327c20161ef96f769993449daabe998db5
    }
  }

  this.ping = function () {
    setInterval(function () {
      console.log('socket,',socket.socket.readyState);
      if(socket.socket.readyState == 1){
        let content = {
          v:'1.0.0',
          time:'xxxx',
          token:token,
          cmd:'ping',
          body:{}
        }
        socket.send(JSON.stringify(content));
      }
    },1500);
  }

}

var socket = new ws('ws://localhost:9502');
socket.connect();

var token = null;
chrome.storage.sync.get(['token'], function(result) {
  token = result.token;
});

var token = null;
chrome.storage.sync.get(['token'], function(result) {
  token = result.token;
  console.log(token);
});


var popupPort = null;
chrome.runtime.onConnect.addListener(function(port) {
  console.assert(port.name == "popup");
  port.onMessage.addListener(function(msg) {
    if (msg.channel == "popup")
      port.postMessage({question: "Who's there?"});
    else if (msg.answer == "Madame")
      port.postMessage({question: "Madame who?"});
    else if (msg.answer == "Madame... Bovary")
      port.postMessage({question: "I don't get it."});
  });
  popupPort = port;
});




