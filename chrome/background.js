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
      }
      sendResponse({});
    }
);

function login(username,password) {
    let content = {
      v:'1.0.0',
      time:'xxxx',
      token:null,
      cmd:'user.login',
      body:{
        username:username,
        password:password
      }
    }
    sokect.send(JSON.stringify(content));
}


var ws = function (url) {
  this.sokect = null;
  this.url = url;
  this.reconnectTimer = null;
  this.reconnectTimeStep = 500;
  this.connect = function () {
    let self = this;
    this.sokect = new WebSocket(this.url);
    this.sokect.onopen = function () {
      console.log('open');
    }
    this.sokect.onmessage = function (evt) {
      var received_msg = evt.data;
      received_msg = JSON.parse(received_msg);
      popupPort.postMessage({cmd:received_msg.cmd,data:received_msg});
      console.log('数据已接收:',received_msg);
    }

    this.sokect.onclose = function()
    {
      // 关闭 websocket
      console.log('连接已关闭');
      self.reconnect();
    };

    this.sokect.onerror = function () {
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

  this.send = function (content) {
    if(this.sokect.readyState == 1){
      this.sokect.send(content);
    }
  }

}

var sokect = new ws('ws://localhost:9502');
sokect.connect();

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




