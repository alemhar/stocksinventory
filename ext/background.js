
chrome.runtime.onMessage.addListener(
  (request, sender, sendResponse) => {
    if (request.from === "content" && request.action === "getstorage"){
      user_id = localStorage.getItem('user_id');
      api_key = localStorage.getItem('api_key');		
      sendResponse({user_id: user_id,api_key: api_key});
    }
  });