
chrome.runtime.onMessage.addListener(
  (request, sender, sendResponse) => {
    if (request.from === "content" && request.action === "getstorage"){
      username = localStorage.getItem('username');
      password = localStorage.getItem('password');		
      sendResponse({username: username,password: password});
    }
  });